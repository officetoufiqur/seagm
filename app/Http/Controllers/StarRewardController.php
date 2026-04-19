<?php

namespace App\Http\Controllers;

use App\Helpers\FileUpload;
use App\Models\StarCategory;
use App\Models\StarReward;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StarRewardController extends Controller
{
    public function index()
    {
        $rewards = StarReward::all();

        return Inertia::render('Reward/Index', compact('rewards'));
    }

    public function create()
    {
        $categories = StarCategory::select('id', 'name')->get();

        return Inertia::render('Reward/Create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'coupon' => 'required',
            'reward' => 'required',
            'description' => 'required',
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = null;

        if ($request->hasFile('image')) {
            $file = FileUpload::storeFile($request->file('image'), 'uploads/rewards');
        }

        $reward = StarReward::create([
            'star_category_id' => $request->star_category_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'coupon' => $request->coupon,
            'reward' => $request->reward,
            'description' => $request->description,
            'image' => $file,
        ]);

        return redirect()->route('star-rewards.index')->with('message', 'Reward created successfully.');
    }

    public function edit($id)
    {
        $reward = StarReward::find($id);
        $categories = StarCategory::select('id', 'name')->get();

        return Inertia::render('Reward/Edit', compact('reward', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'coupon' => 'required',
            'reward' => 'required',
            'description' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $reward = StarReward::find($id);

        $file = $reward->image;

        if ($request->hasFile('image')) {
            $file = FileUpload::updateFile($request->file('image'), 'uploads/rewards', $file);
        }

        $reward->update([
            'star_category_id' => $request->star_category_id,
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'coupon' => $request->coupon,
            'reward' => $request->reward,
            'description' => $request->description,
            'image' => $file,
        ]);

        return redirect()->route('star-rewards.index')->with('message', 'Reward updated successfully.');
    }

    public function destroy($id)
    {
        $reward = StarReward::find($id);

        if ($reward->image) {
            FileUpload::deleteFile($reward->image);
        }

        $reward->delete();

        return redirect()->route('star-rewards.index')->with('message', 'Reward deleted successfully.');
    }
}
