<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponse;

    public function overview()
    {
        $user = Auth::user();

        $orders = $user->orders()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return $this->successResponse([
            'user' => $user,
            'orders' => $orders,
        ], 'Dashboard overview retrieved successfully');
    }

    public function myOrders(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter');
        $user = Auth::user();

        $ordersQuery = $user->orders()->with('cardItem');

        if ($search) {
            $ordersQuery->whereHas('order', function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
            })->orWhereHas('cardItem', function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('code', 'like', '%'.$search.'%');
            });
        }

        if ($filter) {
            $ordersQuery->where('status', $filter);
        }

        $orders = $ordersQuery->get();

        return $this->successResponse($orders, 'My orders retrieved successfully');
    }
}
