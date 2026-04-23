<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\LessonTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonTemplateController extends Controller
{
    public function index()
    {
        $templates = LessonTemplate::where('teacher_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'blocks_json' => 'required|string',
        ]);

        $template = LessonTemplate::create([
            'teacher_id' => Auth::id(),
            'name' => $request->name,
            'blocks_json' => $request->blocks_json,
        ]);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    public function show($id)
    {
        $template = LessonTemplate::where('teacher_id', Auth::id())
            ->findOrFail($id);

        return response()->json($template);
    }
}