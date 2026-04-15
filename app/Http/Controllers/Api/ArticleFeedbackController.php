<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArticleFeedback;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;

class ArticleFeedbackController extends Controller
{
    use ApiResponse;

    public function index($id)
    {
        $feedback = ArticleFeedback::where('article_id', $id)->get();

        $helpful_count = $feedback->feedback()
            ->where('is_helpful', true)
            ->count();

        $total_feedback = $feedback->feedback()->count();

        return $this->successResponse([
            'helpful_count' => $helpful_count,
            'total_feedback' => $total_feedback,
        ], 'Feedback fetched successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'is_helpful' => 'required|boolean',
        ]);

        $data = ArticleFeedback::create([
            'article_id' => $request->article_id,
            'is_helpful' => $request->is_helpful,
            'ip_address' => $request->ip(),
        ]);

        return $this->successResponse($data, 'Feedback submitted successfully');
    }
}
