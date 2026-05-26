<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Glossary;
use App\Models\GlossaryRating;
use App\Models\GlossaryComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlossaryController extends Controller
{
    public function index(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        if (!Auth::user()->courses->contains($course->id)) {
            abort(403, 'У вас нет доступа к этому курсу.');
        }

        $terms = Glossary::where('course_id', $courseId)
            ->when($request->search, function ($query) use ($request) {
                $query->where('term', 'like', '%' . $request->search . '%');
            })
            ->orderBy('term')
            ->get();

        return view('student.glossary.index', compact('course', 'terms'));
    }

    public function show($id)
    {
        $glossary = Glossary::with(['author', 'comments.user', 'ratings'])
            ->findOrFail($id);

        return view('student.glossary.show', compact('glossary'));
    }

    public function rate(Request $request, $id)
    {
        $glossary = Glossary::findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        GlossaryRating::updateOrCreate(
            [
                'glossary_id' => $glossary->id,
                'user_id' => auth()->id()
            ],
            [
                'rating' => $request->rating
            ]
        );

        return back()->with('success', 'Оценка сохранена');
    }

    public function comment(Request $request, $id)
    {
        $glossary = Glossary::findOrFail($id);

        $request->validate([
            'comment' => 'required|string|max:1000'
        ]);

        GlossaryComment::create([
            'glossary_id' => $glossary->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment
        ]);

        return back()->with('success', 'Комментарий добавлен');
    }
}