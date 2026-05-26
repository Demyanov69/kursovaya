<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PortfolioItem;

class PortfolioItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url'
        ]);

        $portfolio = Auth::user()->portfolio;

        $portfolio->items()->create($request->all());

        return back()->with('success', 'Добавлено');
    }

    public function edit($id)
    {
        $item = PortfolioItem::findOrFail($id);

        // защита
        if ($item->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        return view('student.portfolio.edit_item', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = PortfolioItem::findOrFail($id);

        if ($item->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url'
        ]);

        $item->update($request->all());

        return redirect()->route('student.portfolio')->with('success', 'Обновлено');
    }

    public function destroy($id)
    {
        $item = PortfolioItem::findOrFail($id);

        if ($item->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();

        return back()->with('success', 'Удалено');
    }
}