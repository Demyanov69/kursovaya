<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Portfolio;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Course;
use App\Models\Submission;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'title' => 'Моё портфолио',
                'visibility' => 'draft',
                'slug' => Str::uuid()
            ]
        );

        $this->importCompletedCourses($portfolio);

        $portfolio->load('items');

        return view('student.portfolio.index', compact('portfolio'));
    }

    public function update(Request $request)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->firstOrFail();

        $portfolio->update([
            'title' => $request->title,
            'description' => $request->description,
            'visibility' => $request->visibility,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Портфолио обновлено');
    }

    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)
            ->with(['items', 'user.courses'])
            ->firstOrFail();

        // draft запрещён
        if ($portfolio->visibility === 'draft') {
            abort(403);
        }

        $viewer = Auth::user();

        // если не авторизован
        if (!$viewer) {

            // только public
            if ($portfolio->visibility !== 'public') {
                abort(403);
            }

            return view('student.portfolio.public', compact('portfolio'));
        }

        // ADMIN
        if ($viewer->isAdmin()) {
            return view('student.portfolio.public', compact('portfolio'));
        }

        // OWNER
        if ($viewer->id === $portfolio->user_id) {
            return view('student.portfolio.public', compact('portfolio'));
        }

        // TEACHER
        if ($viewer->isTeacher()) {

            $teacherCourseIds = Course::where('author_id', $viewer->id)
                ->pluck('id');

            $studentCourseIds = $portfolio->user->courses
                ->pluck('id');

            $hasAccess = $teacherCourseIds
                ->intersect($studentCourseIds)
                ->count() > 0;

            if (!$hasAccess) {
                abort(403);
            }

            return view('student.portfolio.public', compact('portfolio'));
        }

        // STUDENT
        if ($portfolio->visibility !== 'public') {
            abort(403);
        }

        return view('student.portfolio.public', compact('portfolio'));
    }

    // PDF EXPORT
    public function downloadPdf()
    {
        $portfolio = Portfolio::where('user_id', Auth::id())
            ->with(['items', 'user'])
            ->firstOrFail();

        $pdf = Pdf::loadView('student.portfolio.pdf', compact('portfolio'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('portfolio.pdf');
    }

    private function importCompletedCourses($portfolio)
    {
        $user = Auth::user();

        $courses = $user->courses()->with('modules.lessons')->get();

        foreach ($courses as $course) {

            $lessonIds = [];

            foreach ($course->modules as $module) {
                foreach ($module->lessons as $lesson) {
                    $lessonIds[] = $lesson->id;
                }
            }

            // если уроков нет
            if (count($lessonIds) === 0) {
                continue;
            }

            $completedLessons = Submission::where('student_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->count();

            // курс завершён
            if ($completedLessons >= count($lessonIds)) {

                $exists = $portfolio->items()
                    ->where('type', 'course')
                    ->where('title', $course->title)
                    ->exists();

                if (!$exists) {

                    $portfolio->items()->create([
                        'type' => 'course',
                        'title' => $course->title,
                        'description' => 'Завершённый курс',
                        'url' => null,
                    ]);
                }
            }
        }
    }
}