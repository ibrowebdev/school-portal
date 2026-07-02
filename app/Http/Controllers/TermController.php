<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TermController extends Controller
{
    public function index(AcademicSession $academicSession)
    {
        $terms = $academicSession->terms()->orderBy('start_date')->get();

        return view('academic.terms.index', compact('academicSession', 'terms'));
    }

    public function create(AcademicSession $academicSession)
    {
        return view('academic.terms.create', compact('academicSession'));
    }

    public function store(Request $request, AcademicSession $academicSession): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        try {
            $term = $academicSession->terms()->create($validated);

            if ($request->boolean('is_current')) {
                $term->markAsCurrent();
            }

            return response()->json([
                'message' => 'Term created successfully!',
                'redirect' => route('academic-sessions.terms.index', $academicSession),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create term', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create term.'], 500);
        }
    }

    public function edit(Term $term)
    {
        $term->load('academicSession');

        return view('academic.terms.edit', compact('term'));
    }

    public function update(Request $request, Term $term): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        try {
            $term->update($validated);

            if ($request->boolean('is_current')) {
                $term->markAsCurrent();
            }

            return response()->json([
                'message' => 'Term updated successfully!',
                'redirect' => route('academic-sessions.terms.index', $term->academic_session_id),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update term', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update term.'], 500);
        }
    }

    public function destroy(Term $term): JsonResponse
    {
        try {
            $sessionId = $term->academic_session_id;
            $term->delete();

            return response()->json([
                'message' => 'Term deleted successfully!',
                'redirect' => route('academic-sessions.terms.index', $sessionId),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to delete term', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete term.'], 500);
        }
    }
}
