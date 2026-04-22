<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('author_id', Auth::id())->get();
        return view('teacher.courses', compact('courses'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('teacher.course_edit', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'faculty' => 'nullable|string',
            'direction' => 'nullable|string',
            'course_year' => 'nullable|integer|min:1|max:5',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'faculty' => $request->faculty,
            'direction' => $request->direction,
            'course_year' => $request->course_year,
            'category_id' => $request->category_id,
            'author_id' => Auth::id(),
        ]);

        ActivityLogger::log(
            'course_created',
            'Преподаватель создал курс: ' . $course->title,
            $course->id
        );
        return redirect()->route('teacher.courses.index')
            ->with('success', 'Курс создан.');
    }

    public function edit($id)
    {
        $course = Course::where('author_id', Auth::id())
            ->with(['modules.lessons', 'students'])
            ->findOrFail($id);
        $categories = \App\Models\Category::all();
        $allStudents = User::whereHas('role', function ($query) {
            $query->where('name', 'student');
        })->get();
        return view('teacher.course_edit', compact('course', 'categories', 'allStudents'));
    }
    public function update(Request $request, $id)
    {
        $course = Course::where('author_id', Auth::id())->findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        $course->update($request->only(['title', 'description', 'category_id']));
        ActivityLogger::log(
            'course_updated',
            'Преподаватель обновил курс: ' . $course->title,
            $course->id
        );
        return redirect()->route('teacher.courses.index')
            ->with('success', 'Данные курса обновлены.');
    }
    public function destroy($id)
    {
        $course = Course::where('author_id', Auth::id())->findOrFail($id);
        ActivityLogger::log(
            'course_deleted',
            'Преподаватель удалил курс: ' . $course->title,
            $course->id
        );
        $course->delete();
        return back()->with('success', 'Курс удалён.');
    }
    public function addStudents(Request $request, $courseId)
    {
        $course = Course::where('author_id', Auth::id())->findOrFail($courseId);
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id'
        ]);
        $course->students()->syncWithoutDetaching($request->student_ids);
        ActivityLogger::log(
            'students_added_to_course',
            'Преподаватель добавил студентов на курс: ' . $course->title,
            $course->id
        );
        return back()->with('success', 'Студенты добавлены на курс');
    }

    public function removeStudent($courseId, $studentId)
    {
        $course = Course::where('author_id', Auth::id())->findOrFail($courseId);
        $course->students()->detach($studentId);
        ActivityLogger::log(
            'student_removed_from_course',
            'Преподаватель удалил студента ID=' . $studentId . ' с курса: ' . $course->title,
            $course->id
        );
        return back()->with('success', 'Студент удален с курса');
    }
}