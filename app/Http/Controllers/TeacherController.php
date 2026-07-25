<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'id');
    }

    /** index page */
    public function index()
    {
        $listTeacher = User::teachers()
            ->with(['teacherProfile', 'assignedClasses'])
            ->orderByDesc('id')
            ->get();

        $teacherIds = $listTeacher->pluck('id');
        $assignments = DB::table('class_teacher')
            ->whereIn('class_teacher.user_id', $teacherIds)
            ->join('school_classes', 'class_teacher.school_class_id', '=', 'school_classes.id')
            ->leftJoin('subjects', 'class_teacher.subject_id', '=', 'subjects.id')
            ->select('class_teacher.user_id', 'school_classes.name as class_name', 'subjects.name as subject_name')
            ->get();
        
        $assignmentsByUser = $assignments->groupBy('user_id');

        return view('teacher.list-teachers', compact('listTeacher', 'assignmentsByUser'));
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

        try {
            DB::transaction(function () use ($request, $validated) {
                // Handle avatar upload
                $avatarPath = 'photo_defaults.jpg';
                if ($request->hasFile('avatar')) {
                    $filename = time().'_'.$request->file('avatar')->getClientOriginalName();
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
            });

            return response()->json([
                'message' => 'Teacher record saved successfully!',
                'redirect' => route('teachers.index'),
            ]);
        } catch (\Exception $e) {
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

        try {
            DB::transaction(function () use ($request, $validated, $teacher) {
                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    if ($teacher->avatar !== 'photo_defaults.jpg' && ! empty($teacher->avatar) && file_exists(public_path('images/'.$teacher->avatar))) {
                        unlink(public_path('images/'.$teacher->avatar));
                    }
                    $filename = time().'_'.$request->file('avatar')->getClientOriginalName();
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
            });

            return response()->json([
                'message' => 'Teacher record updated successfully!',
                'redirect' => route('teachers.index'),
            ]);
        } catch (\Exception $e) {
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

    public function show(User $id)
    {
        $teacher = $id;
        $teacher->load(['teacherProfile']);
        
        $assignments = \Illuminate\Support\Facades\DB::table('class_teacher')
            ->where('class_teacher.user_id', $teacher->id)
            ->join('school_classes', 'class_teacher.school_class_id', '=', 'school_classes.id')
            ->leftJoin('subjects', 'class_teacher.subject_id', '=', 'subjects.id')
            ->select(
                'school_classes.name as class_name', 
                'school_classes.level', 
                'school_classes.capacity', 
                'subjects.name as subject_name'
            )
            ->get();

        return view('teacher.show-teacher', compact('teacher', 'assignments'));
    }
}
