<?php

namespace App\Services;

use App\Models\StudentActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class StudentActivityLogger
{
    public static function log(string $type, ?int $courseId = null, ?int $lessonId = null): void
    {
        if (!Auth::check()) {
            return;
        }

        StudentActivity::create([
            'user_id' => Auth::id(),
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'activity_type' => $type,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }
}