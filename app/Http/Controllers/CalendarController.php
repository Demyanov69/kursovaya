<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Course;
use App\Models\Lesson;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        // 🎯 Курсы по роли
        if ($user->isAdmin()) {
            $courseIds = Course::pluck('id');
        } elseif ($user->isTeacher()) {
            $courseIds = Course::where('author_id', $user->id)->pluck('id');
        } else {
            $courseIds = $user->courses()->pluck('courses.id');
        }

        $lessons = Lesson::whereHas('module.course', function ($q) use ($courseIds) {
                $q->whereIn('id', $courseIds);
            })
            ->with('module.course')
            ->get();

        $events = [];
        $now = now();

        foreach ($lessons as $lesson) {

            // 📘 Открытие урока
            if ($lesson->available_from && ($filter === 'all' || $filter === 'available')) {

                $date = Carbon::parse($lesson->available_from);

                $events[] = [
                    'title' => '📘',
                    'start' => $lesson->available_from,
                    'classNames' => [
                        $date->lt($now) ? 'event-past' : 'event-available'
                    ],
                    'extendedProps' => [
                        'type' => 'available',
                        'lesson_id' => $lesson->id,
                        'course' => $lesson->module->course->title ?? '',
                        'full_title' => $lesson->title
                    ]
                ];
            }

            // ⏰ Дедлайн
            if ($lesson->deadline && ($filter === 'all' || $filter === 'deadline')) {

                $date = Carbon::parse($lesson->deadline);

                $events[] = [
                    'title' => '⏰',
                    'start' => $lesson->deadline,
                    'classNames' => [
                        $date->lt($now) ? 'event-overdue' : 'event-deadline'
                    ],
                    'extendedProps' => [
                        'type' => 'deadline',
                        'lesson_id' => $lesson->id,
                        'course' => $lesson->module->course->title ?? '',
                        'full_title' => $lesson->title
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    // 🔥 Drag & Drop обновление дедлайна
    public function updateDate(Request $request)
    {
        $lesson = Lesson::findOrFail($request->lesson_id);

        if (Auth::user()->isTeacher()) {
            if ($lesson->module->course->author_id !== Auth::id()) {
                abort(403);
            }
        }

        if ($request->type === 'deadline') {
            $lesson->deadline = $request->date;
        } else {
            $lesson->available_from = $request->date;
        }

        $lesson->save();

        return response()->json(['success' => true]);
    }
}