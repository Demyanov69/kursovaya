<?php

namespace App\Services;

use App\Models\StudentActivity;
use Illuminate\Support\Facades\Auth;

class StudentActivityLogger
{
    public static function log(string $type, ?int $courseId = null, ?int $lessonId = null): void
    {
        StudentActivity::create([
            'user_id' => Auth::id(),
            'course_id' => $courseId,
            'lesson_id' => $lessonId,
            'activity_type' => $type,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}