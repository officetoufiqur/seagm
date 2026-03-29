<?php

namespace App\Http\Controllers;

use App\Helpers\SeagmHelper;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook')
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {

            $session = $event->data->object;

            DB::transaction(function () use ($session) {

                $payment = Payment::where('transaction_id', $session->id)->lockForUpdate()->first();

                if (! $payment || $payment->status === 'completed') {
                    return;
                }

                $stripeAmount = $session->amount_total / 100;

                if ((float) $payment->amount !== (float) $stripeAmount) {
                    throw new \Exception('Amount mismatch');
                }

                $payment->update([
                    'status' => 'paid',
                ]);

                $metadata = $session->metadata;

                try {
                    $order = SeagmHelper::post('v1/card-orders', [
                        'type_id' => $metadata->type_id,
                        'buy_amount' => $metadata->quantity ?? 1,
                        'mch_order_id' => $payment->transaction_id,
                    ]);

                } catch (\Exception $e) {
                    Log::error('SEAGM Order Failed: '.$e->getMessage());
                    $payment->update(['status' => 'failed']);
                }

            });
        }

        return response()->json(['received' => true]);
    }
}
