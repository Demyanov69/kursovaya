<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query()->with(['user', 'course']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

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

        $users = User::orderBy('name')->get();
        $eventTypes = ActivityLog::select('event_type')->distinct()->orderBy('event_type')->pluck('event_type');

        return view('admin.activity_logs', compact('logs', 'users', 'eventTypes'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = ActivityLog::query()->with(['user', 'course']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $filename = "activity_logs_" . now()->format('Y-m-d_H-i') . ".csv";

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Дата', 'Тип', 'IP', 'Пользователь', 'Роль', 'Курс', 'Описание']);

            foreach ($query->orderByDesc('created_at')->cursor() as $log) {
                fputcsv($handle, [
                    $log->created_at,
                    $log->event_type,
                    $log->ip_address,
                    $log->user?->name,
                    $log->user?->role?->name ?? '',
                    $log->course?->title ?? '',
                    $log->description,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}