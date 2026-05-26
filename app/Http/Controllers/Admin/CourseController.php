<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Cache;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Cache::remember('admin_courses', 120, function () {
            return Course::with(['category', 'author'])
                ->withCount('lessons')
                ->get();
        });

        return view('admin.courses', compact('courses'));
    }
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $categories = Category::all();
        $teachers = User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->get();
        return view('admin.courses', compact('course', 'categories', 'teachers'));
    }
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'author_id' => 'nullable|exists:users,id',
        ]);
        $course->update($request->only([
            'title',
            'description',
            'category_id',
            'author_id',
            'faculty',
            'direction',
            'course_year'
        ]));

        Cache::forget('admin_courses');

        ActivityLogger::log(
            'course_updated_admin',
            'Администратор обновил курс: ' . $course->title,
            $course->id
        );
        return redirect()->route('admin.courses.index')
            ->with('success', 'Курс обновлён.');
    }
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        ActivityLogger::log(
            'course_deleted_admin',
            'Администратор удалил курс: ' . $course->title,
            $course->id
        );

        $course->delete();

        Cache::forget('admin_courses');
        return back()->with('success', 'Курс удалён.');
    }
}
