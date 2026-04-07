<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\DirectTopUp;
use App\Models\Favorite;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $type = $request->query('type');
        $user = Auth::user();

         $query = Favorite::with([
            'favoritable' => function ($q) {
                $q->withCount('favorites as total_favorites');
            }
        ])
        ->where('user_id', $user->id);

        if ($type) {
            if ($type === 'topup') {
                $query->where('favoritable_type', DirectTopUp::class);
            } elseif ($type === 'card') {
                $query->where('favoritable_type', Card::class);
            } else {
                return $this->errorResponse('Invalid type.', 400);
            }
        }

        $favorites = $query->latest()->get();
        $total = $favorites->count();

        $data = [
            'total' => $total,
            'favorites' => $favorites,
        ];
        return $this->successResponse($data, 'Favorites retrieved successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'favoritable_id' => 'required|string',
        ]);

        $user = Auth::user();

        $item = DirectTopUp::where('api_id', $request->favoritable_id)->first();
        $type = DirectTopUp::class;

        if (! $item) {
            $item = Card::where('api_id', $request->favoritable_id)->first();
            $type = Card::class;
        }

        if (! $item) {
            return $this->errorResponse('Item not found.', 404);
        }

        $existingFavorite = Favorite::where([
            'user_id' => $user->id,
            'favoritable_id' => $item->id,
            'favoritable_type' => $type,
        ])->first();

        if ($existingFavorite) {
            return $this->errorResponse('Item already added to favorites.', 400);
        }

        $favorite = Favorite::create(
            [
                'user_id' => $user->id,
                'favoritable_id' => $item->id,
                'favoritable_type' => $type,
                'api_id' => $item->api_id,
            ]
        );

        return $this->successResponse($favorite, 'Added to favorites successfully');
    }
}
