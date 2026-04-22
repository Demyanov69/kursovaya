<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class ModuleController extends Controller
{
    // Создание модуля
    public function store(Request $request, $courseId)
    {
        $course = Course::where('author_id', Auth::id())->findOrFail($courseId);

        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        // Позиция модуля
        $position = $course->modules()->max('position') + 1;

        $module = Module::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'position' => $position,
        ]);

        ActivityLogger::log(
            'module_created',
            'Преподаватель создал модуль: ' . $module->title,
            $course->id
        );

        return back()->with('success', 'Модуль добавлен.');
    }

    // Редактирование модуля
    public function update(Request $request, $moduleId)
    {
        $module = Module::with('course')->findOrFail($moduleId);

        if ($module->course->author_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $module->update([
            'title' => $request->title,
        ]);
        ActivityLogger::log(
            'module_updated',
            'Преподаватель обновил модуль: ' . $module->title,
            $module->course_id
        );

        return back()->with('success', 'Модуль обновлён.');
    }

    public function create($courseId)
    {
        $course = Course::where('author_id', Auth::id())->findOrFail($courseId);
        return view('teacher.module_create', compact('course'));
    }

    public function edit($moduleId)
    {
        $module = Module::with('course')->findOrFail($moduleId);

        if ($module->course->author_id !== Auth::id()) {
            abort(403);
        }

        return view('teacher.module_edit', compact('module'));
    }


    // Удаление модуля
    public function destroy($moduleId)
    {
        $module = Module::with('course')->findOrFail($moduleId);

        if ($module->course->author_id !== Auth::id()) {
            abort(403);
        }

        ActivityLogger::log(
            'module_deleted',
            'Преподаватель удалил модуль: ' . $module->title,
            $module->course_id
        );
        $module->delete();

        return back()->with('success', 'Модуль удалён.');
    }
}
