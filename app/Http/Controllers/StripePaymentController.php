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
            'items' => 'required|array|min:1',
            'items.*.card_id' => 'required',
            'items.*.id' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        $total = 0;
        $currency = null;

        foreach ($request->items as $item) {

            $data = SeagmHelper::get("v1/card-categories/{$item['card_id']}/card-types");

            $res = collect($data['data'])->firstWhere('id', $item['id']);

            if (!$res || $res['unit_price'] <= 0) {
                return response()->json(['error' => 'Invalid product'], 400);
            }

            // Currency check
            if (!$currency) {
                $currency = strtolower($res['currency']);
            } elseif ($currency !== strtolower($res['currency'])) {
                return response()->json([
                    'error' => 'All items must have same currency'
                ], 400);
            }

            $unitAmount = (int) ($res['unit_price'] * 100);
            $quantity = $item['quantity'];

            $total += $res['unit_price'] * $quantity;

            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $res['name'],
                    ],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => $quantity,
            ];
        }

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'metadata' => [
                'user_id' => Auth::id(),
                'items' => json_encode($request->items),
            ],
            'success_url' => config('app.url').'/payment-success',
            'cancel_url' => config('app.url').'/payment-cancel',
        ], [
            'idempotency_key' => uniqid('stripe_', true),
        ]);

        Payment::create([
            'transaction_id' => $session->id,
            'user_id' => Auth::id(),
            'amount' => $total,
            'currency' => $currency,
            'payment_method' => 'stripe',
            'status' => 'pending',
            'items' => $request->items,
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
    }
}
