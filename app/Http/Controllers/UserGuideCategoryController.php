<?php

namespace App\Http\Controllers;

use App\Models\UserGuideCategory;
use Illuminate\Http\Request;

class UserGuideCategoryController extends Controller
{
    public function index()
    {
        $user_guide = UserGuideCategory::all();

        return inertia('GuideCategory/Index', compact('user_guide'));
    }

    public function create()
    {
        return inertia('GuideCategory/Create');
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {

            $file = $request->file('image');
            $filename = time().'.'.$file->getClientOriginalExtension();

            $file->move(public_path('uploads'), $filename);

            return response()->json([
                'url' => asset('uploads/'.$filename),
            ]);
        }
    }
}
