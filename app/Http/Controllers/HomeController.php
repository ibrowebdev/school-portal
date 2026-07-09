<?php

namespace App\Http\Controllers;

use App\Models\AcademicSession;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    /** home dashboard */
    public function index()
    {
        $user = auth()->user();

        // Redirect based on user type
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        if ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        }

        if ($user->isParent()) {
            return redirect()->route('parent.dashboard');
        }

        // Admin / Super Admin dashboard
        $stats = [
            'total_students' => User::students()->count(),
            'total_teachers' => User::teachers()->count(),
            'total_parents' => User::parents()->count(),
            'total_classes' => SchoolClass::count(),
            'current_session' => AcademicSession::current()->first(),
        ];

        return view('dashboard.home', compact('stats'));
    }

    /** profile user */
    public function userProfile()
    {
        return view('dashboard.profile');
    }

    /** teacher dashboard */
    public function teacherDashboardIndex()
    {
        $user = auth()->user();
        $assignedClasses = $user->assignedClasses()
            ->withPivot(['subject_id', 'academic_session_id'])
            ->get();

        return view('dashboard.teacher_dashboard', compact('assignedClasses'));
    }

    /** student dashboard */
    public function studentDashboardIndex()
    {
        $user = auth()->user();
        $user->load('studentProfile.schoolClass', 'studentProfile.classSection');

        $recentResults = $user->results()
            ->with(['subject', 'term', 'academicSession'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard.student_dashboard', compact('recentResults'));
    }

    /** parent dashboard */
    public function parentDashboardIndex()
    {
        return app(ParentController::class)->dashboard();
    }
}
