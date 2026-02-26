<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    public function create($moduleId)
    {
        $module = Module::with('course')->findOrFail($moduleId);
        if ($module->course->author_id !== Auth::id()) {
            abort(403);
        }
        return view('teacher.lesson_create', compact('module'));
    }
    public function store(Request $request, $moduleId)
    {
        $module = Module::with('course')->findOrFail($moduleId);
        if ($module->course->author_id !== Auth::id()) {
            abort(403);
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'assignment_file' => 'nullable|file|max:10240',
            'available_from' => 'nullable|date',
            'deadline' => 'nullable|date',
            'late_penalty_percent' => 'nullable|integer|min:0|max:100',
        ]);
        if ($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $name = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assignments', $name, 'public');
            $data['assignment_file'] = 'assignments/' . $name;
        }
        $data['module_id'] = $module->id;
        $data['late_penalty_percent'] = $data['late_penalty_percent'] ?? 0;
        Lesson::create($data);
        return redirect()
            ->route('teacher.courses.edit', $module->course_id)
            ->with('success', 'Урок создан.');
    }
    public function edit($lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        if ($lesson->module->course->author_id !== Auth::id()) {
            abort(403);
        }
        return view('teacher.lesson_create', compact('lesson'));
    }
    public function update(Request $request, $lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        if ($lesson->module->course->author_id !== Auth::id()) {
            abort(403);
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'assignment_file' => 'nullable|file|max:10240',
            'available_from' => 'nullable|date',
            'deadline' => 'nullable|date',
            'late_penalty_percent' => 'nullable|integer|min:0|max:100',
        ]);
        if ($request->hasFile('assignment_file')) {
            if ($lesson->assignment_file) {
                Storage::disk('public')->delete($lesson->assignment_file);
            }
            $file = $request->file('assignment_file');
            $name = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('assignments', $name, 'public'); // исправлено
            $data['assignment_file'] = 'assignments/' . $name;
        }

        $data['late_penalty_percent'] = $data['late_penalty_percent'] ?? $lesson->late_penalty_percent;
        $lesson->update($data);
        return redirect()
            ->route('teacher.courses.edit', $lesson->module->course_id)
            ->with('success', 'Урок обновлён.');
    }
    public function destroy($lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        if ($lesson->module->course->author_id !== Auth::id()) {
            abort(403);
        }
        if ($lesson->assignment_file) {
            Storage::disk('public')->delete('assignments/' . $lesson->assignment_file);
        }
        $lesson->delete();
        return back()->with('success', 'Урок удалён.');
    }
}
