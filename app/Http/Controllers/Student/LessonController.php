<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function modules($courseId)
    {
        $course = Course::with('modules.lessons')->findOrFail($courseId);

        if (!Auth::user()->courses->contains($course->id)) {
            abort(403, 'Недостаточно прав.');
        }

        return view('student.modules.index', compact('course'));
    }

    public function show($id)
    {
        $lesson = Lesson::with('module.course')->findOrFail($id);

        $course = $lesson->module->course;

        if (!Auth::user()->courses->contains($course->id)) {
            abort(403, 'У вас нет доступа к этому уроку.');
        }

        // Используем существующий шаблон lesson.blade.php
        return view('student.lesson', compact('lesson'));
    }
}