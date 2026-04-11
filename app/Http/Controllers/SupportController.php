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
        $supports = Auth::user()
            ->supports()
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->latest()
            ->get();

        return $this->successResponse($supports, 'All tickets');
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
        ]);

        $support->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment' => $file,
        ]);

        return $this->successResponse($support, 'Support ticket created successfully');
    }

    public function show($id)
    {
        $support = Support::with(['messages.user:id,name,image'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return $this->successResponse($support, 'Ticket details');
    }

    public function replyStore(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $support = Support::findOrFail($id);

        $file = null;

        if ($request->hasFile('attachment')) {
            $file = FileUpload::storeFile($request->file('attachment'), 'uploads/supports');
        }

        $message = $support->messages()->create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'attachment' => $file,
        ]);


        if (! $support->receiver_id && Auth::id() !== $support->user_id) {
            $support->update([
                'receiver_id' => Auth::id(),
                'status' => 'in_progress',
            ]);
        }

        return $this->successResponse($message, 'Reply sent');
    }
}
