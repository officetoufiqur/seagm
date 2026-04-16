<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use App\Models\UserGuideCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubCategoryController extends Controller
{
    public function index()
    {
        $sub_categories = SubCategory::with('guidCategory')->get();

        return Inertia::render('SubCategory/Index', compact('sub_categories'));
    }

    public function create()
    {
        $category = UserGuideCategory::select('id', 'name')->get();

        return Inertia::render('SubCategory/Create', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $slug = str_replace(' ', '-', strtolower($request->name));

        SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('sub-categories.index')->with('message', 'Sub Category created successfully.');
    }

    public function edit($id)
    {
        $category = UserGuideCategory::select('id', 'name')->get();

        $sub_category = SubCategory::find($id);

        return Inertia::render('SubCategory/Edit', compact('sub_category', 'category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $sub_category = SubCategory::find($id);

        $slug = str_replace(' ', '-', strtolower($request->name));

        $sub_category->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()->route('sub-categories.index')->with('message', 'Sub Category updated successfully.');
    }

    public function destroy($id)
    {
        $sub_category = SubCategory::find($id);
        $sub_category->delete();

        return redirect()->route('sub-categories.index')->with('message', 'Sub Category deleted successfully.');
    }
}
