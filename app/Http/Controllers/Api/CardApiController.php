<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Trait\ApiResponse;
use Illuminate\Support\Facades\Cache;

class CardApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $categories = Cache::remember('card_categories_db', 300, function () {
            return Card::paginate(10);
        });

        return $this->successResponse($categories, 'Card categories retrieved successfully.');
    }

    public function show($id)
    {
        $category = Card::with('cardItems')->where('api_id', $id)->first();

        if (!$category) {
            return $this->errorResponse('Card category not found.', 404);
        }

        $reviews = $category->reviews()->with('user:id,name')->latest()->get();
        $averageRating = round($reviews->avg('rating'), 2);

        $category->reviews = $reviews;
        $category->average_rating = $averageRating;
        $category->total_reviews = $reviews->count();

        return $this->successResponse($category, 'Card category retrieved successfully.');
    }
}
