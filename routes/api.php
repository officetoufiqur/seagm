<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\StripeWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/login', 'login');

    Route::post('/send-email-otp', 'sendEmailOtp');
    Route::post('/verify-email-otp', 'verifyEmailOtp');

    Route::post('/send-mobile-otp', 'sendMobileOtp');
    Route::post('/verify-mobile-otp', 'verifyMobileOtp');
});

// paypal payment
Route::get('paypal/payment', [PaypalController::class, 'payment'])->middleware('auth:sanctum');


Route::post('/stripe/checkout', [StripePaymentController::class, 'checkout'])->middleware('auth:sanctum');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
