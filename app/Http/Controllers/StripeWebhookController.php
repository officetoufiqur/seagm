<?php

namespace App\Http\Controllers;

// use App\Helpers\SeagmHelper;
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

        switch ($event->type) {

            case 'checkout.session.completed':

                $session = $event->data->object;

                DB::transaction(function () use ($session) {

                    $payment = Payment::where('transaction_id', $session->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$payment || $payment->status === 'paid') {
                        return;
                    }

                    $stripeAmount = $session->amount_total / 100;

                    if (abs($payment->amount - $stripeAmount) > 0.01) {
                        Log::error('Amount mismatch for payment: '.$payment->id);
                        return;
                    }

                    $items = json_decode($session->metadata->items ?? '[]', true);

                    $allSuccess = true;

                    // foreach ($items as $item) {
                    //     try {
                    //         SeagmHelper::post('v1/card-orders', [
                    //             'type_id' => $item['id'],
                    //             'buy_amount' => $item['quantity'],
                    //             'mch_order_id' => $payment->transaction_id.'_'.$item['id'],
                    //         ]);
                    //     } catch (\Exception $e) {
                    //         $allSuccess = false;
                    //         Log::error('SEAGM Order Failed: '.$e->getMessage());
                    //     }
                    // }

                    $payment->update([
                        'status' => $allSuccess ? 'paid' : 'failed',
                    ]);
                });

                break;

            case 'checkout.session.expired':

                $session = $event->data->object;

                Payment::where('transaction_id', $session->id)
                    ->update(['status' => 'expired']);

                break;
        }

        return response()->json(['received' => true]);
    }
}
