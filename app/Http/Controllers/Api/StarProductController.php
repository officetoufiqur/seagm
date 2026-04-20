<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StarCategory;
use App\Models\StarReward;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;

class StarProductController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $filter = $request->input('filter');

        $categories = StarCategory::withCount('rewards')->get();

        $rewards = StarReward::with('starCategory')->select('id', 'star_category_id', 'title', 'subtitle', 'coupon', 'reward', 'image')
            ->when($filter, function ($query) use ($filter) {
                $query->whereHas('starCategory', function ($q) use ($filter) {
                    $q->where('slug', $filter);
                });
            })
            ->get();

        return $this->successResponse([
            'categories' => $categories,
            'rewards' => $rewards,
        ], 'Star products retrieved successfully');
    }

    public function show($id)
    {
        $reward = StarReward::with('starCategory')->find($id);

        return $this->successResponse($reward, 'Star product retrieved successfully');
    }
}
