<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ExclusiveOfferController;
use App\Http\Controllers\PaynowController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaypalWebhookController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SkrillController;
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
    Route::post('/set-password', 'completeSignup');
});

Route::get('/home', [HomeController::class, 'index']);
Route::get('/promotions/{id}', [PromotionController::class, 'promotionDetails']);
Route::get('/coupons/{id}', [CouponController::class, 'couponDetails']);
Route::get('/exclusive-offers/{id}', [ExclusiveOfferController::class, 'exclusiveOfferDetails']);

Route::post('/paynow/initiate', [PaynowController::class, 'initiate']);
Route::post('/paynow/callback', [PaynowController::class, 'callback']);
Route::get('/paynow/status', [PaynowController::class, 'status']);

// paypal payment
Route::post('paypal/payment', [PaypalController::class, 'payment'])->middleware('auth:sanctum');
Route::post('/paypal/webhook', [PaypalWebhookController::class, 'handle']);

Route::post('/stripe/checkout', [StripePaymentController::class, 'checkout'])->middleware('auth:sanctum');
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

// Skrill payment
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/payment/create', [SkrillController::class, 'create']);

    Route::controller(ReviewController::class)->group(function () {
        Route::post('/products/{product}/reviews', 'store');
    });
});


Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);
Route::post('/payment/ipn', [SkrillController::class, 'ipn']);
