<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserManagementController;
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

    Route::get('setting/page', [SettingController::class, 'index'])->name('setting/page');

    // 2. USER MANAGEMENT
    Route::post('change/password', [UserManagementController::class, 'changePassword'])->name('change/password');
    Route::get('get-users-data', [UserManagementController::class, 'getUsersData'])->name('get-users-data');
    Route::resource('users', UserManagementController::class)->parameters(['users' => 'id']);

    // 3. STUDENTS
    Route::get('student/grid', [StudentController::class, 'studentGrid'])->name('students.grid');
    Route::resource('students', StudentController::class)->parameters(['students' => 'id']);

    // 4. TEACHERS
    Route::get('teacher/grid/page', [TeacherController::class, 'teacherGrid'])->name('teachers.grid');
    Route::resource('teachers', TeacherController::class)->parameters(['teachers' => 'id']);

    // 5. DEPARTMENTS
    Route::get('department/get-data-list', [DepartmentController::class, 'getDataList'])->name('departments.data-list');
    Route::resource('departments', DepartmentController::class)->parameters(['departments' => 'id']);

    // 6. SUBJECTS
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
});