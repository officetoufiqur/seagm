<?php

namespace App\Http\Controllers;

use App\Helpers\SeagmHelper;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'card_id' => 'required',
            'id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $data = SeagmHelper::get("v1/card-categories/{$request->card_id}/card-types");

        $res = collect($data['data'])->firstWhere('id', $request->id);

        if (! $res) {
            return response()->json([
                'error' => 'Card type not found',
            ], 400);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $unitAmount = (int) ($res['unit_price'] * 100);
        $quantity = $request->quantity;
        $total = $res['unit_price'] * $quantity;

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($res['currency']),
                    'product_data' => [
                        'name' => $res['name'],
                    ],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => $quantity,
            ]],
            'metadata' => [
                'user_id' => Auth::id(),
                'card_id' => $request->card_id,
                'type_id' => $request->id,
                'quantity' => $quantity,
            ],
            'success_url' => config('app.url').'/payment-success',
            'cancel_url' => config('app.url').'/payment-cancel',
        ]);

        Payment::create([
            'transaction_id' => $session->id,
            'user_id' => Auth::id(),
            'amount' => $total,
            'currency' => $res['currency'],
            'payment_method' => 'stripe',
            'status' => 'pending',
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
    }
}
