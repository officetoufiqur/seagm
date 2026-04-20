<?php

namespace App\Http\Controllers\Api;

use App\Helpers\NewsByCategory;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsBanner;
use App\Models\NewsCategory;
use App\Models\NewsComment;
use App\Models\NewsVideo;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsApiController extends Controller
{
    use ApiResponse;

    public function newsHome()
    {
        $banners = NewsBanner::get();
        $newsHelper = new NewsByCategory;

        $videos = NewsVideo::latest()->paginate(15);

        $data = [
            'banners' => $banners,
            'latest_news' => News::with('author:id,name,image')->latest()->take(5)->get(),

            'promotions' => $newsHelper->get('promotions', 5),
            'top_up_guides' => $newsHelper->get('top-up-guides', 6),
            'game_guides' => $newsHelper->get('game-guides', 6),
            'esports' => $newsHelper->get('esports', 6),
            'events' => $newsHelper->get('events', 6),
            'scam_alerts' => $newsHelper->get('scam-alerts', 5),
            'corporate' => $newsHelper->get('corporate', 5),
            'videos' => $videos,
        ];

        return $this->successResponse($data, 'News data retrieved successfully');
    }

    public function latestNews()
    {
        $news = News::latest()->take(5)->get();

        return $this->successResponse($news, 'Latest news retrieved successfully');
    }

    public function gamingNewsByCategory()
    {
        $news = NewsCategory::whereIn('slug', [
            'esports',
            'console',
            'mobile',
            'pc',
            'events',
        ])->get();

        return $this->successResponse($news, 'Gaming news retrieved successfully');
    }

    public function guideCategory()
    {
        $news = NewsCategory::whereIn('slug', [
            'game-guides',
            'game-reviews',
            'tech-reviews',
        ])->get();

        return $this->successResponse($news, 'Gaming news retrieved successfully');
    }

    public function newsCategoryDetails(Request $request)
    {
        $slug = $request->query('slug');

        $news = NewsCategory::with('news.author:id,name')->where('slug', $slug)->get();

        return $this->successResponse($news, 'News category details retrieved successfully');
    }

    public function newsCategoryDetailsById($id)
    {
        $news = News::with(['author:id,name,image', 'category:id,name,slug'])
            ->where('id', $id)
            ->first();

        $previous = News::select('id', 'category_id', 'title', 'slug', 'image', 'published_at')->where('id', '<', $news->id)
            ->orderBy('id', 'desc')
            ->first();

        $next = News::select('id', 'category_id', 'title', 'slug', 'image', 'published_at')->where('id', '>', $news->id)
            ->orderBy('id', 'asc')
            ->first();


        $related = News::select('id', 'category_id', 'title', 'slug', 'image', 'published_at')->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->take(3)
            ->get();

        $mayAlsoLike = News::select('id', 'category_id', 'title', 'slug', 'image', 'published_at')->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->get();

        $data = [
            'news' => $news,
            'related' => $related,
            'previous' => $previous,
            'next' => $next,
            'mayAlsoLike' => $mayAlsoLike
        ];

        return $this->successResponse($data, 'News category details retrieved successfully');
    }

    public function newsComments(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $news = News::findOrFail($id);
        $user = Auth::user();

        $comment = NewsComment::create([
            'news_id' => $news->id,
            'user_id' => $user->id ?? 1,
            'comment' => $request->comment,
        ]);

        $news->increment('comments');

        return $this->successResponse($comment, 'News comment created successfully');

    }
}
