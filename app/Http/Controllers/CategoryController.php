<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($data);

        return redirect()->back()->with('success', 'Added iniative successfuly');
    }

    public function destroy($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();

        return redirect()->back()->with('success', 'Deleted Successfully');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'category' => $category]);
        }

        return redirect()->back()->with('success', 'edited successfully');
    }
}
