<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentActivity;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function index()
    {
        $users = Cache::remember('analytics_users', 300, function () {
            return User::orderBy('name')->get();
        });

        $all = Cache::remember('analytics_all_days', 300, function () {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        return view('admin.analytics.index', compact('users', 'all'));
    }

    public function user($userId)
    {
        $user = User::findOrFail($userId);

        $all = Cache::remember("analytics_user_{$userId}_all", 300, function () use ($userId) {
            return StudentActivity::selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->where('user_id', $userId)
                ->groupBy('day')
                ->orderBy('day')
                ->get();
        });

        $logins = Cache::remember("analytics_user_{$userId}_logins", 300, function () use ($userId) {
            return StudentActivity::where('user_id', $userId)
                ->where('activity_type', 'login')
                ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->groupBy('day')
                ->get();
        });

        $opens = Cache::remember("analytics_user_{$userId}_opens", 300, function () use ($userId) {
            return StudentActivity::where('user_id', $userId)
                ->where('activity_type', 'lesson_opened')
                ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->groupBy('day')
                ->get();
        });

        $subs = Cache::remember("analytics_user_{$userId}_subs", 300, function () use ($userId) {
            return StudentActivity::where('user_id', $userId)
                ->where('activity_type', 'submission_sent')
                ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
                ->groupBy('day')
                ->get();
        });

        return view('admin.analytics.user', compact('user', 'all', 'logins', 'opens', 'subs'));
    }
}