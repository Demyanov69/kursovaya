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

        $user = Auth::user();
        $accessAllowed = true;
        $conditions = [];

        // Условие по времени (available_from)
        if ($lesson->available_from && now()->lt($lesson->available_from)) {
            $accessAllowed = false;
            $conditions[] = "Урок будет доступен с: " . $lesson->available_from;
        }

        // Условие по другому уроку
        if ($lesson->required_lesson_id) {

            $requiredLesson = Lesson::with('module.course')->find($lesson->required_lesson_id);

            $submission = \App\Models\Submission::where('lesson_id', $lesson->required_lesson_id)
                ->where('student_id', $user->id)
                ->with('grade')
                ->first();

            if (!$submission) {
                $accessAllowed = false;
                $conditions[] = "Необходимо выполнить урок: " . ($requiredLesson->title ?? "ID=" . $lesson->required_lesson_id);
            } else {
                // если требуется минимальная оценка
                if ($lesson->required_min_score) {
                    $score = $submission->grade?->score;

                    if (!$score || $score < $lesson->required_min_score) {
                        $accessAllowed = false;
                        $conditions[] = "Необходимо получить оценку не ниже " . $lesson->required_min_score .
                            " за урок: " . ($requiredLesson->title ?? "");
                    }
                }
            }
        }

        if (!$accessAllowed) {
            \App\Services\ActivityLogger::log(
                'lesson_access_denied',
                'Студент пытался открыть заблокированный урок: ' . $lesson->title,
                $course->id
            );
        }
        \App\Services\StudentActivityLogger::log(
            'lesson_opened',
            $course->id,
            $lesson->id
        );

        // Используем существующий шаблон lesson.blade.php
        return view('student.lesson', compact('lesson', 'accessAllowed', 'conditions'));
    }
}