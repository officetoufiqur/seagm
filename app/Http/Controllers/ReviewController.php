<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Review;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ApiResponse;

    public function index($api_id)
    {
        $reviews = Review::where('card_api_id', $api_id)
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

        $card = Card::where('api_id', $api_id)->first();

        if (!$card) {
            return $this->errorResponse('Card not found.', 404);
        }

        $review = Review::updateOrCreate(
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
