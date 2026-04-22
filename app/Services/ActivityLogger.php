<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $eventType, ?string $description = null, ?int $courseId = null): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'event_type' => $eventType,
            'ip_address' => request()->ip(),
            'description' => $description,
            'course_id' => $courseId,
        ]);
    }
}