<?php

namespace App\Http\Controllers;

// use App\Helpers\SeagmHelper;
use App\Models\Card;
use App\Models\CardItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\UserCard;
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
            Log::error('Stripe Webhook Error: '.$e->getMessage());

            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {

            case 'checkout.session.completed':

                $session = $event->data->object;

                DB::transaction(function () use ($session) {

                    $payment = Payment::where('transaction_id', $session->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $payment || $payment->status === 'paid') {
                        return;
                    }

                    $stripeAmount = $session->amount_total / 100;

                    if (abs($payment->amount - $stripeAmount) > 0.01) {
                        Log::error('Amount mismatch for payment: '.$payment->id);

                        return;
                    }

                    $userId = $session->metadata->user_id;
                    $items = json_decode($session->metadata->items ?? '[]', true);

                    $allSuccess = true;

                    foreach ($items as $item) {
                        $card = Card::where('api_id', $item['card_id'])->first();

                        if (! $card) {
                            Log::error('Card not found: '.$item['card_id']);

                            continue;
                        }

                        $exists = Order::where('api_id', $item['card_id'])
                            ->where('user_id', $userId)
                            ->where('product_type', Card::class)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        try {
                            $res = CardItem::where('api_category_id', $item['card_id'])
                                ->where('api_id', $item['id'])
                                ->where('status', true)
                                ->first();

                            if (! $res) {
                                throw new \Exception('Invalid card item');
                            }

                            $totalPrice = $res->unit_price * $item['quantity'];

                            // $seagmResponse = SeagmHelper::post('v1/card-orders', [
                            //     'type_id' => $item['id'],
                            //     'buy_amount' => $item['quantity'],
                            //     'mch_order_id' => $payment->transaction_id.'_'.$item['id'],
                            // ]);
                            // if (! isset($seagmResponse['data']['cards'])) {
                            //     throw new \Exception('SEAGM failed: '.json_encode($seagmResponse));
                            // }

                            // $seagmCards = $seagmResponse['data']['cards'];

                            // Create order
                            Order::create([
                                'user_id' => $userId,
                                'product_type' => Card::class,
                                'product_id' => $card->id,
                                'api_id' => $item['card_id'],
                                'quantity' => $item['quantity'],
                                'total_price' => $totalPrice,
                                'status' => 'completed',
                                // 'meta' => json_encode($seagmCards)
                            ]);

                            // foreach ($seagmCards as $seagmCard) {
                            //     UserCard::create([
                            //         'user_id' => $userId,
                            //         'api_id' => $seagmCard['id'],
                            //         'card_number' => $seagmCard['card_number'],
                            //         'card_pin' => $seagmCard['card_pin'],
                            //         'expired' => $seagmCard['expired_at'] ?? null,
                            //     ]);
                            // }

                        } catch (\Exception $e) {
                            $allSuccess = false;
                            Log::error('SEAGM Order Failed: '.$e->getMessage());
                        }
                    }

                    $payment->update([
                        'status' => $allSuccess ? 'paid' : 'failed',
                    ]);

                    if ($payment->status === 'paid') {

                        $year = date('Y');
                        $invoiceNumber = 'INV-'.$year.'-'.str_pad($payment->id, 3, '0', STR_PAD_LEFT);

                        $invoice = Invoice::create([
                            'user_id' => $userId,
                            'payment_id' => $payment->id,
                            'invoice_number' => $invoiceNumber,
                            'amount' => $payment->amount,
                            'status' => 'paid',
                        ]);

                       
                        foreach ($items as $item) {

                            $card = Card::where('api_id', $item['card_id'])->first();

                            if (! $card) {
                                continue;
                            }

                            $cardItem = CardItem::where('api_id', $item['id'])->first();

                            $price = $cardItem->unit_price ?? 0;
                            $quantity = $item['quantity'] ?? 1;

                            InvoiceItem::create([
                                'invoice_id' => $invoice->id,
                                'card_api_id' => $item['card_id'],
                                'name' => $card->name,
                                'quantity' => $quantity,
                                'price' => $price,
                                'total' => $price * $quantity,
                            ]);
                        }
                    }
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
