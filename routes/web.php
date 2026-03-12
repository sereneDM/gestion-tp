<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SettingController;

// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    
    // Student routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('dashboard');
    
    // Course management
    Route::get('/join-course', [StudentController::class, 'showJoinCourse'])
        ->name('join-course.form');
    Route::post('/join-course', [StudentController::class, 'joinCourse'])
        ->name('join-course');
    Route::get('/my-courses', [StudentController::class, 'myCourses'])
        ->name('my-courses');
    Route::delete('/courses/{courseId}/leave', [StudentController::class, 'leaveCourse'])
        ->name('leave-course');
    
    // TP viewing and submission
    Route::get('/tps', [StudentController::class, 'indexTPs'])
        ->name('tps.index');
    Route::get('/tps/{id}', [StudentController::class, 'showTP'])
        ->name('tps.show');
    Route::post('/tps/{id}/submit', [StudentController::class, 'submitTP'])
        ->name('tps.submit');
    
    // My submissions
    Route::get('/submissions', [StudentController::class, 'mySubmissions'])
        ->name('submissions.index');
    
    // My progress
    Route::get('/progress', [StudentController::class, 'myProgress'])
        ->name('progress');
});


    // Teacher routes
Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])
        ->name('dashboard');
    
    // Course management (NEW!)
    Route::get('/courses', [TeacherController::class, 'myCourses'])
        ->name('courses.index');
    Route::get('/courses/create', [TeacherController::class, 'createCourse'])
        ->name('courses.create');
    Route::post('/courses', [TeacherController::class, 'storeCourse'])
        ->name('courses.store');
    Route::get('/courses/{id}', [TeacherController::class, 'showCourse'])
        ->name('courses.show');
    Route::get('/courses/{id}/edit', [TeacherController::class, 'editCourse'])
        ->name('courses.edit');
    Route::put('/courses/{id}', [TeacherController::class, 'updateCourse'])
        ->name('courses.update');
    Route::delete('/courses/{id}', [TeacherController::class, 'deleteCourse'])
        ->name('courses.destroy');
    Route::post('/courses/{id}/regenerate-code', [TeacherController::class, 'regenerateCode'])
        ->name('courses.regenerate-code');
    Route::delete('/courses/{courseId}/students/{studentId}', [TeacherController::class, 'removeStudent'])
        ->name('courses.remove-student');
    
    // TP management
    Route::get('/tps', [TeacherController::class, 'indexTPs'])
        ->name('tps.index');
    Route::get('/tps/create', [TeacherController::class, 'createTP'])
        ->name('tps.create');
    Route::post('/tps', [TeacherController::class, 'storeTP'])
        ->name('tps.store');
    Route::get('/tps/{id}', [TeacherController::class, 'showTP'])
        ->name('tps.show');
    Route::get('/tps/{id}/edit', [TeacherController::class, 'editTP'])
        ->name('tps.edit');
    Route::put('/tps/{id}', [TeacherController::class, 'updateTP'])
        ->name('tps.update');
    Route::delete('/tps/{id}', [TeacherController::class, 'destroyTP'])
        ->name('tps.destroy');
    
    // Submission grading
    Route::get('/tps/{tpId}/submissions/{submissionId}', [TeacherController::class, 'showSubmission'])
        ->name('submissions.show');
    Route::post('/tps/{tpId}/submissions/{submissionId}/grade', [TeacherController::class, 'gradeSubmission'])
        ->name('submissions.grade');
    
    // Student progress tracking
    Route::get('/progress', [TeacherController::class, 'studentProgress'])
        ->name('progress.index');
    Route::get('/progress/student/{studentId}', [TeacherController::class, 'showStudentProgress'])
        ->name('progress.show');
    
    // Attendance management
    Route::get('/attendance', [TeacherController::class, 'attendanceIndex'])
        ->name('attendance.index');
    Route::get('/attendance/take', [TeacherController::class, 'attendanceShow'])
        ->name('attendance.show');
    Route::post('/attendance/save', [TeacherController::class, 'attendanceSave'])
        ->name('attendance.save');
    
    // Statistics
    Route::get('/statistics', [TeacherController::class, 'statistics'])
        ->name('statistics');
});

    // Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');
    
    // User management
    Route::get('/users', [AdminController::class, 'index'])
        ->name('users.index');
    Route::get('/users/create', [AdminController::class, 'create'])
        ->name('users.create');
    Route::post('/users', [AdminController::class, 'store'])
        ->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])
        ->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])
        ->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])
        ->name('users.destroy');
    
    // Quick role update
    Route::put('/users/{id}/role', [AdminController::class, 'updateRole'])
        ->name('users.update-role');

    // Class supervision (view-only + delete)
    Route::get('/classes', [ClassController::class, 'index'])
        ->name('classes.index');
    Route::get('/classes/{id}', [ClassController::class, 'show'])
        ->name('classes.show');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])
        ->name('classes.destroy');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])
        ->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])
        ->name('settings.update');
    Route::post('/settings/reset', [SettingController::class, 'reset'])
        ->name('settings.reset');
    
    // Statistics
    Route::get('/statistics', [AdminController::class, 'statistics'])
        ->name('statistics');
    
    // System Logs
    Route::get('/system-logs', [AdminController::class, 'systemLogs'])
        ->name('system-logs');
});
});

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});