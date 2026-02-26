<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Submission;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers       = User::count();
        $totalStudents    = User::whereHas('role', fn($q) => $q->where('name', 'student'))->count();
        $totalTeachers    = User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->count();
        $totalCourses     = Course::count();
        $totalEnrollments = \DB::table('course_user')->count();

        $activity = Submission::count(); // очень базовая метрика активности

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'totalEnrollments',
            'activity'
        ));
    }
}
