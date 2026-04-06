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
        $topups = Cache::remember('direct_topups_db', 300, function () {
            return DirectTopUp::paginate(10);
        });

        return $this->successResponse($topups, 'Direct top-ups retrieved successfully.');
    }

    public function show($id)
    {
        $topup = DirectTopUp::with('items')->where('api_id', $id)->first();

        if (!$topup) {
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
