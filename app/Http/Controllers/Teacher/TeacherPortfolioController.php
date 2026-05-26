<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class TeacherPortfolioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Список курсов преподавателя
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $courses = Course::where('author_id', Auth::id())
            ->withCount('students')
            ->orderBy('title')
            ->get();

        return view('teacher.portfolio.index', compact('courses'));
    }

    /*
    |--------------------------------------------------------------------------
    | Студенты курса + портфолио
    |--------------------------------------------------------------------------
    */

    public function course($courseId)
    {
        $course = Course::where('author_id', Auth::id())
            ->with([
                'students.portfolio'
            ])
            ->findOrFail($courseId);

        return view('teacher.portfolio.course', compact('course'));
    }
}