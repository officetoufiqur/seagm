<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\NewsVideo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsVideoController extends Controller
{
    
    public function index()
    {
        $videos = NewsVideo::all();
        
        return Inertia::render('Videos/Index', [
            'videos' => $videos
        ]);
    }

    public function create()
    {
        return Inertia::render('Videos/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = null;
        if ($request->hasFile('thumbnail')) {
            $file = FileUpload::storeFile($request->file('thumbnail'), 'uploads/news_videos');
        }

        NewsVideo::create([
            'title' => $request->title,
            'video_url' => $request->video_url,
            'thumbnail' => $file,
        ]);

        return redirect()->route('news-videos.index')->with('success', 'Video created successfully.');
    }

    public function edit($id)
    {
        $video = NewsVideo::findOrFail($id);
        
        return Inertia::render('Videos/Edit', [
            'video' => $video
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'required',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $video = NewsVideo::findOrFail($id);

        $file = $video->thumbnail;
        if ($request->hasFile('thumbnail')) {
            $file = FileUpload::updateFile($request->file('thumbnail'), 'uploads/news_videos', $file);
        }

        $video->update([
            'title' => $request->title,
            'video_url' => $request->video_url,
            'thumbnail' => $file,
        ]);

        return redirect()->route('news-videos.index')->with('success', 'Video updated successfully.');
    }

    public function destroy($id)
    {
        $video = NewsVideo::findOrFail($id);

        if ($video->thumbnail) {
            FileUpload::deleteFile($video->thumbnail);
        }
        $video->delete();

        return redirect()->route('news-videos.index')->with('success', 'Video deleted successfully.');
    }
}
