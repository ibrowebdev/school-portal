<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    /** add teacher page */
    public function teacherAdd()
    {
        $users = User::where('type', User::TEACHER)->get();

        return view('teacher.add-teacher', compact('users'));
    }

    /** teacher list — uses Eloquent relationship instead of raw join */
    public function teacherList()
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

    /** Save Record */
    public function saveRecord(Request $request): JsonResponse
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

            return response()->json(['message' => 'Teacher record saved successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to save Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to save teacher record.'], 500);
        }
    }

    /** Edit Record — uses Eloquent relationship instead of raw join */
    public function editRecord($teacher_id)
    {
        $teacher = Teacher::with('user')->where('teacher_id', $teacher_id)->firstOrFail();

        return view('teacher.edit-teacher', compact('teacher'));
    }

    /** Update Record */
    public function updateRecordTeacher(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id'            => ['required', 'exists:teachers,id'],
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
            $teacher = Teacher::findOrFail($validated['id']);
            unset($validated['id']);
            $teacher->update($validated);

            return response()->json(['message' => 'Teacher record updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to update Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update teacher record.'], 500);
        }
    }

    /** Delete Record */
    public function teacherDelete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'exists:teachers,id'],
        ]);

        try {
            Teacher::findOrFail($validated['id'])->delete();

            return response()->json(['message' => 'Teacher record deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete Teacher record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete teacher record.'], 500);
        }
    }
}
