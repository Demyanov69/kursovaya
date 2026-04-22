<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')->get();

        return view('student.courses', compact('courses'));
    }

    public function show($id)
    {
        $course = Course::with('modules.lessons')->findOrFail($id);

        return view('student.course', compact('course'));
    }

    public function enroll($id)
    {
        $course = Course::findOrFail($id);
        $user = Auth::user();

        if (!$user->courses->contains($course->id)) {
            $user->courses()->attach($course->id);

            ActivityLogger::log(
                'course_enroll',
                'Студент записался на курс: ' . $course->title,
                $course->id
            );
        }

        return redirect()->back()->with('success', 'Вы успешно записались на курс.');
    }
}
