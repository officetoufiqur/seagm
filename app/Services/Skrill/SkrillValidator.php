<?php

namespace App\Services\Skrill;

class SkrillValidator
{
    public function validate($request)
    {
        $merchant_id = config('skrill.merchant_id');
        $secret_word = config('skrill.secret_word');

        $local_hash = strtoupper(md5(
            $merchant_id .
            $request->transaction_id .
            strtoupper(md5($secret_word)) .
            $request->mb_amount .
            $request->mb_currency .
            $request->status
        ));

        return $local_hash === $request->md5sig && $request->status == 2;
    }
}