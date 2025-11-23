<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Initiative;
use App\Models\Category;

class InitiativeController extends Controller
{
    public function index()
    {
        $initiatives = Initiative::with('category')->orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('name')->get();
        return view('dashboard', compact('initiatives', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'impact_score' => 'nullable|integer|min:0',
        ]);

        Initiative::create($data);

        return redirect()->back()->with('success', 'Added iniative successfuly');
    }

    public function update(Request $request, $id)
    {
        $initiative = Initiative::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'impact_score' => 'nullable|integer|min:0',
        ]);

        $initiative->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'initiative' => $initiative]);
        }

        return redirect()->back()->with('success', 'edited successfully');
    }

    public function destroy($id)
    {
        $initiative = Initiative::findOrFail($id);
        $initiative->delete();
        return redirect()->back()->with('success', 'Deleted Successfully');
    }
}
