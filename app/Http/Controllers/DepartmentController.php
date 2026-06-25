<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /** index page department */
    public function index()
    {
        return view('department.list-department');
    }

    /** create page */
    public function create()
    {
        return view('department.add-department');
    }

    /** get data list */
    public function getDataList(Request $request): JsonResponse
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

        $totalRecords = Department::count();

        $searchQuery = function ($query) use ($searchValue) {
            $query->where('department_id', 'like', '%' . $searchValue . '%')
                ->orWhere('department_name', 'like', '%' . $searchValue . '%')
                ->orWhere('head_of_department', 'like', '%' . $searchValue . '%')
                ->orWhere('department_start_date', 'like', '%' . $searchValue . '%')
                ->orWhere('no_of_students', 'like', '%' . $searchValue . '%');
        };

        $totalRecordsWithFilter = Department::where($searchQuery)->count();

        $records = Department::where($searchQuery)
            ->orderBy($columnName, $columnSortOrder)
            ->skip($start)
            ->take($rowPerPage)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {
            $modify = '
                <td class="text-end">
                    <div class="actions">
                        <a href="' . route('departments.edit', $record->id) . '" class="btn btn-sm bg-danger-light">
                            <i class="far fa-edit me-2"></i>
                        </a>
                        <a class="btn btn-sm bg-danger-light delete department_id" data-bs-toggle="modal" data-department_id="' . $record->id . '" data-bs-target="#delete">
                        <i class="fe fe-trash-2"></i>
                        </a>
                    </div>
                </td>
            ';

            $data_arr[] = [
                'department_id'         => $record->department_id,
                'department_name'       => $record->department_name,
                'head_of_department'    => $record->head_of_department,
                'department_start_date' => $record->department_start_date,
                'no_of_students'        => $record->no_of_students,
                'modify'                => $modify,
            ];
        }

        return response()->json([
            'draw'                 => intval($draw),
            'iTotalRecords'        => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordsWithFilter,
            'aaData'               => $data_arr,
        ]);
    }

    /** Save Record */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'department_name'       => ['required', 'string'],
            'head_of_department'    => ['required', 'string'],
            'department_start_date' => ['required', 'string'],
            'no_of_students'        => ['required', 'string'],
        ]);

        try {
            Department::create($validated);

            return response()->json(['message' => 'Department has been added successfully!', 'redirect' => route('departments.index')]);
        } catch (\Exception $e) {
            Log::error('Failed to add Department record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to add department.'], 500);
        }
    }

    /** edit record */
    public function edit(Department $department)
    {
        return view('department.edit-departmen', compact('department'));
    }

    /** Update Record */
    public function update(Request $request, Department $department): JsonResponse
    {
        $validated = $request->validate([
            'department_name'       => ['required', 'string'],
            'head_of_department'    => ['required', 'string'],
            'department_start_date' => ['required', 'string'],
            'no_of_students'        => ['required', 'string'],
        ]);

        try {
            $department->update($validated);

            return response()->json(['message' => 'Department record updated successfully!', 'redirect' => route('departments.index')]);
        } catch (\Exception $e) {
            Log::error('Failed to update Department record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update department.'], 500);
        }
    }

    /** Delete Record */
    public function destroy(Department $department): JsonResponse
    {
        try {
            $department->delete();

            return response()->json(['message' => 'Department record deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete Department record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete department.'], 500);
        }
    }

    public function show(Department $department)
    {
        //
    }
}
