<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\Attendance;
use App\Models\Result;
use App\Models\StudentProfile;
use App\Models\Term;
use App\Models\User;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Parent dashboard — overview of linked children.
     */
    public function dashboard()
    {
        $parent = auth()->user();

        $children = StudentProfile::with(['user', 'schoolClass', 'classSection'])
            ->where('parent_id', $parent->id)
            ->get();

        return view('parent.dashboard', compact('children'));
    }

    /**
     * View a specific child's profile.
     */
    public function childProfile(User $child)
    {
        $this->authorizeParentAccess($child);

        $child->load(['studentProfile.schoolClass', 'studentProfile.classSection']);

        // Attendance summary for current term
        $currentTerm = Term::current()->first();
        $attendanceSummary = [];

        if ($currentTerm) {
            $attendanceSummary = [
                'total' => Attendance::where('student_id', $child->id)->where('term_id', $currentTerm->id)->count(),
                'present' => Attendance::where('student_id', $child->id)->where('term_id', $currentTerm->id)->present()->count(),
                'absent' => Attendance::where('student_id', $child->id)->where('term_id', $currentTerm->id)->absent()->count(),
            ];
        }

        return view('parent.child-profile', compact('child', 'attendanceSummary'));
    }

    /**
     * View a specific child's results.
     */
    public function childResults(Request $request, User $child)
    {
        $this->authorizeParentAccess($child);

        $sessions = AcademicSession::orderByDesc('id')->get();
        $currentSession = AcademicSession::current()->first();
        $currentTerm = Term::current()->first();

        $sessionId = $request->get('academic_session_id', $currentSession?->id);
        $termId = $request->get('term_id', $currentTerm?->id);

        $results = Result::with(['subject', 'schoolClass', 'term', 'academicSession'])
            ->where('student_id', $child->id)
            ->where('academic_session_id', $sessionId)
            ->where('term_id', $termId)
            ->orderBy('subject_id')
            ->get();

        $terms = $sessionId
            ? Term::where('academic_session_id', $sessionId)->get()
            : collect();

        $child->load('studentProfile.schoolClass');

        return view('parent.child-results', compact('child', 'results', 'sessions', 'terms', 'sessionId', 'termId'));
    }

    /**
     * Ensure the authenticated parent is the actual parent of this child.
     */
    private function authorizeParentAccess(User $child): void
    {
        $parent = auth()->user();

        $isLinked = StudentProfile::where('user_id', $child->id)
            ->where('parent_id', $parent->id)
            ->exists();

        abort_unless($isLinked, 403, 'You are not authorized to view this student\'s information.');
    }
}
