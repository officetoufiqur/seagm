<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Stripe\Stripe;
// use App\Models\User;
// use App\Models\Invoice;
// use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class StripePaymentController extends Controller
{
    public function checkout(Request $request)
    {
        // $request->validate([
        //     'package_id' => 'required|exists:packages,id',
        // ]);

        // $package = Package::findOrFail($request->package_id);
 
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Test Product',
                    ],
                    'unit_amount' => '100',
                ],
                'quantity' => 1,
            ]],
            'metadata' => [
                'user_id' => Auth::id(),
                // 'package_id' => $package->id,
            ],
            'success_url' => config('app.url').'/payment-success',
            'cancel_url' => config('app.url').'/payment-cancel',
        ]);

        Payment::create([
            'transaction_id' => $session->id,
            'user_id' => Auth::id(),
            'amount' => '100',
            'payment_method' => 'stripe',
            'status' => 'pending',
        ]);

        return response()->json([
            'url' => $session->url,
        ]);
    }

    
}
