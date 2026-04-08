<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DirectTopUp;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
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

        $reviews = $topup->topUpReviews()->with('user:id,name,image')->latest()->get();
        $averageRating = round($reviews->avg('rating'), 2);

        $topup->reviews = $reviews->take(6);
        $topup->average_rating = $averageRating;
        $topup->total_reviews = $reviews->count();

        return $this->successResponse($topup, 'Direct top-up retrieved successfully.');
    }

    public function allReviews($id, Request $request)
    {
        $ratingMap = [
            'excellent' => 5,
            'great' => 4,
            'average' => 3,
            'poor' => 2,
            'bad' => 1,
        ];

        $topup = DirectTopUp::select('id', 'api_id', 'name', 'region', 'image')
            ->where('api_id', $id)
            ->first();

        if (! $topup) {
            return $this->errorResponse('Direct top-up not found.', 404);
        }

        // Base query
        $query = $topup->topUpReviews()
            ->with('user:id,name,image');

        // Filter handling
        if ($request->has('filter') && $request->filter !== 'all') {
            $filter = strtolower($request->filter);

            if (! array_key_exists($filter, $ratingMap)) {
                return $this->errorResponse('Invalid filter value.', 400);
            }

            $query->where('rating', $ratingMap[$filter]);
        }

        // Get reviews
        $reviews = $query->latest()->get();

        // Summary data
        $totalReviews = $topup->topUpReviews()->count();
        $averageRating = round($topup->topUpReviews()->avg('rating'), 1);

        $ratingCounts = [];
        foreach ($ratingMap as $key => $value) {
            $ratingCounts[$key] = $topup->topUpReviews()
                ->where('rating', $value)
                ->count();
        }

        return $this->successResponse([
            'topup' => $topup,
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating,
            'rating_counts' => $ratingCounts,
            'reviews' => $reviews,
        ], 'Reviews retrieved successfully.');
    }
}
