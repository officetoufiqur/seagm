<?php

namespace App\Http\Controllers\Api;

use App\Helpers\NewsByCategory;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsBanner;
use App\Trait\ApiResponse;

class NewsApiController extends Controller
{
    use ApiResponse;

    public function newsHome()
    {
        $banners = NewsBanner::get();
        $newsHelper = new NewsByCategory;

        $data = [
            'banners' => $banners,
            'latest_news' => News::latest()->take(5)->get(),

            'promotions' => $newsHelper->get('promotions', 5),
            'top_up_guides' => $newsHelper->get('top-up-guides', 6),
            'game_guides' => $newsHelper->get('game-guides', 6),
            'esports' => $newsHelper->get('esports', 6),
            'events' => $newsHelper->get('events', 6),
            'scam_alerts' => $newsHelper->get('scam-alerts', 5),
            'corporate' => $newsHelper->get('corporate', 5),
        ];

        return $this->successResponse($data, 'News data retrieved successfully');
    }

    public function latestNews()
    {
        $news = News::latest()->take(5)->get();

        return $this->successResponse($news, 'Latest news retrieved successfully');
    }
}
