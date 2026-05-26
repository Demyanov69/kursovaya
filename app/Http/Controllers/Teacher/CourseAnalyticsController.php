<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\StudentActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CourseAnalyticsController extends Controller
{
    public function index($courseId)
    {
        $course = Course::where('author_id', Auth::id())
            ->with('students')
            ->findOrFail($courseId);

        $students = $course->students;

        $all = Cache::remember("course_{$courseId}_all", 300, function () use ($courseId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $logins = Cache::remember("course_{$courseId}_logins", 300, function () use ($courseId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('activity_type', 'login')
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $opens = Cache::remember("course_{$courseId}_opens", 300, function () use ($courseId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('activity_type', 'lesson_opened')
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $subs = Cache::remember("course_{$courseId}_subs", 300, function () use ($courseId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('activity_type', 'submission_sent')
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

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

        $all = Cache::remember("course_{$courseId}_student_{$studentId}_all", 300, function () use ($courseId, $studentId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('user_id', $studentId)
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $logins = Cache::remember("course_{$courseId}_student_{$studentId}_logins", 300, function () use ($courseId, $studentId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('user_id', $studentId)
                ->where('activity_type', 'login')
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $opens = Cache::remember("course_{$courseId}_student_{$studentId}_opens", 300, function () use ($courseId, $studentId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('user_id', $studentId)
                ->where('activity_type', 'lesson_opened')
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $subs = Cache::remember("course_{$courseId}_student_{$studentId}_subs", 300, function () use ($courseId, $studentId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('user_id', $studentId)
                ->where('activity_type', 'submission_sent')
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $heatmap = Cache::remember("course_{$courseId}_student_{$studentId}_heatmap", 300, function () use ($courseId, $studentId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, HOUR(created_at) as hour, COUNT(*) as total")
                ->where('course_id', $courseId)
                ->where('user_id', $studentId)
                ->groupBy('day', 'hour')
                ->orderBy('day')
                ->get();
        });

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