<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleFeedback;
use App\Models\SubCategory;
use App\Models\UserGuideCategory;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;

class SupportApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $category = UserGuideCategory::all();

        $promotedArticles = Article::with([
            'subCategory:id,category_id,name',
            'subCategory.category:id,name',
        ])->where('is_promoted', 1)->get();

        $recentArticles = UserGuideCategory::where('name', 'Cards')
            ->with('subCategories.articles')
            ->first();

        $recentDescription = $recentArticles->subCategories
            ->flatMap(function ($sub) use ($recentArticles) {
                return $sub->articles->map(function ($article) use ($recentArticles) {
                    $article->category = [
                        'id' => $recentArticles->id,
                        'name' => $recentArticles->name,
                    ];

                    return $article;
                });
            })
            ->sortByDesc('created_at')
            ->take(6)
            ->values();

        $data = [
            'categories' => $category,
            'promotedArticles' => $promotedArticles,
            'recentDescription' => $recentDescription,
        ];

        return $this->successResponse($data, 'Support module fetched successfully.');
    }

    public function subCategory(Request $request)
    {
        $category = $request->category;

        $subCategory = UserGuideCategory::select('id', 'name', 'description')->where('name', $category)
            ->with([
                'subCategories:id,category_id,name',
                'subCategories.articles:id,sub_category_id,title',
            ])
            ->first();

        return $this->successResponse($subCategory, 'Sub category fetched successfully.');
    }

    public function subCategoryDetails($id)
    {
        $subCategory = SubCategory::select('id', 'name', 'category_id')->where('id', $id)
            ->with('articles', 'category:id,name')->first();

        return $this->successResponse($subCategory, 'Sub category fetched successfully.');
    }

    public function articleDetails(Request $request, $id)
    {
        $article = Article::with([
            'subCategory:id,category_id,name',
            'subCategory.category:id,name',
            'steps',
        ])->find($id);

        $helpful_count = ArticleFeedback::where('article_id', $id)
            ->where('is_helpful', true)
            ->count();

        $total_feedback = ArticleFeedback::where('article_id', $id)
            ->count();

        $userFeedback = ArticleFeedback::select('id', 'is_helpful')->where('article_id', $id)
            ->where('ip_address', $request->ip())
            ->first();

        $article->viewed_at = now();
        $article->save();

        $relatedArticles = Article::with([
            'subCategory:id,category_id,name',
            'subCategory.category:id,name',
        ])->where('sub_category_id', $article->sub_category_id)
            ->where('id', '!=', $article->id)
            ->select('id', 'title')
            ->latest()
            ->limit(6)
            ->get();

        $recentArticles = Article::with([
            'subCategory:id,category_id,name',
            'subCategory.category:id,name',
        ])->select('id', 'sub_category_id', 'title')->orderBy('viewed_at', 'desc')->limit(6)->get();

        return $this->successResponse([
            'helpful_count' => $helpful_count,
            'total_feedback' => $total_feedback,
            'userFeedback' => $userFeedback,
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'recentArticles' => $recentArticles,
        ], 'Article fetched successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (! $query) {
            return response()->json([
                'message' => 'Search query is required',
            ], 400);
        }

        $articles = Article::with([
            'subCategory:id,category_id,name',
            'subCategory.category:id,name',
        ])->where(function ($qBuilder) use ($query) {
            $qBuilder->where('title', 'LIKE', "%{$query}%")
                ->orWhere('content', 'LIKE', "%{$query}%")
                ->orWhereHas('subCategory', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('subCategory.category', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                });
        })
            ->latest()
            ->paginate(10);

        return $this->successResponse($articles, 'Articles fetched successfully.');
    }
}
