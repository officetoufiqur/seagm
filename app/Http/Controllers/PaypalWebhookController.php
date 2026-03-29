<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalWebhookController extends Controller
{
    public function handle(Request $request)
    {
        DB::beginTransaction();

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $verified = $provider->verifyWebHook([
                'transmission_id' => $request->header('paypal-transmission-id'),
                'transmission_time' => $request->header('paypal-transmission-time'),
                'cert_url' => $request->header('paypal-cert-url'),
                'auth_algo' => $request->header('paypal-auth-algo'),
                'transmission_sig' => $request->header('paypal-transmission-sig'),
                'webhook_id' => config('paypal.webhook_id'),
                'webhook_event' => $request->all(),
            ]);

            if (! $verified) {
                Log::warning('Invalid PayPal Webhook Signature', [
                    'headers' => $request->headers->all(),
                    'body' => $request->all(),
                ]);

                DB::rollBack();

                return response()->json(['error' => 'Invalid webhook'], 400);
            }

            $event = $request->input('event_type');
            $resource = $request->input('resource');

            Log::info('PayPal Webhook Received', [
                'event' => $event,
                'resource' => $resource,
            ]);

            switch ($event) {

                // PAYMENT SUCCESS
                case 'PAYMENT.CAPTURE.COMPLETED':

                    $transactionId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;

                    if (! $transactionId) {
                        break;
                    }

                    $payment = Payment::where('transaction_id', $transactionId)
                        ->lockForUpdate()
                        ->first();

                    if (! $payment) {
                        Log::warning('Payment not found', ['transaction_id' => $transactionId]);
                        break;
                    }

                    // Idempotency check
                    if ($payment->payment_status === 'paid') {
                        break;
                    }

                    $paidAmount = $resource['amount']['value'];
                    $paidCurrency = $resource['amount']['currency_code'];

                    // Verify amount & currency
                    if (
                        number_format($payment->amount, 2) !== number_format($paidAmount, 2) ||
                        strtoupper($payment->currency) !== strtoupper($paidCurrency)
                    ) {
                        Log::error('Payment verification failed', [
                            'db_amount' => $payment->amount,
                            'paypal_amount' => $paidAmount,
                        ]);

                        DB::rollBack();

                        return response()->json(['error' => 'Verification failed'], 400);
                    }

                    // Update payment
                    $items = $payment->items;

                    $allSuccess = true;

                    // foreach ($items as $item) {

                    //     // $res = SeagmHelper::post('v1/orders', [
                    //     //     'card_id' => $item['card_id'],
                    //     //     'card_type_id' => $item['id'],
                    //     //     'quantity' => $item['quantity'],
                    //     // ]);

                    //     if (! isset($res['success']) || ! $res['success']) {
                    //         $allSuccess = false;
                    //     }
                    // }

                    $payment->update([
                        'payment_status' => $allSuccess ? 'paid' : 'failed'
                    ]);

                    break;

                    // PAYMENT FAILED
                case 'PAYMENT.CAPTURE.DENIED':

                    $transactionId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;

                    if ($transactionId) {
                        Payment::where('transaction_id', $transactionId)
                            ->update([
                                'payment_status' => 'failed',
                                'payload' => json_encode($request->all()),
                            ]);
                    }

                    break;

                case 'PAYMENT.CAPTURE.REFUNDED':

                    $transactionId = $resource['supplementary_data']['related_ids']['order_id'] ?? null;

                    if ($transactionId) {
                        Payment::where('transaction_id', $transactionId)
                            ->update([
                                'payment_status' => 'failed',
                                'payload' => json_encode($request->all()),
                            ]);
                    }

                    break;

                case 'CHECKOUT.ORDER.APPROVED':

                    Log::info('Order approved', [
                        'resource' => $resource,
                    ]);

                    break;
            }

            DB::commit();

            return response()->json(['status' => 'success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('PayPal Webhook Error', [
                'error' => $e->getMessage(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Webhook failed'], 500);
        }
    }
}
