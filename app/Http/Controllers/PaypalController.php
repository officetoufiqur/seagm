<?php

namespace App\Http\Controllers;

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
                        "currency_code" => "USD",
                        "value" => "100.00"
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
