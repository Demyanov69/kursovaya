<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Glossary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlossaryController extends Controller
{
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->author_id !== Auth::id()) {
            abort(403);
        }

        $terms = Glossary::with([
            'comments.user',
            'ratings',
            'author'
        ])
            ->where('course_id', $courseId)
            ->orderBy('term')
            ->get();

        return view('teacher.glossary.index', compact('course', 'terms'));
    }

    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->author_id !== Auth::id()) {
            abort(403);
        }

        return view('teacher.glossary.form', compact('course'));
    }

    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        if ($course->author_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'term' => 'required|string|max:255',
            'short_definition' => 'required|string|max:255',
            'full_definition' => 'required|string'
        ]);

        Glossary::create([
            'course_id' => $course->id,
            'term' => $data['term'],
            'short_definition' => $data['short_definition'],
            'full_definition' => $data['full_definition'],
            'created_by' => Auth::id(),
            'status' => 'approved'
        ]);

        return redirect()
            ->route('teacher.glossary.index', $courseId)
            ->with('success', 'Термин успешно добавлен.');
    }

    public function edit($id)
    {
        $term = Glossary::findOrFail($id);

        $course = Course::findOrFail($term->course_id);

        if ($course->author_id !== Auth::id()) {
            abort(403);
        }

        return view('teacher.glossary.form', compact('term', 'course'));
    }

    public function update(Request $request, $id)
    {
        $term = Glossary::findOrFail($id);

        $course = Course::findOrFail($term->course_id);

        if ($course->author_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'term' => 'required|string|max:255',
            'short_definition' => 'required|string|max:255',
            'full_definition' => 'required|string'
        ]);

        $term->update([
            'term' => $data['term'],
            'short_definition' => $data['short_definition'],
            'full_definition' => $data['full_definition']
        ]);

        return redirect()
            ->route('teacher.glossary.index', $course->id)
            ->with('success', 'Термин успешно обновлён.');
    }

    public function destroy($id)
    {
        $term = Glossary::findOrFail($id);

        $course = Course::findOrFail($term->course_id);

        if ($course->author_id !== Auth::id()) {
            abort(403);
        }

        $term->comments()->delete();
        $term->ratings()->delete();

        $term->delete();

        return back()->with('success', 'Термин успешно удалён.');
    }
}