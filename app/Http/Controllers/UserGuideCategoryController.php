<?php

namespace App\Http\Controllers;

use App\Models\UserGuideCategory;
use Illuminate\Http\Request;

class UserGuideCategoryController extends Controller
{
    public function index()
    {
        $user_guide = UserGuideCategory::all();

        return inertia('GuideCategory/Index', compact('user_guide'));
    }

    public function create()
    {
        return inertia('GuideCategory/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'description' => 'required',
        ]);

        UserGuideCategory::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'description' => $request->description
        ]);

        return redirect()->route('user-guide-categories.index')->with('message', 'User Guide Category created successfully.');
    }

    public function edit($id)
    {
        $user_guide = UserGuideCategory::find($id);

        return inertia('GuideCategory/Edit', compact('user_guide'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'icon' => 'required',
            'description' => 'required',
        ]);

        $user_guide = UserGuideCategory::find($id);
        $user_guide->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'description' => $request->description
        ]);

        return redirect()->route('user-guide-categories.index')->with('message', 'User Guide Category updated successfully.');
    }

    public function destroy($id)
    {
        $user_guide = UserGuideCategory::find($id);
        $user_guide->delete();

        return redirect()->route('user-guide-categories.index')->with('message', 'User Guide Category deleted successfully.');
    }
}
