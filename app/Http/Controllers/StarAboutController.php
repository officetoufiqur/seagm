<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\StarAbout;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StarAboutController extends Controller
{
    public function index()
    {
        $abouts = StarAbout::all();

        return Inertia::render('StarAbout/Index', compact('abouts'));
    }

    public function create()
    {
        return Inertia::render('StarAbout/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'section' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/star_abouts');
        }

        $about = StarAbout::create([
            'section' => $request->section,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $file,
        ]);

        return redirect()->route('star-abouts.index')->with('message', 'About created successfully.');
    }

    public function edit($id)
    {
        $about = StarAbout::findOrFail($id);

        return Inertia::render('StarAbout/Edit', compact('about'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'section' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $about = StarAbout::findOrFail($id);

        $file = $about->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/star_abouts');
        }

        $about->update([
            'section' => $request->section,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $file,
        ]);

        return redirect()->route('star-abouts.index')->with('message', 'About updated successfully.');
    }

    public function destroy($id)
    {
        $about = StarAbout::findOrFail($id);
        if ($about->image) {
            FileUpload::deleteFile($about->image);
        }
        $about->delete();

        return redirect()->route('star-abouts.index')->with('message', 'About deleted successfully.');
    }
}
