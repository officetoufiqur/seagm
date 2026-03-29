<?php

namespace App\Services\Skrill;

class SkrillService
{
    public function createPayment($payment)
    {
        return [
            'pay_to_email' => config('skrill.merchant_email'),
            'transaction_id' => $payment->transaction_id,
            'amount' => $payment->amount,
            'currency' => config('skrill.currency'),
            'return_url' => route('skrill.payment.success'),
            'cancel_url' => route('skrill.payment.cancel'),
            'status_url' => url('/api/payment/ipn'),
            'detail1_description' => 'Payment',
            'detail1_text' => 'Order #' . $payment->transaction_id
        ];
    }
}