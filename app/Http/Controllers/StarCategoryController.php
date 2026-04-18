<?php

namespace App\Http\Controllers;

use App\Models\StarCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StarCategoryController extends Controller
{
    public function index()
    {
        $categories = StarCategory::all();

        return Inertia::render('StarCategory/Index', compact('categories'));
    }

    public function create()
    {
        return Inertia::render('StarCategory/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $slug = str_replace(' ', '-', strtolower($request->name));

        StarCategory::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('star-category.index')->with('message', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = StarCategory::find($id);

        return Inertia::render('StarCategory/Edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $slug = str_replace(' ', '-', strtolower($request->name));

        $category = StarCategory::find($id);
        $category->name = $request->name;
        $category->slug = $slug;
        $category->save();

        return redirect()->route('star-category.index')->with('message', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = StarCategory::find($id);
        $category->delete();

        return redirect()->route('star-category.index')->with('message', 'Category deleted successfully.');
    }
}
