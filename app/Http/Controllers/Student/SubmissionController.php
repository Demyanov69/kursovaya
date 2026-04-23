<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;
use App\Models\SubmissionDraft;

class SubmissionController extends Controller
{
    public function create($lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;
        if (!Auth::user()->courses->contains($course->id)) {
            abort(403);
        }
        $existing = Submission::where('lesson_id', $lessonId)
            ->where('student_id', Auth::id())
            ->first();
        if ($existing) {
            return redirect()->route('student.submissions.status', $lessonId);
        }
        return view('student.submit', compact('lesson'));
    }


    public function store(Request $request, $lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        $course = $lesson->module->course;
        if (!Auth::user()->courses->contains($course->id)) {
            abort(403);
        }
        $request->validate([
            'text_answer' => 'nullable|string',
            'file_answer' => 'nullable|file|max:10240',
        ]);
        $filePath = null;
        if ($request->hasFile('file_answer')) {
            $file = $request->file('file_answer');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('submissions', $name, 'public');

            $filePath = 'submissions/' . $name;
        }

        $submission = Submission::create([
            'lesson_id' => $lesson->id,
            'student_id' => Auth::id(),
            'text_answer' => $request->text_answer,
            'file_answer' => $filePath,
            'status' => 'awaiting_review',
            'submitted_at' => now(),
        ]);

        SubmissionDraft::where('lesson_id', $lesson->id)
            ->where('student_id', Auth::id())
            ->delete();

        ActivityLogger::log(
            'submission_sent',
            'Студент отправил работу по уроку: ' . $lesson->title,
            $course->id
        );
        return redirect()
            ->route('student.submissions.status', $lesson->id)
            ->with('success', 'Работа успешно отправлена!');
    }

    public function status($lessonId)
    {
        $submission = Submission::where('lesson_id', $lessonId)
            ->where('student_id', Auth::id())
            ->with('grade')
            ->firstOrFail();
        return view('student.submission_status', compact('submission'));
    }

}
