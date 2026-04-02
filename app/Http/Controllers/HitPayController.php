<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HitPayController extends Controller
{
    use ApiResponse;

    public function hitpay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $methods = [
            'paynow_online',
            // 'card',
            // 'grabpay',
            // 'shopee_pay',
        ];

        $items = [
            [
                'name' => 'Sample card',
                'quantity' => 1,
                'price' => $request->amount,
            ],
        ];

        $amount = $request->amount;
        $currency = 'SGD';

        $data = [
            'amount' => $amount,
            'currency' => strtoupper($currency),
            'payment_methods' => $methods,
            'email' => 'customer@mail.com',
            'redirect_url' => route('hitpay.success'),
            'webhook' => 'https://nonpluralistic-princeton-subaqueous.ngrok-free.dev/api/hitpay/webhook',
        ];

        $url = config('services.hitpay.url').'/payment-requests';

        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => config('services.hitpay.key'),
        ])->post($url, $data);

        $response = $response->json();

        Payment::create([
            'transaction_id' => $response['reference_number'],
            'user_id' => Auth::id(),
            'amount' => $amount,
            'currency' => $currency,
            'payment_status' => 'pending',
            'payment_method' => 'HitPay',
            'items' => $items,
        ]);

        return $this->successResponse($response['url'], 'Payment successful', 200);
    }

    public function hitpaySuccess()
    {
        return 'Payment Successful';
    }

    public function webhook(Request $request)
    {

        $payload = $request->getContent();

        $signature = $request->header('HITPAY-SIGNATURE');

        Log::info('HITPAY WEBHOOK', [
            'payload' => $request->getContent(),
            'signature' => $request->header('HITPAY-SIGNATURE'),
        ]);

        $salt = config('services.hitpay.salt');

        $computedSignature = hash_hmac('sha256', $payload, $salt);

        // verify
        if (! hash_equals($computedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // decode payload
        $data = json_decode($payload, true);

        // find payment
        $payment = Payment::where('transaction_id', $data['id'])->first();

        if (! $payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // prevent duplicate update
        if ($payment->payment_status === 'paid') {
            return response()->json(['message' => 'Already processed']);
        }

        // update status
        if ($data['status'] === 'completed') {
            $payment->update([
                'payment_status' => 'paid',
            ]);
        }

        return response()->json(['message' => 'Webhook verified']);
    }
}
