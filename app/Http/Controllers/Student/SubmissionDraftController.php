<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\SubmissionDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionDraftController extends Controller
{
    // автосохранение черновика
    public function save(Request $request, $lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;

        if (!Auth::user()->courses->contains($course->id)) {
            abort(403);
        }

        $request->validate([
            'text_answer' => 'nullable|string'
        ]);

        SubmissionDraft::updateOrCreate(
            [
                'lesson_id' => $lesson->id,
                'student_id' => Auth::id(),
            ],
            [
                'text_answer' => $request->text_answer,
            ]
        );

        return response()->json([
            'success' => true,
            'saved_at' => now()->format('H:i:s')
        ]);
    }

    // восстановление черновика
    public function load($lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;

        if (!Auth::user()->courses->contains($course->id)) {
            abort(403);
        }

        $draft = SubmissionDraft::where('lesson_id', $lesson->id)
            ->where('student_id', Auth::id())
            ->first();

        return response()->json([
            'exists' => $draft ? true : false,
            'text_answer' => $draft?->text_answer,
            'updated_at' => $draft?->updated_at?->format('d.m.Y H:i:s')
        ]);
    }

    // удалить черновик (после отправки)
    public static function deleteDraft($lessonId): void
    {
        SubmissionDraft::where('lesson_id', $lessonId)
            ->where('student_id', Auth::id())
            ->delete();
    }
}