<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use ApiResponse;

    public function index($id)
    {
        $reviews = Review::where('card_item_id', $id)->with('user:id,name')->get();

        if ($reviews->isEmpty()) {
            return $this->errorResponse('No reviews found for this card item.', 404);
        }

        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');

        $data = [
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 2),
            'reviews' => $reviews,
        ];

        return $this->successResponse($data, 'Reviews fetched successfully.');
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create([
            'card_item_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return $this->successResponse($review, 'Review submitted successfully.', 201);
    }
}
