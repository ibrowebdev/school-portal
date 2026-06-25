<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /** index page student list */
    public function index()
    {
        $studentList = Student::all();

        return view('student.student', compact('studentList'));
    }

    /** index page student grid */
    public function studentGrid()
    {
        $studentList = Student::all();

        return view('student.student-grid', compact('studentList'));
    }

    /** student add page */
    public function create()
    {
        return view('student.add-student');
    }

    /** Save Record */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'gender'        => ['required', 'not_in:0'],
            'date_of_birth' => ['required', 'date'],
            'roll'          => ['required', 'string', 'max:50'],
            'blood_group'   => ['required', 'string', 'max:10'],
            'religion'      => ['required', 'string', 'max:50'],
            'email'         => ['required', 'email', 'unique:students,email'],
            'class'         => ['required', 'string', 'max:50'],
            'section'       => ['required', 'string', 'max:50'],
            'admission_id'  => ['required', 'string', 'unique:students,admission_id'],
            'phone_number'  => ['required', 'numeric', 'digits_between:8,15'],
            'upload'        => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            $filename = time() . '_' . $request->file('upload')->getClientOriginalName();
            $request->file('upload')->move(public_path('student-photos'), $filename);

            Student::create(array_merge($validated, [
                'upload' => 'student-photos/' . $filename,
            ]));

            return response()->json(['message' => 'Student has been added successfully!', 'redirect' => route('students.index')]);
        } catch (\Exception $e) {
            Log::error('Student Save Failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to add student.'], 500);
        }
    }

    /** View */
    public function edit(Student $student)
    {
        $studentEdit = $student;
        return view('student.edit-student', compact('studentEdit'));
    }

    /** Update Record */
    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'gender'        => ['required', 'not_in:0'],
            'date_of_birth' => ['required', 'date'],
            'roll'          => ['required', 'string', 'max:50'],
            'blood_group'   => ['required', 'string', 'max:10'],
            'religion'      => ['required', 'string', 'max:50'],
            'email'         => ['required', 'email', 'unique:students,email,' . $student->id],
            'class'         => ['required', 'string', 'max:50'],
            'section'       => ['required', 'string', 'max:50'],
            'admission_id'  => ['required', 'string', 'unique:students,admission_id,' . $student->id],
            'phone_number'  => ['required', 'numeric', 'digits_between:8,15'],
            'upload'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            if ($request->hasFile('upload')) {
                // Delete old image
                if (! empty($student->upload) && file_exists(public_path($student->upload))) {
                    unlink(public_path($student->upload));
                }
                $filename = time() . '_' . $request->file('upload')->getClientOriginalName();
                $request->file('upload')->move(public_path('student-photos'), $filename);
                $validated['upload'] = 'student-photos/' . $filename;
            } else {
                unset($validated['upload']);
            }

            $student->update($validated);

            return response()->json(['message' => 'Student updated successfully!', 'redirect' => route('students.index')]);
        } catch (\Exception $e) {
            Log::error('Student Update Failed', ['error' => $e->getMessage(), 'id' => $student->id]);

            return response()->json(['message' => 'Failed to update student.'], 500);
        }
    }

    /** Delete Record */
    public function destroy(Student $student): JsonResponse
    {
        try {
            if (! empty($student->upload) && file_exists(public_path($student->upload))) {
                unlink(public_path($student->upload));
            }

            $student->delete();

            return response()->json(['message' => 'Student deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Student Deletion Failed', ['error' => $e->getMessage(), 'id' => $student->id]);

            return response()->json(['message' => 'Failed to delete student.'], 500);
        }
    }

    /** student profile page */
    public function show(Student $student)
    {
        $studentProfile = $student;
        return view('student.student-profile', compact('studentProfile'));
    }
}
