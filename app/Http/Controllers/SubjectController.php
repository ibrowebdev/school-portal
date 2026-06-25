<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubjectController extends Controller
{
    /** index page */
    public function subjectList()
    {
        $subjectList = Subject::all();

        return view('subjects.subject_list', compact('subjectList'));
    }

    /** subject add */
    public function subjectAdd()
    {
        return view('subjects.subject_add');
    }

    /** Save Record */
    public function saveRecord(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_name' => ['required', 'string'],
            'class'        => ['required', 'string'],
        ]);

        try {
            Subject::create($validated);

            return response()->json(['message' => 'Subject record saved successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to save Subject record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to save subject record.'], 500);
        }
    }

    /** subject edit view */
    public function subjectEdit($subject_id)
    {
        $subjectEdit = Subject::where('subject_id', $subject_id)->firstOrFail();

        return view('subjects.subject_edit', compact('subjectEdit'));
    }

    /** Update Record */
    public function updateRecord(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_id'   => ['required', 'string'],
            'subject_name' => ['required', 'string'],
            'class'        => ['required', 'string'],
        ]);

        try {
            $subject = Subject::where('subject_id', $validated['subject_id'])->firstOrFail();
            $subject->update($validated);

            return response()->json(['message' => 'Subject record updated successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to update Subject record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update subject record.'], 500);
        }
    }

    /** Delete Record */
    public function deleteRecord(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'string'],
        ]);

        try {
            Subject::where('subject_id', $validated['subject_id'])->firstOrFail()->delete();

            return response()->json(['message' => 'Subject record deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete Subject record', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete subject record.'], 500);
        }
    }
}
