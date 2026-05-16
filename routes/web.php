<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LikeController;


Route::put('/tps/{id}/submit', [StudentController::class, 'updateSubmission'])->name('student.tps.update-submission');
Route::put('/posts/{id}', [FeedController::class, 'update'])->name('posts.update');
Route::delete('/profile/picture', [ProfileController::class, 'deletePicture'])
    ->name('profile.delete-picture')
    ->middleware('auth');
Route::get('/users/search', [ProfileController::class, 'search'])->middleware('auth');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/setup-password/{token}', [AuthController::class, 'showPasswordSetup'])
    ->name('password.setup');
Route::post('/setup-password', [AuthController::class, 'setupPassword'])
    ->name('password.setup.submit');

Route::middleware('auth')->group(function () {
    // Post detail & comments
Route::get('/posts/{id}', [FeedController::class, 'show'])->name('posts.show');
Route::post('/posts/{id}/comments', [FeedController::class, 'storeComment'])->name('posts.comments.store');
Route::delete('/comments/{id}', [FeedController::class, 'destroyComment'])->name('comments.destroy');
Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
Route::post('/comments/{comment}/like', [LikeController::class, 'toggleComment'])->name('comments.like');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update-info');
    Route::put('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::post('/profile/request-email-change', [ProfileController::class, 'requestEmailChange'])->name('profile.request-email-change');
    Route::post('/profile/confirm-email-change', [ProfileController::class, 'confirmEmailChange'])->name('profile.confirm-email-change');

    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::post('/posts', [FeedController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{id}', [FeedController::class, 'destroy'])->name('posts.destroy');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notification-settings', [NotificationController::class, 'settings'])->name('notification-settings');
    Route::post('/notification-settings', [NotificationController::class, 'updateSettings'])->name('notification-settings.update');

    // Student routes
    Route::prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/join-course', [StudentController::class, 'showJoinCourse'])->name('join-course.form');
        Route::post('/join-course', [StudentController::class, 'joinCourse'])->name('join-course');
        Route::get('/my-courses', [StudentController::class, 'myCourses'])->name('my-courses');
        Route::delete('/courses/{courseId}/leave', [StudentController::class, 'leaveCourse'])->name('leave-course');
        Route::get('/courses/{courseId}', [StudentController::class, 'showCourse'])->name('courses.show');
        Route::get('/tps/{id}', [StudentController::class, 'showTP'])->name('tps.show');
        Route::post('/tps/{id}/submit', [StudentController::class, 'submitTP'])->name('tps.submit');
        Route::put('/tps/{id}/submit', [StudentController::class, 'updateSubmission'])->name('tps.update-submission');
        Route::get('/submissions', [StudentController::class, 'mySubmissions'])->name('submissions.index');
        Route::get('/progress', [StudentController::class, 'myProgress'])->name('progress');
    });

    // Teacher routes
    Route::prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [TeacherController::class, 'myCourses'])->name('courses.index');
        Route::get('/courses/create', [TeacherController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [TeacherController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{id}', [TeacherController::class, 'showCourse'])->name('courses.show');
        Route::get('/courses/{id}/edit', [TeacherController::class, 'editCourse'])->name('courses.edit');
        Route::put('/courses/{id}', [TeacherController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{id}', [TeacherController::class, 'deleteCourse'])->name('courses.destroy');
        Route::post('/courses/{id}/regenerate-code', [TeacherController::class, 'regenerateCode'])->name('courses.regenerate-code');
        Route::delete('/courses/{courseId}/students/{studentId}', [TeacherController::class, 'removeStudent'])->name('courses.remove-student');
        Route::get('/courses/{courseId}/tps/create', [TeacherController::class, 'createTPForCourse'])->name('courses.tps.create');
        Route::post('/courses/{courseId}/tps', [TeacherController::class, 'storeTPForCourse'])->name('courses.tps.store');
        Route::get('/tps/{id}', [TeacherController::class, 'showTP'])->name('tps.show');
        Route::get('/tps/{id}/edit', [TeacherController::class, 'editTP'])->name('tps.edit');
        Route::put('/tps/{id}', [TeacherController::class, 'updateTP'])->name('tps.update');
        Route::delete('/tps/{id}', [TeacherController::class, 'destroyTP'])->name('tps.destroy');
        Route::get('/tps/{tpId}/submissions/{submissionId}', [TeacherController::class, 'showSubmission'])->name('submissions.show');
        Route::post('/tps/{tpId}/submissions/{submissionId}/grade', [TeacherController::class, 'gradeSubmission'])->name('submissions.grade');
        Route::get('/progress', [TeacherController::class, 'studentProgress'])->name('progress.index');
        Route::get('/progress/student/{studentId}', [TeacherController::class, 'showStudentProgress'])->name('progress.show');
        Route::get('/attendance', [TeacherController::class, 'attendanceIndex'])->name('attendance.index');
        Route::get('/attendance/take', [TeacherController::class, 'attendanceShow'])->name('attendance.show');
        Route::post('/attendance/save', [TeacherController::class, 'attendanceSave'])->name('attendance.save');
        Route::get('/statistics', [TeacherController::class, 'statistics'])->name('statistics');
    });

});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
    Route::get('/classes', [ClassController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClassController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClassController::class, 'store'])->name('classes.store');
    Route::get('/classes/{id}', [ClassController::class, 'show'])->name('classes.show');
    Route::get('/classes/{id}/edit', [ClassController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{id}', [ClassController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/reset', [SettingController::class, 'reset'])->name('settings.reset');
    Route::get('/statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('/system-logs', [AdminController::class, 'systemLogs'])->name('system-logs');
});

Route::get('/', function () {
    return redirect()->route('login');
});
//forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');