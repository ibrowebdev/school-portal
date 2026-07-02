<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AcademicSessionController extends Controller
{
    public function index()
    {
        $sessions = AcademicSession::withCount('terms')
            ->orderByDesc('id')
            ->get();

        return view('academic.sessions.index', compact('sessions'));
    }

    public function create()
    {
        return view('academic.sessions.create');
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:academic_sessions,name'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        try {
            $session = AcademicSession::create($validated);

            if ($request->boolean('is_current')) {
                $session->markAsCurrent();
            }

            return response()->json([
                'message' => 'Academic session created successfully!',
                'redirect' => route('academic-sessions.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create academic session', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to create academic session.'], 500);
        }
    }

    public function edit(AcademicSession $academicSession)
    {
        return view('academic.sessions.edit', compact('academicSession'));
    }

    public function update(Request $request, AcademicSession $academicSession): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:academic_sessions,name,' . $academicSession->id],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        try {
            $academicSession->update($validated);

            if ($request->boolean('is_current')) {
                $academicSession->markAsCurrent();
            }

            return response()->json([
                'message' => 'Academic session updated successfully!',
                'redirect' => route('academic-sessions.index'),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update academic session', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to update academic session.'], 500);
        }
    }

    public function destroy(AcademicSession $academicSession): JsonResponse
    {
        try {
            $academicSession->delete();

            return response()->json(['message' => 'Academic session deleted successfully!']);
        } catch (\Exception $e) {
            Log::error('Failed to delete academic session', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to delete academic session.'], 500);
        }
    }

    public function show(AcademicSession $academicSession)
    {
        $academicSession->load('terms');

        return view('academic.sessions.show', compact('academicSession'));
    }
}
