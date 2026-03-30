<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('category', 'author')->get();

        return Inertia::render('News/Index', [
            'news' => $news,
        ]);
    }

    public function create()
    {
        $categories = NewsCategory::select('id', 'name')->get();

        return Inertia::render('News/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:news_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/news');
        }

        $slug = Str::slug($request->title);

        $news = new News;
        $news->category_id = $request->category_id;
        $news->author_id = Auth::id();
        $news->title = $request->title;
        $news->slug = $slug;
        $news->content = $request->content;
        $news->image = $file;
        $news->published_at = now();

        $news->save();

        return redirect()->route('news.index')->with('message', 'News created successfully.');
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::select('id', 'name')->get();

        return Inertia::render('News/Edit', [
            'news' => $news,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'category_id' => 'required|exists:news_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($news->image) {
                FileUpload::deleteFile($news->image);
            }

            $file = FileUpload::storeFile($request->file('image'), 'uploads/news');
            $news->image = $file;
        }

        $slug = Str::slug($request->title);

        $news->category_id = $request->category_id;
        $news->title = $request->title;
        $news->slug = $slug;
        $news->content = $request->content;

        $news->save();

        return redirect()->route('news.index')->with('message', 'News updated successfully.');
    }

    public function status(News $news)
    {
        $news->status = !$news->status;
        $news->save();

        return redirect()->route('news.index')->with('message', 'News status updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->image) {
            FileUpload::deleteFile($news->image);
        }

        $news->delete();

        return redirect()->route('news.index')->with('message', 'News deleted successfully.');
    }
}
