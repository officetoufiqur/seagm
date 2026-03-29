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
        if ($request->has('items')) {
            return $this->handleMultipleItems($request);
        }

        return $this->handleSingleItem($request);
    }

    private function handleSingleItem(Request $request)
    {
        $request->validate([
            'card_id' => 'required',
            'id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = [
            'card_id' => $request->card_id,
            'id' => $request->id,
            'quantity' => $request->quantity
        ];

        return $this->processItems([$item]);
    }

    private function handleMultipleItems(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.card_id' => 'required',
            'items.*.id' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return $this->processItems($request->items);
    }

    private function processItems($items)
    {
        try {
            $totalAmount = 0;
            $currency = null;
            $paypalItems = [];

            foreach ($items as $item) {

                $data = SeagmHelper::get("v1/card-categories/{$item['card_id']}/card-types");
                $res = collect($data['data'])->firstWhere('id', $item['id']);

                if (! $res) {
                    return response()->json(['error' => 'Card not found'], 400);
                }

                $totalAmount += $res['unit_price'] * $item['quantity'];
                $currency = $res['currency'];

                $paypalItems[] = [
                    'name' => 'Card Item',
                    'unit_amount' => [
                        'currency_code' => strtoupper($currency),
                        'value' => number_format($res['unit_price'], 2, '.', ''),
                    ],
                    'quantity' => (string)$item['quantity'],
                ];
            }

            return $this->createPaypalOrder($totalAmount, $currency, $paypalItems, $items);

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    private function createPaypalOrder($totalAmount, $currency, $paypalItems, $items)
    {
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
                        'currency_code' => strtoupper($currency),
                        'value' => number_format($totalAmount, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => strtoupper($currency),
                                'value' => number_format($totalAmount, 2, '.', ''),
                            ],
                        ],
                    ],
                    'items' => $paypalItems,

                    // metadata
                    'custom_id' => Auth::id()
                ]
            ],
        ]);

        if (isset($response['id'])) {

            Payment::create([
                'transaction_id' => $response['id'],
                'user_id' => Auth::id(),
                'amount' => $totalAmount,
                'currency' => $currency,
                'payment_status' => 'pending',
                'payment_method' => 'PayPal',
                'items' => $items,
            ]);

            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return $this->successResponse($link['href'], 200);
                }
            }
        }

        return $this->errorResponse('Payment failed', 500);
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
