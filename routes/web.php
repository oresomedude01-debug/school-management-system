<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\PublicEnrollmentController;
use App\Http\Controllers\RegistrationTokenController;
use App\Http\Controllers\StudentManagementController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Enrollment Routes
Route::get('/enrollment', [PublicEnrollmentController::class, 'index'])->name('enrollment.index');
Route::post('/enrollment/validate-token', [PublicEnrollmentController::class, 'validateToken'])->name('enrollment.validate');
Route::post('/enrollment/submit', [PublicEnrollmentController::class, 'submit'])->name('enrollment.submit');
Route::post('/enrollment/check-status', [PublicEnrollmentController::class, 'checkStatus'])->name('enrollment.status');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'stats']);

    // Page Routes (render views with layout)
    Route::get('/students', function () { return view('students.index'); })->name('students');
    Route::get('/teachers', function () { return view('teachers.index'); })->name('teachers');
    Route::get('/classes', function () { return view('classes.index'); })->name('classes');
    Route::get('/subjects', function () { return view('subjects.index'); })->name('subjects');
    Route::get('/attendance', function () { return view('attendance.index'); })->name('attendance');
    Route::get('/grades', function () { return view('grades.index'); })->name('grades');

    // Students API
    Route::get('/api/students', [StudentController::class, 'index']);
    Route::post('/api/students', [StudentController::class, 'store']);
    Route::get('/api/students/{student}', [StudentController::class, 'show']);
    Route::put('/api/students/{student}', [StudentController::class, 'update']);
    Route::delete('/api/students/{student}', [StudentController::class, 'destroy']);

    // Teachers API
    Route::get('/api/teachers', [TeacherController::class, 'index']);
    Route::post('/api/teachers', [TeacherController::class, 'store']);
    Route::get('/api/teachers/{teacher}', [TeacherController::class, 'show']);
    Route::put('/api/teachers/{teacher}', [TeacherController::class, 'update']);
    Route::delete('/api/teachers/{teacher}', [TeacherController::class, 'destroy']);

    // Classes API
    Route::get('/api/classes', [ClassController::class, 'index']);
    Route::post('/api/classes', [ClassController::class, 'store']);
    Route::get('/api/classes/{class}', [ClassController::class, 'show']);
    Route::put('/api/classes/{class}', [ClassController::class, 'update']);
    Route::delete('/api/classes/{class}', [ClassController::class, 'destroy']);

    // Subjects API
    Route::get('/api/subjects', [SubjectController::class, 'index']);
    Route::post('/api/subjects', [SubjectController::class, 'store']);
    Route::get('/api/subjects/{subject}', [SubjectController::class, 'show']);
    Route::put('/api/subjects/{subject}', [SubjectController::class, 'update']);
    Route::delete('/api/subjects/{subject}', [SubjectController::class, 'destroy']);

    // Attendance API
    Route::get('/api/attendance', [AttendanceController::class, 'index']);
    Route::post('/api/attendance', [AttendanceController::class, 'store']);
    Route::get('/api/attendance/{attendance}', [AttendanceController::class, 'show']);
    Route::put('/api/attendance/{attendance}', [AttendanceController::class, 'update']);

    // Grades API
    Route::get('/api/grades', [GradeController::class, 'index']);
    Route::post('/api/grades', [GradeController::class, 'store']);
    Route::get('/api/grades/{grade}', [GradeController::class, 'show']);
    Route::put('/api/grades/{grade}', [GradeController::class, 'update']);
    Route::delete('/api/grades/{grade}', [GradeController::class, 'destroy']);

    // Registration Token Management (Admin only)
    Route::get('/registration-tokens', function () { return view('admin.tokens.index'); })->name('tokens.index');
    Route::get('/api/registration-tokens', [RegistrationTokenController::class, 'index']);
    Route::post('/api/registration-tokens', [RegistrationTokenController::class, 'store']);
    Route::post('/api/registration-tokens/bulk', [RegistrationTokenController::class, 'bulkGenerate']);
    Route::get('/api/registration-tokens/statistics', [RegistrationTokenController::class, 'getStatistics']);
    Route::get('/api/registration-tokens/export', [RegistrationTokenController::class, 'export']);
    Route::get('/api/registration-tokens/{id}', [RegistrationTokenController::class, 'show']);
    Route::put('/api/registration-tokens/{id}/status', [RegistrationTokenController::class, 'updateStatus']);
    Route::delete('/api/registration-tokens/{id}', [RegistrationTokenController::class, 'destroy']);

    // Student Management (Enhanced CRUD)
    Route::get('/student-management', function () { return view('admin.students.index'); })->name('student-management');
    Route::get('/api/student-management', [StudentManagementController::class, 'index']);
    Route::post('/api/student-management', [StudentManagementController::class, 'store']);
    Route::get('/api/student-management/statistics', [StudentManagementController::class, 'getStatistics']);
    Route::get('/api/student-management/{id}', [StudentManagementController::class, 'show']);
    Route::put('/api/student-management/{id}', [StudentManagementController::class, 'update']);
    Route::delete('/api/student-management/{id}', [StudentManagementController::class, 'destroy']);

    // Localization
    Route::get('/api/translations/{locale}', [LocalizationController::class, 'getTranslations']);
    Route::post('/api/locale', [LocalizationController::class, 'setLocale']);
    Route::get('/api/locale', [LocalizationController::class, 'getLocale']);
});
