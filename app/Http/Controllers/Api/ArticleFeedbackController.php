<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArticleFeedback;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;

class ArticleFeedbackController extends Controller
{
    use ApiResponse;

    public function store(Request $request, $id)
    {
        $request->validate([
            'is_helpful' => 'required|boolean',
        ]);

        $data = ArticleFeedback::create([
            'article_id' => $id,
            'is_helpful' => $request->is_helpful,
            'ip_address' => $request->ip(),
        ]);

        return $this->successResponse($data, 'Feedback submitted successfully');
    }
}
