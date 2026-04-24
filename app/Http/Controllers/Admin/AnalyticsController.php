<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentActivity;
use App\Models\User;

class AnalyticsController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        $all = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.analytics.index', compact('users', 'all'));
    }

    public function user($userId)
    {
        $user = User::findOrFail($userId);

        $all = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('user_id', $userId)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $logins = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('user_id', $userId)
            ->where('activity_type', 'login')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $opens = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('user_id', $userId)
            ->where('activity_type', 'lesson_opened')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $subs = StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->where('user_id', $userId)
            ->where('activity_type', 'submission_sent')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return view('admin.analytics.user', compact('user', 'all', 'logins', 'opens', 'subs'));
    }
}