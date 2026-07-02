<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    /** index page */
    public function index()
    {
        $subjectList = Subject::withCount('classes')
            ->orderBy('name')
            ->get();

        return view('subjects.subject_list', compact('subjectList'));
    }

    /** subject add */
    public function create()
    {
        return view('subjects.subject_add');
    }

    /** Save Record */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10', 'unique:subjects,code'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            Subject::create($validated);

            return response()->json([
                'message' => 'Subject record saved successfully!',
                'redirect' => route('subjects.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save Subject record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to save subject record.'], 500);
        }
    }

    /** subject edit view */
    public function edit(Subject $subject)
    {
        $subjectEdit = $subject;

        return view('subjects.subject_edit', compact('subjectEdit'));
    }

    /** Update Record */
    public function update(Request $request, Subject $subject): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:10', 'unique:subjects,code,' . $subject->id],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            $subject->update($validated);

            return response()->json([
                'message' => 'Subject record updated successfully!',
                'redirect' => route('subjects.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update Subject record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update subject record.'], 500);
        }
    }

    /** Delete Record */
    public function destroy(Subject $subject): JsonResponse
    {
        try {
            $subject->delete();

            return response()->json(['message' => 'Subject record deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete Subject record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete subject record.'], 500);
        }
    }

    public function show(Subject $subject)
    {
        $subject->load('classes');

        return view('subjects.subject_show', compact('subject'));
    }
}
