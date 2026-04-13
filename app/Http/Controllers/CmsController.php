<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\HomeCMS;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CmsController extends Controller
{
    public function index()
    {
        $cms = HomeCMS::all();

        return Inertia::render('Cms/Index', compact('cms'));
    }

    public function create()
    {
        $page = Page::select('id', 'slug')->get();

        return Inertia::render('Cms/Create', compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'section' => 'required',
            'title' => 'required',
            'description' => 'required',
            'icon' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/cms');
        }

        $page = Page::select('id', $request->page_id)->first();

        HomeCMS::create([
            'page_id' => $page->id,
            'section' => $page->slug,
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
            'image' => $file,
        ]);

        return redirect()->route('about-cms.index')->with('message', 'CMS created successfully.');
    }

    public function edit($id)
    {
        $cms = HomeCMS::find($id);
        $page = Page::select('id', 'slug')->get();

        return Inertia::render('Cms/Edit', compact('cms', 'page'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'page_id' => 'required|exists:pages,id',
            'section' => 'required',
            'title' => 'required',
            'description' => 'required',
            'icon' => 'nullable',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $cms = HomeCMS::find($id);

        $image = $cms->image;

        if ($request->hasFile('image')) {
            $image = FileUpload::updateFile($request->file('image'), 'uploads/cms', $image);
        }

        $page = Page::where('id', $request->page_id)->first();

        $cms->update([
            'page_id' => $page->id,
            'section' => $page->slug,
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon,
            'image' => $image,
        ]);

        return redirect()->route('about-cms.index')->with('message', 'CMS updated successfully.');
    }

    public function destroy($id)
    {
        $cms = HomeCMS::find($id);

        if ($cms->image) {
            FileUpload::deleteFile($cms->image);
        }

        $cms->delete();

        return redirect()->route('about-cms.index')->with('message', 'CMS deleted successfully.');
    }
}
