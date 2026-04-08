<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Cart;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $user = $request->user();

        $carts = $user->carts()->with('card')->get();

        return $this->successResponse($carts, 'Cart items retrieved successfully');
    }

    public function store(Request $request, $id)
    {
        $user = Auth::user();

        $card = Card::with('cardItems')->findOrFail($id);

        $price = $card->card_items;
        return $price;

        $cart = Cart::create([
            'user_id' => $user->id,
            'card_id' => $card->id,
            'price' => $card->price,
        ]);

        return $this->successResponse($cart, 'Cart item added successfully');
    }
}
