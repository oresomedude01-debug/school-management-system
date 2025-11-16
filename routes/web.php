<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/stats', [DashboardController::class, 'stats']);

    // Students
    Route::get('/api/students', [StudentController::class, 'index']);
    Route::post('/api/students', [StudentController::class, 'store']);
    Route::get('/api/students/{student}', [StudentController::class, 'show']);
    Route::put('/api/students/{student}', [StudentController::class, 'update']);
    Route::delete('/api/students/{student}', [StudentController::class, 'destroy']);

    // Teachers
    Route::get('/api/teachers', [TeacherController::class, 'index']);
    Route::post('/api/teachers', [TeacherController::class, 'store']);
    Route::get('/api/teachers/{teacher}', [TeacherController::class, 'show']);
    Route::put('/api/teachers/{teacher}', [TeacherController::class, 'update']);
    Route::delete('/api/teachers/{teacher}', [TeacherController::class, 'destroy']);

    // Classes
    Route::get('/api/classes', [ClassController::class, 'index']);
    Route::post('/api/classes', [ClassController::class, 'store']);
    Route::get('/api/classes/{class}', [ClassController::class, 'show']);
    Route::put('/api/classes/{class}', [ClassController::class, 'update']);
    Route::delete('/api/classes/{class}', [ClassController::class, 'destroy']);

    // Subjects
    Route::get('/api/subjects', [SubjectController::class, 'index']);
    Route::post('/api/subjects', [SubjectController::class, 'store']);
    Route::get('/api/subjects/{subject}', [SubjectController::class, 'show']);
    Route::put('/api/subjects/{subject}', [SubjectController::class, 'update']);
    Route::delete('/api/subjects/{subject}', [SubjectController::class, 'destroy']);

    // Attendance
    Route::get('/api/attendance', [AttendanceController::class, 'index']);
    Route::post('/api/attendance', [AttendanceController::class, 'store']);
    Route::get('/api/attendance/{attendance}', [AttendanceController::class, 'show']);
    Route::put('/api/attendance/{attendance}', [AttendanceController::class, 'update']);

    // Grades
    Route::get('/api/grades', [GradeController::class, 'index']);
    Route::post('/api/grades', [GradeController::class, 'store']);
    Route::get('/api/grades/{grade}', [GradeController::class, 'show']);
    Route::put('/api/grades/{grade}', [GradeController::class, 'update']);
    Route::delete('/api/grades/{grade}', [GradeController::class, 'destroy']);
});
