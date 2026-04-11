<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;

class NewsLetterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:news_letters,email',
        ]);

        NewsLetter::create([
            'email' => $request->email,
        ]);

        return response()->json(['message' => 'Subscribed to newsletter successfully'], 201);
    }
}
