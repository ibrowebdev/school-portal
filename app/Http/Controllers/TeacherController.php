<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /** index page */
    public function index()
    {
        $listTeacher = Teacher::with('user')->get();

        return view('teacher.list-teachers', compact('listTeacher'));
    }

    /** teacher Grid */
    public function teacherGrid()
    {
        $teacherGrid = Teacher::all();

        return view('teacher.teachers-grid', compact('teacherGrid'));
    }

    /** create page */
    public function create()
    {
        $users = User::where('type', User::TEACHER)->get();

        return view('teacher.add-teacher', compact('users'));
    }

    /** store record */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name'     => ['required', 'string'],
            'teacher_id'    => ['nullable', 'string'],
            'gender'        => ['required', 'string'],
            'experience'    => ['required', 'string'],
            'date_of_birth' => ['required', 'string'],
            'qualification' => ['required', 'string'],
            'phone_number'  => ['required', 'string'],
            'address'       => ['required', 'string'],
            'city'          => ['required', 'string'],
            'state'         => ['required', 'string'],
            'zip_code'      => ['required', 'string'],
            'country'       => ['required', 'string'],
        ]);

        try {
            Teacher::create($validated);

            return response()->json(['message' => 'Teacher record saved successfully!', 'redirect' => route('teachers.index')]);
        } catch (\Exception $e) {
            Log::error('Failed to save Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to save teacher record.'], 500);
        }
    }

    /** edit record */
    public function edit(Teacher $teacher)
    {
        // $teacher is automatically resolved by Route Model Binding
        $teacher->load('user');
        return view('teacher.edit-teacher', compact('teacher'));
    }

    /** update record */
    public function update(Request $request, Teacher $teacher): JsonResponse
    {
        $validated = $request->validate([
            'full_name'     => ['required', 'string'],
            'gender'        => ['required', 'string'],
            'date_of_birth' => ['required', 'string'],
            'qualification' => ['required', 'string'],
            'experience'    => ['required', 'string'],
            'phone_number'  => ['required', 'string'],
            'address'       => ['required', 'string'],
            'city'          => ['required', 'string'],
            'state'         => ['required', 'string'],
            'zip_code'      => ['required', 'string'],
            'country'       => ['required', 'string'],
        ]);

        try {
            $teacher->update($validated);

            return response()->json(['message' => 'Teacher record updated successfully!', 'redirect' => route('teachers.index')]);
        } catch (\Exception $e) {
            Log::error('Failed to update Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update teacher record.'], 500);
        }
    }

    /** delete record */
    public function destroy(Teacher $teacher): JsonResponse
    {
        try {
            $teacher->delete();

            return response()->json(['message' => 'Teacher record deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete teacher record.'], 500);
        }
    }

    public function show(Teacher $teacher)
    {
        // Not implemented in original, but needed for resource
        return view('teacher.show-teacher', compact('teacher'));
    }
}
