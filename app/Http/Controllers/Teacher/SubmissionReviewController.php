<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class SubmissionReviewController extends Controller
{
    public function allSubmissions()
    {
        $submissions = Submission::whereHas('lesson.module.course', function ($query) {
            $query->where('author_id', Auth::id());
        })
            ->with(['student', 'lesson.module.course'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('teacher.submissions', compact('submissions'));
    }
    public function index($lessonId)
    {
        $lesson = Lesson::with('module.course')->findOrFail($lessonId);
        if ($lesson->module->course->author_id !== Auth::id()) {
            abort(403);
        }
        $submissions = Submission::where('lesson_id', $lessonId)
            ->with(['student', 'lesson.module.course'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.submissions', compact('submissions'));
    }
    public function show($id)
    {
        $submission = Submission::with(['student', 'lesson.module.course'])
            ->findOrFail($id);
        if ($submission->lesson->module->course->author_id !== Auth::id()) {
            abort(403);
        }
        return view('teacher.submission_review', compact('submission'));
    }
    public function grade(Request $request, $id)
    {
        $submission = Submission::with('lesson.module.course')->findOrFail($id);
        if ($submission->lesson->module->course->author_id !== Auth::id()) {
            abort(403);
        }
        $request->validate([
            'score' => 'required|integer|min:1|max:100',
            'comment' => 'nullable|string',
        ]);
        $grade = Grade::create([
            'submission_id' => $submission->id,
            'grader_id' => Auth::id(),
            'score' => $request->score,
            'comment' => $request->comment,
        ]);
        $submission->update(['status' => 'graded']);
        $student = $submission->student;

        if ($student) {
            $student->notify(new \App\Notifications\GradeReceivedNotification($submission));
        }
        ActivityLogger::log(
            'submission_checked',
            'Преподаватель оценил работу студента ID=' . $submission->student_id .
            ' по уроку: ' . $submission->lesson->title .
            ' (оценка: ' . $grade->score . ')',
            $submission->lesson->module->course_id
        );
        return redirect()->route('teacher.submissions.all')
            ->with('success', 'Работа оценена.');
    }
}