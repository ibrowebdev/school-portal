<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use App\Models\AcademicSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount(['studentProfiles', 'subjects', 'sections'])
            ->orderBy('name')
            ->get();

        return view('academic.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('academic.classes.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:school_classes,name'],
            'level' => ['nullable', 'string', 'in:junior,senior'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'sections' => ['nullable', 'array'],
            'sections.*' => ['string', 'max:10'],
        ]);

        try {
            $class = SchoolClass::create([
                'name' => $validated['name'],
                'level' => $validated['level'] ?? null,
                'capacity' => $validated['capacity'] ?? null,
            ]);

            // Create sections if provided
            if (! empty($validated['sections'])) {
                foreach ($validated['sections'] as $sectionName) {
                    $class->sections()->create(['name' => trim($sectionName)]);
                }
            }

            return response()->json([
                'message' => 'Class created successfully!',
                'redirect' => route('school-classes.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create class', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create class.'], 500);
        }
    }

    public function show(SchoolClass $schoolClass)
    {
        $schoolClass->load(['sections', 'subjects', 'studentProfiles.user', 'teachers']);

        return view('academic.classes.show', compact('schoolClass'));
    }

    public function edit(SchoolClass $schoolClass)
    {
        $schoolClass->load('sections');
        $allSubjects = Subject::orderBy('name')->get();
        $teachers = User::teachers()->orderBy('first_name')->get();
        $currentSession = AcademicSession::current()->first();

        return view('academic.classes.edit', compact('schoolClass', 'allSubjects', 'teachers', 'currentSession'));
    }

    public function update(Request $request, SchoolClass $schoolClass): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:school_classes,name,' . $schoolClass->id],
            'level' => ['nullable', 'string', 'in:junior,senior'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ]);

        try {
            $schoolClass->update($validated);

            return response()->json([
                'message' => 'Class updated successfully!',
                'redirect' => route('school-classes.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update class', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update class.'], 500);
        }
    }

    public function destroy(SchoolClass $schoolClass): JsonResponse
    {
        try {
            $schoolClass->delete();

            return response()->json(['message' => 'Class deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete class', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete class.'], 500);
        }
    }

    /**
     * Map subjects to a class (sync pivot).
     */
    public function mapSubjects(Request $request, SchoolClass $schoolClass): JsonResponse
    {
        $validated = $request->validate([
            'subject_ids' => ['required', 'array'],
            'subject_ids.*' => ['exists:subjects,id'],
        ]);

        try {
            $schoolClass->subjects()->sync($validated['subject_ids']);

            return response()->json(['message' => 'Subjects mapped to class successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to map subjects', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to map subjects.'], 500);
        }
    }

    /**
     * Assign teachers to a class (sync pivot).
     */
    public function assignTeachers(Request $request, SchoolClass $schoolClass): JsonResponse
    {
        $validated = $request->validate([
            'assignments' => ['required', 'array', 'min:1'],
            'assignments.*.user_id' => ['required', 'exists:users,id'],
            'assignments.*.subject_id' => ['nullable', 'exists:subjects,id'],
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
        ]);

        try {
            // Build sync data
            $syncData = [];
            foreach ($validated['assignments'] as $assignment) {
                $syncData[] = [
                    'user_id' => $assignment['user_id'],
                    'school_class_id' => $schoolClass->id,
                    'subject_id' => $assignment['subject_id'] ?? null,
                    'academic_session_id' => $validated['academic_session_id'],
                ];
            }

            // Delete existing and re-create for this class + session
            $schoolClass->teachers()
                ->wherePivot('academic_session_id', $validated['academic_session_id'])
                ->detach();

            foreach ($syncData as $data) {
                \DB::table('class_teacher')->insert(array_merge($data, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            return response()->json(['message' => 'Teachers assigned successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to assign teachers', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to assign teachers.'], 500);
        }
    }
}
