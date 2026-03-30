<?php

namespace App\Http\Controllers;

use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsCategoryController extends Controller
{
    public function index()
    {
        $category = NewsCategory::all();

        return Inertia::render('Category/Index', [
            'categories' => $category,
        ]);
    }

    public function create()
    {
        return Inertia::render('Category/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        NewsCategory::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('news-categories.index')->with('message', 'News category created successfully.');
    }

    public function edit($id)
    {
        $category = NewsCategory::findOrFail($id);

        return Inertia::render('Category/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = NewsCategory::findOrFail($id);
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->save();

        return redirect()->route('news-categories.index')->with('message', 'News category updated successfully.');
    }

    public function destroy($id)
    {
        $category = NewsCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('news-categories.index')->with('message', 'News category deleted successfully.');
    }
}
