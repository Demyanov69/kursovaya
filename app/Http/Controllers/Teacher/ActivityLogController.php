<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $teacherId = Auth::id();

        $query = ActivityLog::query()
            ->with(['user', 'course'])
            ->whereHas('course', function ($q) use ($teacherId) {
                $q->where('author_id', $teacherId);
            });

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $eventTypes = ActivityLog::select('event_type')
            ->distinct()
            ->orderBy('event_type')
            ->pluck('event_type');

        return view('teacher.activity_logs', compact('logs', 'eventTypes'));
    }

    public function export(Request $request): StreamedResponse
    {
        $teacherId = Auth::id();

        $query = ActivityLog::query()
            ->with(['user', 'course'])
            ->whereHas('course', function ($q) use ($teacherId) {
                $q->where('author_id', $teacherId);
            });

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $filename = "teacher_activity_logs_" . now()->format('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['Дата', 'Тип', 'IP', 'Пользователь', 'Курс', 'Описание']);

            foreach ($query->orderByDesc('created_at')->cursor() as $log) {
                fputcsv($handle, [
                    $log->created_at,
                    $log->event_type,
                    $log->ip_address,
                    $log->user?->name,
                    $log->course?->title ?? '',
                    $log->description,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}