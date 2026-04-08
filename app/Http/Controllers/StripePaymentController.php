<?php

namespace App\Http\Controllers;

use App\Models\CardItem;
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

            $res = CardItem::where('api_category_id', $item['card_id'])
                ->where('api_id', $item['id'])
                ->where('status', true)
                ->first();

            if (! $res || $res->unit_price <= 0) {
                return response()->json(['error' => 'Invalid card'], 400);
            }

            // Currency check
            if (! $currency) {
                $currency = strtolower($res['currency']);
            } elseif ($currency !== strtolower($res['currency'])) {
                return response()->json([
                    'error' => 'All items must have same currency',
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

        $itemsData =[
            'items' => $request->items,
            'total' => $total,
            'type' => 'card',
        ];

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'metadata' => [
                'user_id' => Auth::id(),
                'items' => json_encode($itemsData)
            ],
            'success_url' => "https://seagm.netlify.app/payment-success",
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
            'items' => $itemsData,
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
    }

    public function success(Request $request)
    {
        $redirectParam = $request->get('param');

        $frontendUrl = 'https://seagm.netlify.app/payment-success?'.http_build_query([
            'param' => $redirectParam,
        ]);

        return redirect($frontendUrl);
    }
}
