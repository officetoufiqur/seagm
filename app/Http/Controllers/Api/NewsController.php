<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Trait\ApiResponse;

class NewsController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $news = News::all();

        return $this->successResponse($news, 'News retrieved successfully');
    }

    public function show($id)
    {
        $news = News::findOrFail($id);

        return $this->successResponse($news, 'News retrieved successfully');
    }
}
