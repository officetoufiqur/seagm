<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DirectTopUp;
use App\Trait\ApiResponse;
use Illuminate\Support\Facades\Cache;

class DirectTopUpApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $data = Cache::remember('direct_topups_db', 300, function () {

            $topups = DirectTopUp::get();

            // $popular = DirectTopUp::withSum(['orders as total_sold' => function ($q) {
            //     $q->where('status', 'completed')
            //         ->where('product_type', \App\Models\DirectTopUp::class);
            // }], 'quantity')
            //     ->orderByDesc('total_sold')
            //     ->take(6)
            //     ->get();

            $popular = DirectTopUp::withSum(['orders as total_sold' => function ($q) {
                $q->where('status', 'completed')
                    ->where('product_type', \App\Models\DirectTopUp::class);
            }], 'quantity')
                ->withAvg('topUpReviews as avg_rating', 'rating')
                ->withCount('topUpReviews as total_reviews')
                ->orderByDesc('total_sold')
                ->take(6)
                ->get();

            return [
                'topups' => $topups,
                'popular' => $popular,
            ];
        });

        return $this->successResponse($data, 'Direct top-ups + popular retrieved successfully');
    }

    public function show($id)
    {
        $topup = DirectTopUp::with('items')->where('api_id', $id)->first();

        if (! $topup) {
            return $this->errorResponse('Direct top-up not found.', 404);
        }

        $reviews = $topup->topUpReviews()->with('user:id,name')->latest()->get();
        $averageRating = round($reviews->avg('rating'), 2);

        $topup->reviews = $reviews;
        $topup->average_rating = $averageRating;
        $topup->total_reviews = $reviews->count();

        return $this->successResponse($topup, 'Direct top-up retrieved successfully.');
    }
}
