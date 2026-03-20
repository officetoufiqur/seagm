<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Paynow\Payments\Paynow;

class PaynowController extends Controller
{
    private function paynow()
    {
        return new Paynow(
            config('services.paynow.id'),
            config('services.paynow.key'),
            config('app.url').'/api/paynow/return',
            config('app.url').'/api/paynow/callback'
        );
    }

    // Initiate Payment
    public function initiate(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ]);

        try {
            $amount = $request->amount;
            $paynow = $this->paynow();

            $reference = 'Order-'.uniqid();

            $payment = $paynow->createPayment($reference, $request->email);
            $payment->add('Product', $amount);

            $response = $paynow->send($payment);

            if ($response->success()) {

                Payment::create([
                    'transaction_id' => $reference,
                    'user_id' => Auth::id() ?? null,
                    'amount' => $amount,
                    'payment_method' => 'Paynow',
                    'payment_status' => 'Pending',
                    'poll_url' => $response->pollUrl(),
                ]);

                return response()->json([
                    'status' => true,
                    'redirect_url' => $response->redirectUrl(),
                    'poll_url' => $response->pollUrl(),
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Failed to initiate payment',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Paynow Initiate Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Server error',
            ], 500);
        }
    }

    // Callback (SECURE VERSION)
    public function callback(Request $request)
    {
        Log::info('Paynow Callback', [
            'data' => $request->all(),
        ]);

        $reference = $request->input('reference');

        $payment = Payment::where('transaction_id', $reference)->first();

        if (! $payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        try {
            // VERIFY WITH POLL URL
            $response = Http::get($payment->poll_url);

            parse_str($response->body(), $data);

            if (is_array($data) && isset($data['status'])) {
                $payment->update([
                    'payment_status' => $data['status'],
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Paynow Callback Verification Failed', [
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    // Check Status (Manual Poll)
    public function status(Request $request)
    {
        $pollUrl = $request->query('poll_url');

        if (! $pollUrl) {
            return response()->json(['error' => 'poll_url required'], 400);
        }

        try {
            $response = Http::get($pollUrl);

            parse_str($response->body(), $data);

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch status',
            ], 500);
        }
    }
}
