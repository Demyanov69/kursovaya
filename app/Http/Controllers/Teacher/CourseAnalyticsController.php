<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\StudentActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CourseAnalyticsController extends Controller
{
    public function index($courseId)
    {
        $course = Course::where('author_id', Auth::id())
            ->with('students')
            ->findOrFail($courseId);

        $students = $course->students;

        // Общая активность
        $all = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Входы
        $logins = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('activity_type', 'login')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Открытие уроков
        $opens = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('activity_type', 'lesson_opened')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Отправка заданий
        $subs = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('activity_type', 'submission_sent')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('teacher.course_analytics.index', compact(
            'course',
            'students',
            'all',
            'logins',
            'opens',
            'subs'
        ));
    }

    public function student($courseId, $studentId)
    {
        $course = Course::where('author_id', Auth::id())
            ->with('students')
            ->findOrFail($courseId);

        $student = User::findOrFail($studentId);

        if (!$course->students->contains($student->id)) {
            abort(403);
        }

        $all = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('user_id', $studentId)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $logins = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('user_id', $studentId)
            ->where('activity_type', 'login')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $opens = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('user_id', $studentId)
            ->where('activity_type', 'lesson_opened')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $subs = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('user_id', $studentId)
            ->where('activity_type', 'submission_sent')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $heatmap = StudentActivity::selectRaw("DATE(created_at) as day, HOUR(created_at) as hour, COUNT(*) as total")
            ->where('course_id', $courseId)
            ->where('user_id', $studentId)
            ->groupBy('day', 'hour')
            ->orderBy('day')
            ->get();

        return view('teacher.course_analytics.student', compact(
            'course',
            'student',
            'all',
            'logins',
            'opens',
            'subs',
            'heatmap'
        ));
    }
}