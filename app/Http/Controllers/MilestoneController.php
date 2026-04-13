<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Milestone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MilestoneController extends Controller
{
    public function index()
    {
        $milestones = Milestone::all();

        return Inertia::render('Milestone/Index', compact('milestones'));
    }

    public function create()
    {
        return Inertia::render('Milestone/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'title' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/milestone');
        }

        Milestone::create([
            'year' => $request->year,
            'title' => $request->title,
            'image' => $file,
        ]);

        return redirect()->route('milestones.index')->with('success', 'Milestone created successfully.');
    }

    public function edit($id)
    {
        $milestone = Milestone::findOrFail($id);

        return Inertia::render('Milestone/Edit', compact('milestone'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'year' => 'required',
            'title' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $milestone = Milestone::findOrFail($id);

        $file = $milestone->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/milestone', $file);
        }

        $milestone->update([
            'year' => $request->year,
            'title' => $request->title,
            'image' => $file,
        ]);

        return redirect()->route('milestones.index')->with('success', 'Milestone updated successfully.');
    }

    public function destroy($id)
    {
        $milestone = Milestone::findOrFail($id);

        if ($milestone->image) {
            FileUpload::deleteFile($milestone->image);
        }

        $milestone->delete();

        return redirect()->route('milestones.index')->with('success', 'Milestone deleted successfully.');
    }
}
