<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Glossary;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    public function index(Request $request)
    {
        $terms = Glossary::with(['course', 'author'])
            ->when($request->search, function ($query) use ($request) {
                $query->where('term', 'like', '%' . $request->search . '%');
            })
            ->orderBy('term')
            ->paginate(20);

        return view('admin.glossary.index', compact('terms'));
    }

    public function create()
    {
        $courses = Course::orderBy('title')->get();

        return view('admin.glossary.form', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'term' => 'required|string|max:255',
            'short_definition' => 'required|string|max:255',
            'full_definition' => 'required|string'
        ]);

        $data['created_by'] = auth()->id();
        $data['status'] = 'approved';

        Glossary::create($data);

        return redirect()
            ->route('admin.glossary.index')
            ->with('success', 'Термин успешно создан.');
    }

    public function edit($id)
    {
        $term = Glossary::findOrFail($id);

        $courses = Course::orderBy('title')->get();

        return view('admin.glossary.form', compact('term', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $term = Glossary::findOrFail($id);

        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'term' => 'required|string|max:255',
            'short_definition' => 'required|string|max:255',
            'full_definition' => 'required|string'
        ]);

        $term->update($data);

        return redirect()
            ->route('admin.glossary.index')
            ->with('success', 'Термин успешно обновлён.');
    }

    public function destroy($id)
    {
        $term = Glossary::findOrFail($id);

        $term->delete();

        return back()->with('success', 'Термин удалён администратором.');
    }

    public function show($id)
    {
        $term = Glossary::with([
            'course',
            'author',
            'comments.user',
            'ratings'
        ])->findOrFail($id);

        return view('admin.glossary.show', compact('term'));
    }
}