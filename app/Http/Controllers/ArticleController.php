<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Article;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with('subCategory')->get();

        return Inertia::render('Article/Index', [
            'articles' => $articles,
        ]);
    }

    public function create()
    {
        $sub_category = SubCategory::select('id', 'name')->get();

        return Inertia::render('Article/Create', [
            'sub_category' => $sub_category,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required',
            'items.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $article = Article::create([
            'sub_category_id' => $request->sub_category_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->items) {
            foreach ($request->items as $item) {
                $file = null;

                if ($item['image']) {
                    $file = FileUpload::storeFile($item['image'], 'uploads/articles');
                }

                $article->steps()->create([
                    'description' => $item['description'],
                    'image' => $file,
                ]);
            }
        }

        return redirect()->route('articles.index')->with('message', 'Article created successfully.');
    }

    public function edit($id)
    {
        $article = Article::with('steps')->findOrFail($id);
        $sub_category = SubCategory::select('id', 'name')->get();

        return Inertia::render('Article/Edit', [
            'article' => $article,
            'sub_category' => $sub_category,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sub_category_id' => 'required',
            'title' => 'required',
            'content' => 'required',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required',
            'items.*.image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $article = Article::findOrFail($id);
        $article->update([
            'sub_category_id' => $request->sub_category_id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        $existingIds = [];

        foreach ($request->items as $index => $item) {

            // UPDATE
            if (! empty($item['id'])) {

                $articleItem = $article->steps()->find($item['id']);

                if ($articleItem) {

                    $itemImage = $articleItem->image;

                    if ($request->hasFile("items.$index.image")) {
                        FileUpload::deleteFile($articleItem->image);

                        $itemImage = FileUpload::storeFile($request->file("items.$index.image"), 'uploads/articles');
                    }

                    $articleItem->update([
                        'description' => $item['description'],
                        'image' => $itemImage
                    ]);

                    $existingIds[] = $articleItem->id;
                }

            } else {
                $cardFile = null;

                if ($request->hasFile("items.$index.image")) {
                    $cardFile = FileUpload::storeFile(
                        $request->file("items.$index.image"),
                        'uploads/articles'
                    );
                }

                $newItem = $article->steps()->create([
                    'description' => $item['description'],
                    'image' => $cardFile
                ]);

                $existingIds[] = $newItem->id;
            }
        }

        $article->steps()->whereNotIn('id', $existingIds)->delete();

        return redirect()->route('articles.index')->with('message', 'Article updated successfully.');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        foreach ($article->steps as $step) {
            FileUpload::deleteFile($step->image);
        }

        $article->delete();

        return redirect()->route('articles.index')->with('message', 'Article deleted successfully.');
    }

    public function promoted($id)
    {
        $article = Article::findOrFail($id);
        $article->is_promoted = !$article->is_promoted;
        $article->save();
        return redirect()->route('articles.index')->with('message', 'Article promoted successfully.');
    }
}
