<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\ExclusiveOffer;
use App\Models\News;
use App\Models\Promotion;
use App\Trait\ApiResponse;

class HomeController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $promotions = Promotion::with('items')->where('status', 1)->get();
        $coupons = Coupon::with('card:id,name,code,image')->where('is_active', true)->get();
        $banners = Banner::where('status', 1)->get();
        $news = News::with('category:id,name', 'author:id,name')->where('status', 1)->get();
        $offers = ExclusiveOffer::where('is_active', 1)->get();

        $data = [
            'banners' => $banners,
            'exclusive_offers' => $offers,
            'coupons' => $coupons,
            'news' => $news,
            'promotions' => $promotions,
        ];

        return $this->successResponse($data, 'Home module fetched successfully.');
    }
}
