<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /** index page */
    public function index()
    {
        return view('usermanagement.list_users');
    }

    /** user view edit */
    public function edit(User $user)
    {
        $role = Role::all();
        $users = $user;

        return view('usermanagement.user_update', compact('role', 'users'));
    }

    /** user Update */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone_number' => ['required', 'string', 'max:255'],
            'status'       => ['required', 'string', 'max:255'],
            'role_name'    => ['required', 'string', 'max:255'],
            'position'     => ['required', 'string', 'max:255'],
            'department'   => ['required', 'string', 'max:255'],
            'avatar'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('avatar')) {
                // Delete old avatar if it's not the default
                if ($user->avatar !== 'photo_defaults.jpg' && ! empty($user->avatar) && file_exists(public_path('images/' . $user->avatar))) {
                    unlink(public_path('images/' . $user->avatar));
                }
                $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('images'), $filename);
                $validated['avatar'] = $filename;
            } else {
                unset($validated['avatar']);
            }

            // Sync type and role
            $validated['type'] = $validated['role_name'];
            unset($validated['role_name']);

            $user->update($validated);
            $user->syncRoles([$validated['type']]);

            return response()->json(['message' => 'User updated successfully!', 'redirect' => route('users.index')]);
        } catch (\Exception $e) {
            Log::error('User Update Failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update user.'], 500);
        }
    }

    /** user delete */
    public function destroy(User $user): JsonResponse
    {
        try {
            if ($user->avatar !== 'photo_defaults.jpg' && ! empty($user->avatar) && file_exists(public_path('images/' . $user->avatar))) {
                unlink(public_path('images/' . $user->avatar));
            }

            $user->delete();

            return response()->json(['message' => 'User deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('User Deletion Failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete user.'], 500);
        }
    }

    public function show(User $user)
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    /** change password */
    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password'     => ['required', new MatchOldPassword],
            'new_password'         => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

            return response()->json(['message' => 'Password changed successfully!']);
        } catch (\Exception $e) {
            Log::error('Password Change Failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to change password.'], 500);
        }
    }

    /** get users data */
    public function getUsersData(Request $request): JsonResponse
    {
        $draw            = $request->get('draw');
        $start           = $request->get('start');
        $rowPerPage      = $request->get('length');
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column'];
        $columnName      = $columnName_arr[$columnIndex]['data'];
        $columnSortOrder = $order_arr[0]['dir'];
        $searchValue     = $search_arr['value'];

        $totalRecords = User::count();

        $searchQuery = function ($query) use ($searchValue) {
            $query->where('name', 'like', '%' . $searchValue . '%')
                ->orWhere('email', 'like', '%' . $searchValue . '%')
                ->orWhere('position', 'like', '%' . $searchValue . '%')
                ->orWhere('phone_number', 'like', '%' . $searchValue . '%')
                ->orWhere('join_date', 'like', '%' . $searchValue . '%')
                ->orWhere('type', 'like', '%' . $searchValue . '%')
                ->orWhere('status', 'like', '%' . $searchValue . '%');
        };

        $totalRecordsWithFilter = User::where($searchQuery)->count();

        $records = User::where($searchQuery)
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {
            $modify = '
                <td class="text-end">
                    <div class="actions">
                        <a href="' . route('users.edit', $record->id) . '" class="btn btn-sm bg-danger-light">
                            <i class="far fa-edit me-2"></i>
                        </a>
                        <a class="btn btn-sm bg-danger-light delete_user" data-bs-toggle="modal" data-id="' . $record->id . '" data-bs-target="#delete_user">
                            <i class="fe fe-trash-2"></i>
                        </a>
                    </div>
                </td>
            ';
            if ($record->status == 'Active') {
                $status = '<span class="badge badge-success">' . $record->status . '</span>';
            } else {
                $status = '<span class="badge badge-danger">' . $record->status . '</span>';
            }

            $profile = '
                <h2 class="table-avatar">
                    <a class="avatar avatar-sm me-2">
                        <img class="avatar-img rounded-circle" src="' . url('images/' . $record->avatar) . '" alt="User Image">
                    </a>
                    <a>' . $record->name . '</a>
                </h2>
            ';

            $data_arr[] = [
                'user_id'      => $record->user_id,
                'name'         => $profile,
                'email'        => $record->email,
                'position'     => $record->position,
                'phone_number' => $record->phone_number,
                'join_date'    => $record->join_date,
                'type'         => $record->type,
                'status'       => $status,
                'modify'       => $modify,
            ];
        }

        return response()->json([
            'draw'                 => intval($draw),
            'iTotalRecords'        => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordsWithFilter,
            'aaData'               => $data_arr,
        ]);
    }
}
