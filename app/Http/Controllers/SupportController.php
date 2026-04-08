<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\Support;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $supports = Auth::user()->supports()->latest()->get();

        return $this->successResponse($supports, 'Support tickets retrieved successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'question' => 'required|string',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('attachment')) {
            $file = FileUpload::storeFile($request->file('attachment'), 'uploads/supports');
        }

        $support = Support::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'question' => $request->question,
            'description' => $request->description,
            'attachment' => $file,
        ]);

        return $this->successResponse($support, 'Support ticket created successfully');
    }
}
