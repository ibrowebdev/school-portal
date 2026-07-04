<?php

use App\Http\Controllers\AcademicSessionController;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GradeSettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Guest / Auth Landing ───────────────────────────────────
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('home')
        : redirect()->route('login');
});

Auth::routes();

// ─── Authentication ─────────────────────────────────────────
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'login')->name('login')->middleware('guest');
    Route::post('/login', 'authenticate')->middleware('guest');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::controller(RegisterController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'storeUser');
});

// ─── Authenticated Routes ───────────────────────────────────
Route::middleware('auth')->group(function () {

    // 1. DASHBOARD & PROFILE
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('user/profile/page', 'userProfile')->name('user/profile/page');
        Route::get('teacher/dashboard', 'teacherDashboardIndex')->name('teacher/dashboard');
        Route::get('student/dashboard', 'studentDashboardIndex')->name('student/dashboard');
    });

    // ─── SUPER-ADMIN ONLY ROUTES ────────────────────────────────
    Route::middleware(['role:super-admin'])->group(function () {
        Route::get('roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
    });

    // ─── ADMIN & SUPER-ADMIN ONLY ROUTES ────────────────────────
    Route::middleware(['role:super-admin|admin'])->group(function () {
        // Billing & Accounts
        Route::get('manage-class-fees', function () {
            return view('accounts.manage-class-fees');
        })->name('manage-class-fees');
        Route::get('record-student-payment', function () {
            return view('accounts.record-student-payment');
        })->name('record-student-payment');

        // Settings
        Route::controller(SettingController::class)->group(function () {
            Route::get('setting/page', 'index')->name('setting/page');
            Route::post('setting/update', 'update')->name('setting.update');
        });

        // 2. USER MANAGEMENT
        Route::post('change/password', [UserController::class, 'changePassword'])->name('change/password');
        Route::get('get-users-data', [UserController::class, 'getUsersData'])->name('get-users-data');
        Route::resource('users', UserController::class);

        // 5. DEPARTMENTS
        Route::get('department/get-data-list', [DepartmentController::class, 'getDataList'])->name('departments.data-list');
        Route::resource('departments', DepartmentController::class)->parameters(['departments' => 'id']);

        // 6. SUBJECTS
        Route::get('subjects/register', function () {
            return view('academic.subjects.register');
        })->name('subjects.register');
        Route::resource('subjects', SubjectController::class)->parameters(['subjects' => 'id']);

        // 7. INVOICES
        Route::prefix('invoice')->controller(InvoiceController::class)->group(function () {
            Route::get('paid/page', 'invoicePaid')->name('invoices.paid');
            Route::get('overdue/page', 'invoiceOverdue')->name('invoices.overdue');
            Route::get('draft/page', 'invoiceDraft')->name('invoices.draft');
            Route::get('recurring/page', 'invoiceRecurring')->name('invoices.recurring');
            Route::get('cancelled/page', 'invoiceCancelled')->name('invoices.cancelled');
            Route::get('grid/page', 'invoiceGrid')->name('invoices.grid');
            Route::get('settings/page', 'invoiceSettings')->name('invoices.settings');
            Route::get('settings/tax/page', 'invoiceSettingsTax')->name('invoices.settings-tax');
            Route::get('settings/bank/page', 'invoiceSettingsBank')->name('invoices.settings-bank');
        });
        Route::resource('invoices', InvoiceController::class)->parameters(['invoices' => 'id']);

        // 8. ACCOUNTS / FEES
        Route::get('account/fees/collections/page', [AccountsController::class, 'index'])->name('fees.index');
        Route::get('add/fees/collection/page', [AccountsController::class, 'create'])->name('fees.create');
        Route::post('fees/collection/save', [AccountsController::class, 'store'])->name('fees.store');

        // ─── 9. ACADEMIC MANAGEMENT ─────────────────────────────
        Route::resource('academic-sessions', AcademicSessionController::class);
        Route::resource('academic-sessions.terms', TermController::class)->shallow();

        // 10. SCHOOL CLASSES
        Route::post('school-classes/{schoolClass}/assign-teachers', [SchoolClassController::class, 'assignTeachers'])
            ->name('school-classes.assign-teachers');
        Route::resource('school-classes', SchoolClassController::class);

        // ─── 12. GRADE SETTINGS ─────────────────────────────────
        Route::resource('grade-settings', GradeSettingController::class)->except(['show']);
    });

    // ─── TEACHER, ADMIN & SUPER-ADMIN ROUTES ────────────────────
    Route::middleware(['role:super-admin|admin|teacher'])->group(function () {
        // 3. STUDENTS
        Route::get('student/grid', [StudentController::class, 'studentGrid'])->name('students.grid');
        Route::get('students/class-sections/{schoolClass}', [StudentController::class, 'getClassSections'])->name('students.class-sections');
        Route::resource('students', StudentController::class)->parameters(['students' => 'id']);

        // 4. TEACHERS
        Route::get('teacher/grid/page', [TeacherController::class, 'teacherGrid'])->name('teachers.grid');
        Route::resource('teachers', TeacherController::class)->parameters(['teachers' => 'id']);
    });

    // ─── 11. RESULTS ────────────────────────────────────────
    Route::middleware(['permission:upload-results'])->group(function () {
        Route::get('results/upload', [ResultController::class, 'create'])->name('results.upload');
        Route::post('results/upload', [ResultController::class, 'store'])->name('results.store');
    });

    Route::middleware(['permission:view-results'])->group(function () {
        Route::get('results', [ResultController::class, 'index'])->name('results.index');
        Route::get('results/{student}/report', [ResultController::class, 'studentReport'])->name('results.report');
        Route::get('results/{student}/report/pdf', [ResultController::class, 'exportPdf'])->name('results.report.pdf');
    });

    // AJAX helpers for results (Available to any authenticated user who might need them)
    Route::get('api/class-subjects/{schoolClass}', [ResultController::class, 'getClassSubjects'])->name('api.class-subjects');
    Route::get('api/session-terms/{academicSession}', [ResultController::class, 'getSessionTerms'])->name('api.session-terms');

    // ─── 13. ATTENDANCE ─────────────────────────────────────
    Route::middleware(['permission:mark-attendance'])->group(function () {
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    });

    Route::middleware(['permission:view-attendance-report'])->group(function () {
        Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    });

    // ─── 14. PARENT PORTAL ──────────────────────────────────
    Route::prefix('parent')->middleware('permission:view-children')->group(function () {
        Route::get('dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
        Route::get('children/{child}', [ParentController::class, 'childProfile'])->name('parent.child-profile');
        Route::get('children/{child}/results', [ParentController::class, 'childResults'])->name('parent.child-results');
    });
});
