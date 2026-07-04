<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Attendance::class);
    }

    /**
     * Show attendance marking form.
     */
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $currentSession = AcademicSession::current()->first();
        $currentTerm = Term::current()->first();

        $students = collect();
        $attendanceRecords = collect();
        $selectedDate = $request->get('date', now()->format('Y-m-d'));

        if ($request->filled('school_class_id')) {
            $students = User::students()
                ->whereHas('studentProfile', function ($q) use ($request) {
                    $q->where('school_class_id', $request->school_class_id);
                })
                ->with('studentProfile')
                ->orderBy('first_name')
                ->get();

            // Get existing attendance for this date
            $attendanceRecords = Attendance::where('school_class_id', $request->school_class_id)
                ->where('date', $selectedDate)
                ->pluck('status', 'student_id');
        }

        return view('attendance.index', compact(
            'classes', 'students', 'attendanceRecords',
            'selectedDate', 'currentSession', 'currentTerm'
        ));
    }

    /**
     * Store or update attendance for a class on a date.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
            'term_id' => ['required', 'exists:terms,id'],
            'date' => ['required', 'date'],
            'attendance' => ['required', 'array'],
            'attendance.*.student_id' => ['required', 'exists:users,id'],
            'attendance.*.status' => ['required', 'in:present,absent,late,excused'],
            'attendance.*.remark' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['attendance'] as $record) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'date' => $validated['date'],
                    ],
                    [
                        'school_class_id' => $validated['school_class_id'],
                        'academic_session_id' => $validated['academic_session_id'],
                        'term_id' => $validated['term_id'],
                        'status' => $record['status'],
                        'remark' => $record['remark'] ?? null,
                        'marked_by' => auth()->id(),
                    ]
                );
            }

            DB::commit();

            return response()->json(['message' => 'Attendance saved successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save attendance', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Failed to save attendance.'], 500);
        }
    }

    /**
     * View attendance report for a class.
     */
    public function report(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $sessions = AcademicSession::orderByDesc('id')->get();

        $attendanceData = collect();
        $students = collect();
        $dates = collect();

        if ($request->filled(['school_class_id', 'term_id'])) {
            $students = User::students()
                ->whereHas('studentProfile', function ($q) use ($request) {
                    $q->where('school_class_id', $request->school_class_id);
                })
                ->with('studentProfile')
                ->orderBy('first_name')
                ->get();

            $attendanceData = Attendance::where('school_class_id', $request->school_class_id)
                ->where('term_id', $request->term_id)
                ->get()
                ->groupBy('student_id');

            $dates = Attendance::where('school_class_id', $request->school_class_id)
                ->where('term_id', $request->term_id)
                ->distinct('date')
                ->orderBy('date')
                ->pluck('date');
        }

        $terms = Term::orderBy('name')->get();

        return view('attendance.report', compact('classes', 'sessions', 'terms', 'students', 'attendanceData', 'dates'));
    }
}
