<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\CardItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Payment;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HitPayController extends Controller
{
    use ApiResponse;

    public function hitpayCheckout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.card_id' => 'required',
            'items.*.id' => 'required',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $items = [];
        $total = 0;
        $currency = null;

        foreach ($request->items as $item) {

            $res = CardItem::where('api_category_id', $item['card_id'])
                ->where('api_id', $item['id'])
                ->where('status', true)
                ->first();

            if (! $res || $res->unit_price <= 0) {
                return response()->json(['error' => 'Invalid card'], 400);
            }

            // Currency check
            if (! $currency) {
                $currency = strtoupper($res['currency']);
            } elseif ($currency !== strtoupper($res['currency'])) {
                return response()->json([
                    'error' => 'All items must have same currency',
                ], 400);
            }

            $quantity = $item['quantity'];
            $subtotal = $res['unit_price'] * $quantity;

            $total += $subtotal;

            $items[] = [
                'name' => $res['name'],
                'card_id' => $item['card_id'],
                'api_id' => $item['id'],
                'price' => $res['unit_price'],
                'quantity' => $quantity,
            ];
        }

        $methods = ['paynow_online'];

        $data = [
            'amount' => $total,
            'currency' => 'SGD',
            'payment_methods' => $methods,
            'email' => Auth::user()->email ?? 'customer@mail.com',
            'redirect_url' => route('hitpay.success'),
            'webhook' => route('hitpay.webhook'),
            // 'webhook' => 'https://nonpluralistic-princeton-subaqueous.ngrok-free.dev/api/hitpay/webhook',
        ];

        $url = config('services.hitpay.url').'/payment-requests';

        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => config('services.hitpay.key'),
        ])->post($url, $data);

        $response = $response->json();

        $itemsData = [
            'items' => $items,
            'total' => $total,
            'type' => 'card',
        ];

        Payment::create([
            'transaction_id' => $response['id'],
            'user_id' => Auth::id(),
            'amount' => $total,
            'currency' => $currency,
            'payment_method' => 'hitpay',
            'status' => 'pending',
            'items' => $itemsData,
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
            'payload' => $payload,
            'signature' => $signature,
        ]);

        $salt = config('services.hitpay.salt');
        $computedSignature = hash_hmac('sha256', $payload, $salt);

        if (! hash_equals($computedSignature, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);

        DB::transaction(function () use ($data) {

            $payment = Payment::where('transaction_id', $data['id'])
                ->lockForUpdate()
                ->first();

            if (! $payment) {
                throw new \Exception('Payment not found');
            }

            if ($payment->payment_status === 'paid') {
                return;
            }

            if ((float) $payment->amount !== (float) $data['amount']) {
                Log::error('HitPay amount mismatch', [
                    'db' => $payment->amount,
                    'hitpay' => $data['amount'],
                ]);

                return;
            }

            if ($data['status'] !== 'completed') {
                $payment->update(['payment_status' => 'failed']);

                return;
            }

            $itemsData = is_array($payment->items)
                ? $payment->items
                : json_decode($payment->items ?? '[]', true) ?? [];

            $items = $itemsData['items'] ?? [];
            $userId = $payment->user_id;

            $allSuccess = true;

            foreach ($items as $item) {

                $card = Card::where('api_id', $item['card_id'])->first();

                if (! $card) {
                    Log::error('Card not found: '.$item['card_id']);

                    continue;
                }

                $exists = Order::where('api_id', $item['card_id'])
                    ->where('user_id', $userId)
                    ->exists();

                if ($exists) {
                    continue;
                }

                try {
                    $cardItem = CardItem::where('api_category_id', $item['card_id'])
                        ->where('api_id', $item['api_id'])
                        ->where('status', true)
                        ->first();

                    if (! $cardItem) {
                        throw new \Exception('Invalid card item');
                    }

                    $totalPrice = $cardItem->unit_price * $item['quantity'];

                    Order::create([
                        'user_id' => $userId,
                        'product_type' => Card::class,
                        'product_id' => $card->id,
                        'api_id' => $item['card_id'],
                        'quantity' => $item['quantity'],
                        'total_price' => $totalPrice,
                        'status' => 'completed',
                    ]);

                } catch (\Exception $e) {
                    $allSuccess = false;
                    Log::error('Order Failed: '.$e->getMessage());
                }
            }

            // Update payment
            $payment->update([
                'payment_status' => $allSuccess ? 'paid' : 'failed',
            ]);

            // Create invoice
            if ($payment->payment_status === 'paid') {

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

                    $cardItem = CardItem::where('api_id', $item['api_id'])->first();

                    $price = $cardItem->unit_price ?? 0;
                    $qty = $item['quantity'] ?? 1;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'card_api_id' => $item['card_id'],
                        'name' => $card->name,
                        'quantity' => $qty,
                        'price' => $price,
                        'total' => $price * $qty,
                    ]);
                }
            }
        });

        return response()->json(['message' => 'Webhook processed']);
    }
}
