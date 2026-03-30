<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Coupon;
use App\Models\News;
use App\Models\Promotion;
use App\Trait\ApiResponse;

class HomeController extends Controller
{
    use ApiResponse;
    
    public function index()
    {
        $promotions = Promotion::with('items')->where('status', 1)->get();

        if ($promotions->isEmpty()) {
            return $this->errorResponse('Promotions not found.', 404);
        }

         $coupons = Coupon::with('product:id,name,code,image')->where('is_active', true)->get();

        if ($coupons->isEmpty()) {
            return $this->errorResponse('Coupons not found.', 404);
        }

        $banners = Banner::where('status', 1)->get();

        if ($banners->isEmpty()) {
            return $this->errorResponse('Banners not found.', 404);
        }

        $news = News::with('category:id,name', 'author:id,name')->where('status', 1)->get();

        if ($news->isEmpty()) {
            return $this->errorResponse('News not found.', 404);
        }

        $data = [
            'banners' => $banners,
            'promotions' => $promotions,
            'news' => $news,
            'coupons' => $coupons
        ];

        return $this->successResponse($data, 'Home module fetched successfully.');
    }
}
