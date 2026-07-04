<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'id');
    }

    /** index page student list */
    public function index()
    {
        $studentList = User::students()
            ->with('studentProfile.schoolClass', 'studentProfile.classSection')
            ->orderByDesc('id')
            ->get();

        return view('student.student', compact('studentList'));
    }

    /** index page student grid */
    public function studentGrid()
    {
        $studentList = User::students()
            ->with('studentProfile.schoolClass')
            ->orderByDesc('id')
            ->get();

        return view('student.student-grid', compact('studentList'));
    }

    /** student add page */
    public function create()
    {
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $parents = User::parents()->orderBy('first_name')->get();

        return view('student.add-student', compact('classes', 'parents'));
    }

    /** Save Record */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Handle avatar upload
            $avatarPath = 'photo_defaults.jpg';
            if ($request->hasFile('avatar')) {
                $filename = time().'_'.$request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('student-photos'), $filename);
                $avatarPath = 'student-photos/'.$filename;
            }

            // Create the user record
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'phone_number' => $validated['phone_number'] ?? null,
                'type' => User::STUDENT,
                'status' => 'active',
                'avatar' => $avatarPath,
                'join_date' => now()->toDateString(),
                'password' => Hash::make('password'), // default password
            ]);

            $user->assignRole(User::STUDENT);

            // Create the student profile
            StudentProfile::create([
                'user_id' => $user->id,
                'admission_id' => $validated['admission_id'],
                'roll_number' => $validated['roll_number'] ?? null,
                'school_class_id' => $validated['school_class_id'],
                'class_section_id' => $validated['class_section_id'] ?? null,
                'blood_group' => $validated['blood_group'] ?? null,
                'religion' => $validated['religion'] ?? null,
                'address' => $validated['address'] ?? null,
                'parent_id' => $validated['parent_id'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Student has been added successfully!',
                'redirect' => route('students.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student Save Failed', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to add student.'], 500);
        }
    }

    /** View */
    public function edit($id)
    {
        $studentEdit = User::with('studentProfile')->findOrFail($id);
        $classes = SchoolClass::with('sections')->orderBy('name')->get();
        $parents = User::parents()->orderBy('first_name')->get();

        return view('student.edit-student', compact('studentEdit', 'classes', 'parents'));
    }

    /** Update Record */
    public function update(UpdateStudentRequest $request, $id): JsonResponse
    {
        $validated = $request->validated();
        $student = User::findOrFail($id);

        DB::beginTransaction();
        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                if ($student->avatar !== 'photo_defaults.jpg' && ! empty($student->avatar) && file_exists(public_path($student->avatar))) {
                    unlink(public_path($student->avatar));
                }
                $filename = time().'_'.$request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move(public_path('student-photos'), $filename);
                $student->avatar = 'student-photos/'.$filename;
            }

            // Update user record
            $student->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'phone_number' => $validated['phone_number'] ?? null,
            ]);

            // Update or create student profile
            $student->studentProfile()->updateOrCreate(
                ['user_id' => $student->id],
                [
                    'admission_id' => $validated['admission_id'],
                    'roll_number' => $validated['roll_number'] ?? null,
                    'school_class_id' => $validated['school_class_id'],
                    'class_section_id' => $validated['class_section_id'] ?? null,
                    'blood_group' => $validated['blood_group'] ?? null,
                    'religion' => $validated['religion'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'parent_id' => $validated['parent_id'] ?? null,
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Student updated successfully!',
                'redirect' => route('students.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Student Update Failed', ['error' => $e->getMessage(), 'id' => $id]);

            return response()->json(['message' => 'Failed to update student.'], 500);
        }
    }

    /** Delete Record */
    public function destroy($id): JsonResponse
    {
        $student = User::findOrFail($id);

        try {
            if (! empty($student->avatar) && $student->avatar !== 'photo_defaults.jpg' && file_exists(public_path($student->avatar))) {
                unlink(public_path($student->avatar));
            }

            $student->delete(); // Soft delete; profile cascades

            return response()->json(['message' => 'Student deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Student Deletion Failed', ['error' => $e->getMessage(), 'id' => $id]);

            return response()->json(['message' => 'Failed to delete student.'], 500);
        }
    }

    /** student profile page */
    public function show($id)
    {
        $studentProfile = User::with([
            'studentProfile.schoolClass',
            'studentProfile.classSection',
            'studentProfile.parent',
            'results.subject',
        ])->findOrFail($id);

        return view('student.student-profile', compact('studentProfile'));
    }

    /** Get class sections via AJAX */
    public function getClassSections(SchoolClass $schoolClass): JsonResponse
    {
        return response()->json($schoolClass->sections);
    }
}
