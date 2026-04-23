<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\SubmissionController as StudentSubmissionController;
use App\Http\Controllers\Teacher\CourseController as TeacherCourseController;
use App\Http\Controllers\Teacher\ModuleController as TeacherModuleController;
use App\Http\Controllers\Teacher\LessonController as TeacherLessonController;
use App\Http\Controllers\Teacher\SubmissionReviewController as TeacherSubmissionReviewController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Teacher\ActivityLogController as TeacherActivityLogController;
use App\Http\Controllers\Student\SubmissionDraftController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

//STUDENT
Route::prefix('student')
    ->middleware(['auth', 'role:student'])
    ->name('student.')
    ->group(function () {
        Route::get('/', function () {
            return view('student.dashboard');
        })->name('dashboard');
        Route::get('/grades', function () {
            $grades = \App\Models\Grade::whereHas('submission', function ($query) {
                $query->where('student_id', auth()->id());
            })
                ->with(['submission.lesson'])
                ->get();

            return view('student.grades', compact('grades'));
        })->name('grades');
        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{id}', [StudentCourseController::class, 'show'])->name('courses.show');
        Route::post('/courses/{id}/enroll', [StudentCourseController::class, 'enroll'])->name('courses.enroll');
        Route::get('/lessons/{id}', [StudentLessonController::class, 'show'])->name('lessons.show');
        Route::get('/lessons/{id}/submit', [StudentSubmissionController::class, 'create'])->name('submissions.create');
        Route::post('/lessons/{id}/submit', [StudentSubmissionController::class, 'store'])->name('submissions.store');
        Route::get('/submissions/{id}/status', [StudentSubmissionController::class, 'status'])
            ->name('submissions.status');
    });

//TEACHER
Route::prefix('teacher')
    ->middleware(['auth', 'role:teacher'])
    ->name('teacher.')
    ->group(function () {
        Route::get('/', function () {
            return view('teacher.dashboard');
        })->name('dashboard');
        Route::get('/courses', [TeacherCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [TeacherCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [TeacherCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{id}/edit', [TeacherCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{id}', [TeacherCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{id}', [TeacherCourseController::class, 'destroy'])->name('courses.destroy');
        Route::post('/courses/{id}/students', [TeacherCourseController::class, 'addStudents'])
            ->name('courses.addStudents');
        Route::delete('/courses/{courseId}/students/{studentId}', [TeacherCourseController::class, 'removeStudent'])
            ->name('courses.removeStudent');
        Route::get('/courses/{id}/modules/create', [TeacherModuleController::class, 'create'])
            ->name('modules.create');
        Route::post('/courses/{id}/modules', [TeacherModuleController::class, 'store'])
            ->name('modules.store');
        Route::get('/modules/{id}/edit', [TeacherModuleController::class, 'edit'])
            ->name('modules.edit');
        Route::put('/modules/{id}', [TeacherModuleController::class, 'update'])
            ->name('modules.update');
        Route::delete('/modules/{id}', [TeacherModuleController::class, 'destroy'])
            ->name('modules.delete');
        Route::get('/modules/{id}/lessons/create', [TeacherLessonController::class, 'create'])
            ->name('lessons.create');
        Route::post('/modules/{id}/lessons', [TeacherLessonController::class, 'store'])
            ->name('lessons.store');
        Route::get('/lessons/{id}/edit', [TeacherLessonController::class, 'edit'])
            ->name('lessons.edit');
        Route::put('/lessons/{id}', [TeacherLessonController::class, 'update'])
            ->name('lessons.update');
        Route::delete('/lessons/{id}', [TeacherLessonController::class, 'destroy'])
            ->name('lessons.destroy');
        Route::get('/submissions', [TeacherSubmissionReviewController::class, 'allSubmissions'])
            ->name('submissions.all');
        Route::get('/lessons/{id}/submissions', [TeacherSubmissionReviewController::class, 'index'])
            ->name('submissions.index');
        Route::get('/submissions/{id}', [TeacherSubmissionReviewController::class, 'show'])
            ->name('submissions.show');
        Route::post('/submissions/{id}/grade', [TeacherSubmissionReviewController::class, 'grade'])
            ->name('submissions.grade');
    });

//ADMIN
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/{id}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{id}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{id}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');
    });

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])
            ->name('admin.activity_logs');

        Route::get('/activity-logs/export', [AdminActivityLogController::class, 'export'])
            ->name('admin.activity_logs.export');
    });

    Route::middleware('role:teacher')->prefix('teacher')->group(function () {
        Route::get('/activity-logs', [TeacherActivityLogController::class, 'index'])
            ->name('teacher.activity_logs');

        Route::get('/activity-logs/export', [TeacherActivityLogController::class, 'export'])
            ->name('teacher.activity_logs.export');
    });

});

Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {

    Route::post('/drafts/{lessonId}/save', [SubmissionDraftController::class, 'save'])
        ->name('student.drafts.save');

    Route::get('/drafts/{lessonId}/load', [SubmissionDraftController::class, 'load'])
        ->name('student.drafts.load');

});