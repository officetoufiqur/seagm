<?php

namespace App\Http\Controllers;

use App\Helpers\SeagmHelper;
use App\Models\Payment;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    use ApiResponse;
    
    public function payment(Request $request)
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

        $unitAmount = (int) ($res['unit_price'] * 100);
        $quantity = $request->quantity;
        
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => strtolower($res['currency']),
                        "value" => $unitAmount
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id']) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }else{
            return redirect()->route('paypal.payment.cancel');
        }

    }

    public function paymentCancel()
    {
        return $this->errorResponse("Payment cancelled.", 500);
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            // Payment info extract
            $transaction_id = $response['id'];
            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $status = $response['status'];

            // Save to database
            Payment::create([
                'transaction_id' => $transaction_id,
                'user_id'     => Auth::id(),
                'amount'         => $amount,
                'payment_method'       => 'PayPal',
                'payment_status'         => $status,
            ]);

            return $this->successResponse("Payment successful.", 200);
        }else{
            return redirect()->route('paypal.payment.cancel');
        }
    }
}
