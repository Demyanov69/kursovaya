<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Submission;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 120, function () {
            return [
                'totalUsers' => User::count(),
                'totalStudents' => User::whereHas('role', fn($q) => $q->where('name', 'student'))->count(),
                'totalTeachers' => User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->count(),
                'totalCourses' => Course::count(),
                'totalEnrollments' => \DB::table('course_user')->count(),
                'activity' => Submission::count(),
            ];
        });

        return view('admin.dashboard', $stats);
    }
}
