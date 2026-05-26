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
use App\Http\Controllers\Teacher\LessonTemplateController;
use App\Http\Controllers\Teacher\CourseAnalyticsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Admin\UserImportExportController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\Student\PortfolioItemController;
use App\Http\Controllers\Student\GlossaryController as StudentGlossaryController;
use App\Http\Controllers\Teacher\GlossaryController as TeacherGlossaryController;
use App\Http\Controllers\Admin\GlossaryController as AdminGlossaryController;

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
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'show'])
        ->name('password.change');

    Route::post('/change-password', [ChangePasswordController::class, 'update'])
        ->name('password.update');
});

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
        Route::get('/courses/{courseId}/glossary', [StudentGlossaryController::class, 'index'])
            ->name('glossary.index');
        Route::get('/glossary/{id}', [StudentGlossaryController::class, 'show'])
            ->name('glossary.show');
        Route::post('/glossary/{id}/rate', [StudentGlossaryController::class, 'rate'])->name('glossary.rate');
        Route::post('/glossary/{id}/comment', [StudentGlossaryController::class, 'comment'])->name('glossary.comment');
        Route::get(
            '/glossary/term/{id}',
            [StudentGlossaryController::class, 'show']
        )->name('glossary.show');
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
        // ПОРТФОЛИО СТУДЕНТОВ
        Route::get('/portfolio', [\App\Http\Controllers\Teacher\TeacherPortfolioController::class, 'index'])
            ->name('portfolio.index');
        Route::get('/portfolio/course/{courseId}', [\App\Http\Controllers\Teacher\TeacherPortfolioController::class, 'course'])
            ->name('portfolio.course');
        Route::get(
            '/courses/{courseId}/glossary',
            [TeacherGlossaryController::class, 'index']
        )
            ->name('glossary.index');

        Route::get(
            '/courses/{courseId}/glossary/create',
            [TeacherGlossaryController::class, 'create']
        )
            ->name('glossary.create');

        Route::post(
            '/courses/{courseId}/glossary',
            [TeacherGlossaryController::class, 'store']
        )
            ->name('glossary.store');

        Route::get(
            '/glossary/{id}/edit',
            [TeacherGlossaryController::class, 'edit']
        )
            ->name('glossary.edit');

        Route::put(
            '/glossary/{id}',
            [TeacherGlossaryController::class, 'update']
        )
            ->name('glossary.update');

        Route::delete(
            '/glossary/{id}',
            [TeacherGlossaryController::class, 'destroy']
        )
            ->name('glossary.destroy');
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
        Route::get('/users/import', function () {
            return view('admin.users.import');
        })->name('users.import');
        Route::post('/users/import', [UserImportExportController::class, 'import'])
            ->name('users.import.post');
        Route::get('/users/export', [UserImportExportController::class, 'export'])
            ->name('users.export');
        Route::get('/glossary', [AdminGlossaryController::class, 'index'])
            ->name('glossary.index');

        Route::get('/glossary/create', [AdminGlossaryController::class, 'create'])
            ->name('glossary.create');

        Route::post('/glossary', [AdminGlossaryController::class, 'store'])
            ->name('glossary.store');

        Route::get('/glossary/{id}', [AdminGlossaryController::class, 'show'])
            ->name('glossary.show');

        Route::get('/glossary/{id}/edit', [AdminGlossaryController::class, 'edit'])
            ->name('glossary.edit');

        Route::put('/glossary/{id}', [AdminGlossaryController::class, 'update'])
            ->name('glossary.update');

        Route::delete('/glossary/{id}', [AdminGlossaryController::class, 'destroy'])
            ->name('glossary.destroy');
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
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {

    Route::get('/lesson-templates', [LessonTemplateController::class, 'index'])
        ->name('teacher.lesson_templates.index');

    Route::post('/lesson-templates', [LessonTemplateController::class, 'store'])
        ->name('teacher.lesson_templates.store');

    Route::get('/lesson-templates/{id}', [LessonTemplateController::class, 'show'])
        ->name('teacher.lesson_templates.show');

});

Route::middleware(['auth'])->group(function () {

    Route::prefix('teacher')->group(function () {
        Route::get('/courses/{courseId}/analytics', [CourseAnalyticsController::class, 'index'])
            ->name('teacher.course.analytics');

        Route::get('/courses/{courseId}/analytics/student/{studentId}', [CourseAnalyticsController::class, 'student'])
            ->name('teacher.course.analytics.student');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/analytics', [AnalyticsController::class, 'index'])
            ->name('admin.analytics.index');

        Route::get('/analytics/user/{userId}', [AnalyticsController::class, 'user'])
            ->name('admin.analytics.user');
    });

});

// КАЛЕНДАРЬ
Route::middleware(['auth'])->group(function () {

    Route::get('/calendar', [CalendarController::class, 'index'])
        ->name('calendar');

    Route::get('/calendar/events', [CalendarController::class, 'events'])
        ->name('calendar.events');

    // 🔥 drag & drop обновление
    Route::post('/calendar/update-date', [CalendarController::class, 'updateDate'])
        ->name('calendar.update');
});

// PORTFOLIO
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        Route::get('/portfolio', [PortfolioController::class, 'index'])
            ->name('portfolio.index');

        Route::post('/portfolio/update', [PortfolioController::class, 'update'])
            ->name('portfolio.update');

        Route::post('/portfolio/items', [PortfolioItemController::class, 'store'])
            ->name('portfolio.items.store');

        Route::get('/portfolio/items/{id}/edit', [PortfolioItemController::class, 'edit'])
            ->name('portfolio.items.edit');

        Route::put('/portfolio/items/{id}', [PortfolioItemController::class, 'update'])
            ->name('portfolio.items.update');

        Route::delete('/portfolio/items/{id}', [PortfolioItemController::class, 'destroy'])
            ->name('portfolio.items.delete');

        // PDF EXPORT
        Route::get('/portfolio/pdf', [PortfolioController::class, 'downloadPdf'])
            ->name('portfolio.pdf');
    });

// Публичное портфолио
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])
    ->name('portfolio.public');