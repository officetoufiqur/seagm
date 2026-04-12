<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Api\CardApiController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DirectTopUpApiController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\NewsApiController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\NewsLetterController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ExclusiveOfferController;
use App\Http\Controllers\HitPayController;
use App\Http\Controllers\PaynowController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaypalWebhookController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SkrillController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\TopUpReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthenticationController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/mobile-login', 'mobileLogin');

    Route::post('/send-email-otp', 'sendEmailOtp');
    Route::post('/verify-email-otp', 'verifyEmailOtp');

    Route::post('/send-mobile-otp', 'sendMobileOtp');
    Route::post('/verify-mobile-otp', 'verifyMobileOtp');

    Route::post('/verify-email', 'verifyEmail');
    Route::post('/verify-mobile', 'verifyMobile');

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
        Route::post('/card-items/{id}/reviews', 'store');
    });

    Route::controller(TopUpReviewController::class)->group(function () {
        Route::post('/direct-top-up/{id}/reviews', 'store');
    });

    Route::post('/hitpay/payment', [HitPayController::class, 'hitpay']);

    Route::controller(UserProfileController::class)->group(function () {
        Route::get('/profile', 'show');
        Route::post('/profile/image', 'imageUpdate');
        Route::post('/profile/username', 'usernameUpdate');
        Route::post('/change-password/otp', 'changePasswordOtp');
        Route::post('/profile/change-password', 'changePassword');
        Route::get('/profile/billing_addresses', 'getBillingAddress');
        Route::post('/profile/billing_addresses', 'addBillingAddress');
        Route::delete('/profile/delete', 'deleteAccount');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard/overview', 'overview');
        Route::get('/my-orders', 'myOrders');
        Route::get('/my-cards', 'mycards');
        Route::post('/invoices/download', 'downloadInvoice');
    });
    Route::controller(FavoriteController::class)->group(function () {
        Route::post('/favorites', 'store');
        Route::get('/favorites', 'index');
    });

    Route::get('/invoices', [DashboardController::class, 'invoices']);

    Route::controller(SupportController::class)->group(function () {
        Route::get('/supports', 'index');
        Route::post('/supports', 'store');
        Route::get('/supports/{id}', 'show');
        Route::post('/supports/{id}/reply', 'replyStore');
    });

    Route::controller(CartController::class)->group(function () {
        Route::get('/carts', 'index');
        Route::post('/carts/{id}', 'store');
    });
});

// Route::get('/invoices/download', [DashboardController::class, 'downloadInvoice']);

Route::post('/hitpay/webhook', [HitPayController::class, 'webhook'])->name('hitpay.webhook');

Route::get('/card-items/{id}/reviews', [ReviewController::class, 'index']);
Route::get('/direct-top-up/{id}/reviews', [TopUpReviewController::class, 'index']);
Route::post('/payment/ipn', [SkrillController::class, 'ipn']);

Route::get('/cards', [CardApiController::class, 'index']);
Route::get('/cards/{id}', [CardApiController::class, 'show']);

Route::get('/direct-top-up', [DirectTopUpApiController::class, 'index']);
Route::get('/direct-top-up/{id}', [DirectTopUpApiController::class, 'show']);
Route::get('/top-up/all-reviews/{id}', [DirectTopUpApiController::class, 'allReviews']);

Route::get('/terms', [TermsController::class, 'terms']);

Route::controller(NewsController::class)->group(function () {
    Route::get('/news', 'index');
    Route::get('/news/{id}', 'show');
});

Route::controller(NewsApiController::class)->group(function () {
    Route::get('/home-page/news', 'newsHome');
    Route::get('/news/latest', 'latestNews');
    Route::get('/gaming/news/category', 'gamingNewsByCategory');
    Route::get('/guide/category', 'guideCategory');
    Route::get('/news/category/details', 'newsCategoryDetails');
});

Route::post('/newsletter', [NewsLetterController::class, 'store']);
Route::get('/about-us', [AboutUsController::class, 'show']);
Route::get('/platform', [PlatformController::class, 'show']);
Route::get('/careers', [CareerController::class, 'show']);
