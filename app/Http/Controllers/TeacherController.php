<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /** index page */
    public function index()
    {
        $listTeacher = User::teachers()
            ->with('teacherProfile')
            ->orderByDesc('id')
            ->get();

        return view('teacher.list-teachers', compact('listTeacher'));
    }

    /** teacher Grid */
    public function teacherGrid()
    {
        $teacherGrid = User::teachers()
            ->with('teacherProfile')
            ->orderByDesc('id')
            ->get();

        return view('teacher.teachers-grid', compact('teacherGrid'));
    }

    /** create page */
    public function create()
    {
        return view('teacher.add-teacher');
    }

    /** store record */
    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Handle avatar upload
            $avatarPath = 'photo_defaults.jpg';
            if ($request->hasFile('avatar')) {
                $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('images'), $filename);
                $avatarPath = $filename;
            }

            // Create the user record
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'phone_number' => $validated['phone_number'],
                'type' => User::TEACHER,
                'status' => 'active',
                'avatar' => $avatarPath,
                'join_date' => now()->toDateString(),
                'password' => Hash::make('password'),
            ]);

            $user->assignRole(User::TEACHER);

            // Create the teacher profile
            TeacherProfile::create([
                'user_id' => $user->id,
                'employee_id' => $validated['employee_id'] ?? null,
                'qualification' => $validated['qualification'],
                'experience' => $validated['experience'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'zip_code' => $validated['zip_code'] ?? null,
                'country' => $validated['country'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Teacher record saved successfully!',
                'redirect' => route('teachers.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to save teacher record.'], 500);
        }
    }

    /** edit record */
    public function edit($id)
    {
        $teacher = User::with('teacherProfile')->findOrFail($id);

        return view('teacher.edit-teacher', compact('teacher'));
    }

    /** update record */
    public function update(UpdateTeacherRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $teacher = User::findOrFail($id);

        DB::beginTransaction();
        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                if ($teacher->avatar !== 'photo_defaults.jpg' && ! empty($teacher->avatar) && file_exists(public_path('images/' . $teacher->avatar))) {
                    unlink(public_path('images/' . $teacher->avatar));
                }
                $filename = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('images'), $filename);
                $teacher->avatar = $filename;
            }

            // Update user record
            $teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'phone_number' => $validated['phone_number'],
            ]);

            // Update or create teacher profile
            $teacher->teacherProfile()->updateOrCreate(
                ['user_id' => $teacher->id],
                [
                    'employee_id' => $validated['employee_id'] ?? null,
                    'qualification' => $validated['qualification'],
                    'experience' => $validated['experience'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'state' => $validated['state'] ?? null,
                    'zip_code' => $validated['zip_code'] ?? null,
                    'country' => $validated['country'] ?? null,
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Teacher record updated successfully!',
                'redirect' => route('teachers.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update teacher record.'], 500);
        }
    }

    /** delete record */
    public function destroy($id): JsonResponse
    {
        $teacher = User::findOrFail($id);

        try {
            $teacher->delete();

            return response()->json(['message' => 'Teacher record deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete teacher record.'], 500);
        }
    }

    public function show($id)
    {
        $teacher = User::with(['teacherProfile', 'assignedClasses'])->findOrFail($id);

        return view('teacher.show-teacher', compact('teacher'));
    }
}
