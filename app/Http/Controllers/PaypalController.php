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

        try {
            $data = SeagmHelper::get("v1/card-categories/{$request->card_id}/card-types");

            $res = collect($data['data'])->firstWhere('id', $request->id);

            if (! $res) {
                return response()->json([
                    'error' => 'Card type not found',
                ], 400);
            }

            $quantity = $request->quantity;
            $totalAmount = $res['unit_price'] * $quantity;

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => route('paypal.payment.success'),
                    'cancel_url' => route('paypal.payment.cancel'),
                ],
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => strtoupper($res['currency']),
                            'value' => number_format($totalAmount, 2, '.', ''),
                        ],
                    ],
                ],
            ]);

            if (isset($response['id']) && $response['id']) {
                Payment::create([
                    'transaction_id' => $response['id'],
                    'user_id' => Auth::id(),
                    'amount' => $totalAmount,
                    'currency' => $res['currency'],
                    'payment_method' => 'PayPal',
                    'payment_status' => 'pending',
                ]);

                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return $this->successResponse($link['href'], 200);
                    }
                }
            } else {
                return redirect()->route('paypal.payment.cancel');
            }

        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong', 500);
        }

    }

    public function paymentCancel()
    {
        return $this->errorResponse('Payment cancelled.', 500);
    }

    public function paymentSuccess(Request $request)
    {
        $payment = Payment::where('transaction_id', $request->token)->first();

        if (! $payment) {
            return $this->errorResponse('Payment not found', 404);
        }

        if ($payment->payment_status === 'paid') {
            return $this->successResponse('Payment successful', 200);
        }

        return $this->successResponse('Payment processing...', 200);
    }

    // public function paymentSuccess(Request $request)
    // {
    //     try {
    //         $provider = new PayPalClient;
    //         $provider->setApiCredentials(config('paypal'));
    //         $provider->getAccessToken();

    //         $payment = Payment::where('transaction_id', $request->token)->first();

    //         if (! $payment) {
    //             return $this->errorResponse('Payment not found', 404);
    //         }

    //         if ($payment->payment_status === 'paid') {
    //             return $this->successResponse('Already processed', 200);
    //         }

    //         $response = $provider->capturePaymentOrder($request->token);

    //         if (isset($response['status']) && $response['status'] === 'COMPLETED') {

    //             $paidAmount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
    //             $paidCurrency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];

    //             if (
    //                 number_format($payment->amount, 2) !== number_format($paidAmount, 2) ||
    //                 $payment->currency !== $paidCurrency
    //             ) {
    //                 return $this->errorResponse('Verification failed', 400);
    //             }

    //             $payment->update([
    //                 'payment_status' => 'paid',
    //             ]);

    //             return $this->successResponse('Payment successful.', 200);
    //         }

    //         return redirect()->route('paypal.payment.cancel');

    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage(), 500);
    //     }
    // }
}
