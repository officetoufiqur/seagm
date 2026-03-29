<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\Skrill\SkrillService;
use App\Services\Skrill\SkrillValidator;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkrillController extends Controller
{
    use ApiResponse;

    public function create(Request $request, SkrillService $skrill)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $payment = Payment::create([
            'transaction_id' => uniqid(),
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'currency' => config('skrill.currency'),
            'payment_method' => 'Skrill',
            'payment_status' => 'pending',
        ]);

        $data = $skrill->createPayment($payment);

        return $this->successResponse($data, 'Payment successful', 200);
    }

    public function ipn(Request $request, SkrillValidator $validator)
    {
        if ($validator->validate($request)) {

            $payment = Payment::where('transaction_id', $request->transaction_id)->first();

            if ($payment && $payment->payment_status !== 'paid') {
                $payment->update(['payment_status' => 'paid']);
            }
        } else {
            return $this->errorResponse([], 'Payment failed', 400);
        }

        return $this->successResponse([], 'Payment successful', 200);
    }

    public function success()
    {
        return response()->json(['message' => 'Payment Success']);
    }

    public function cancel()
    {
        return response()->json(['message' => 'Payment Cancelled']);
    }
}
