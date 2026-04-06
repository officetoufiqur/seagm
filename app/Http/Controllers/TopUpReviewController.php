<?php

namespace App\Http\Controllers;

use App\Models\DirectTopUp;
use App\Models\TopUpReview;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopUpReviewController extends Controller
{
     use ApiResponse;

    public function index($api_id)
    {
        $reviews = TopUpReview::where('card_api_id', $api_id)
            ->with('user:id,name')
            ->latest()
            ->get();

        if ($reviews->isEmpty()) {
            return $this->errorResponse('No reviews found for this card.', 404);
        }

        $data = [
            'total_reviews' => $reviews->count(),
            'average_rating' => round($reviews->avg('rating'), 2),
            'reviews' => $reviews,
        ];

        return $this->successResponse($data, 'Reviews fetched successfully.');
    }

    public function store(Request $request, $api_id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $topup = DirectTopUp::where('api_id', $api_id)->first();

        if (!$topup) {
            return $this->errorResponse('Direct top-up not found.', 404);
        }

        $review = TopUpReview::updateOrCreate(
            [
                'card_api_id' => $api_id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]
        );

        return $this->successResponse($review, 'Review submitted successfully.', 201);
    }
}
