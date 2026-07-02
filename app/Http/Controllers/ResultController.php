<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResultRequest;
use App\Models\AcademicSession;
use App\Models\Result;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    /**
     * View results with filters.
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sessions = AcademicSession::orderByDesc('id')->get();
        $terms = Term::orderBy('name')->get();

        $results = collect();

        if ($request->filled(['school_class_id', 'subject_id', 'term_id', 'academic_session_id'])) {
            $results = Result::with(['student', 'subject', 'schoolClass', 'term', 'academicSession'])
                ->where('school_class_id', $request->school_class_id)
                ->where('subject_id', $request->subject_id)
                ->where('term_id', $request->term_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->orderBy('total_score', 'desc')
                ->get();
        }

        // Get subjects for the selected class
        $subjects = collect();
        if ($request->filled('school_class_id')) {
            $class = SchoolClass::find($request->school_class_id);
            $subjects = $class ? $class->subjects : collect();
        }

        return view('results.index', compact('classes', 'sessions', 'terms', 'results', 'subjects'));
    }

    /**
     * Show the result upload form.
     */
    public function create(Request $request)
    {
        $classes = SchoolClass::with('subjects')->orderBy('name')->get();
        $sessions = AcademicSession::orderByDesc('id')->get();
        $terms = Term::orderBy('name')->get();

        $students = collect();
        $subjects = collect();
        $existingResults = collect();

        if ($request->filled(['school_class_id', 'subject_id', 'term_id', 'academic_session_id'])) {
            // Get students in the selected class
            $students = User::students()
                ->whereHas('studentProfile', function ($q) use ($request) {
                    $q->where('school_class_id', $request->school_class_id);
                })
                ->with('studentProfile')
                ->orderBy('first_name')
                ->get();

            // Get existing results for pre-filling
            $existingResults = Result::where('school_class_id', $request->school_class_id)
                ->where('subject_id', $request->subject_id)
                ->where('term_id', $request->term_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->pluck('ca_score', 'student_id')
                ->toArray();

            $existingExamScores = Result::where('school_class_id', $request->school_class_id)
                ->where('subject_id', $request->subject_id)
                ->where('term_id', $request->term_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->pluck('exam_score', 'student_id')
                ->toArray();

            $existingResults = Result::where('school_class_id', $request->school_class_id)
                ->where('subject_id', $request->subject_id)
                ->where('term_id', $request->term_id)
                ->where('academic_session_id', $request->academic_session_id)
                ->get()
                ->keyBy('student_id');

            // Get subjects mapped to selected class
            $class = SchoolClass::find($request->school_class_id);
            $subjects = $class ? $class->subjects : collect();
        }

        return view('results.upload', compact(
            'classes', 'sessions', 'terms', 'students',
            'subjects', 'existingResults'
        ));
    }

    /**
     * Store bulk results.
     */
    public function store(StoreResultRequest $request): JsonResponse
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            foreach ($validated['results'] as $resultData) {
                Result::updateOrCreate(
                    [
                        'student_id' => $resultData['student_id'],
                        'subject_id' => $validated['subject_id'],
                        'term_id' => $validated['term_id'],
                        'academic_session_id' => $validated['academic_session_id'],
                    ],
                    [
                        'school_class_id' => $validated['school_class_id'],
                        'ca_score' => $resultData['ca_score'],
                        'exam_score' => $resultData['exam_score'],
                        'uploaded_by' => auth()->id(),
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Results uploaded successfully!',
                'redirect' => route('results.index', [
                    'school_class_id' => $validated['school_class_id'],
                    'subject_id' => $validated['subject_id'],
                    'term_id' => $validated['term_id'],
                    'academic_session_id' => $validated['academic_session_id'],
                ]),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to upload results', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to upload results.'], 500);
        }
    }

    /**
     * Student report card for a term.
     */
    public function studentReport(Request $request, User $student)
    {
        $sessions = AcademicSession::orderByDesc('id')->get();
        $currentSession = AcademicSession::current()->first();
        $currentTerm = Term::current()->first();

        $sessionId = $request->get('academic_session_id', $currentSession?->id);
        $termId = $request->get('term_id', $currentTerm?->id);

        $results = Result::with(['subject', 'schoolClass', 'term', 'academicSession'])
            ->where('student_id', $student->id)
            ->where('academic_session_id', $sessionId)
            ->where('term_id', $termId)
            ->orderBy('subject_id')
            ->get();

        $student->load('studentProfile.schoolClass', 'studentProfile.classSection');

        $terms = $sessionId
            ? Term::where('academic_session_id', $sessionId)->get()
            : collect();

        return view('results.report-card', compact('student', 'results', 'sessions', 'terms', 'sessionId', 'termId'));
    }

    /**
     * Export report card as PDF.
     */
    public function exportPdf(Request $request, User $student)
    {
        $sessionId = $request->get('academic_session_id');
        $termId = $request->get('term_id');

        $results = Result::with(['subject', 'schoolClass', 'term', 'academicSession'])
            ->where('student_id', $student->id)
            ->where('academic_session_id', $sessionId)
            ->where('term_id', $termId)
            ->orderBy('subject_id')
            ->get();

        $student->load('studentProfile.schoolClass', 'studentProfile.classSection');
        $session = AcademicSession::find($sessionId);
        $term = Term::find($termId);

        $html = view('results.report-card-pdf', compact('student', 'results', 'session', 'term'))->render();

        $mpdf = new \Mpdf\Mpdf([
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        $mpdf->WriteHTML($html);

        return $mpdf->Output(
            'Report_Card_' . $student->name . '_' . ($term?->name ?? '') . '.pdf',
            \Mpdf\Output\Destination::INLINE
        );
    }

    /**
     * Get subjects for a class (AJAX endpoint).
     */
    public function getClassSubjects(SchoolClass $schoolClass): JsonResponse
    {
        return response()->json($schoolClass->subjects);
    }

    /**
     * Get terms for a session (AJAX endpoint).
     */
    public function getSessionTerms(AcademicSession $academicSession): JsonResponse
    {
        return response()->json($academicSession->terms);
    }
}
