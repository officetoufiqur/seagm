<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CardCategoryController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ExclusiveOfferController;
use App\Http\Controllers\HitPayController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SkrillController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('paypal/payment/success', [PaypalController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PaypalController::class, 'paymentCancel'])->name('paypal.payment.cancel');

Route::get('/skrill/payment/success', [SkrillController::class, 'success'])->name('skrill.payment.success');
Route::get('/skrill/payment/cancel', [SkrillController::class, 'cancel'])->name('skrill.payment.cancel');

Route::get('/hitpay/success', [HitPayController::class, 'hitpaySuccess'])->name('hitpay.success');



Route::middleware('auth')->group(function () {
    Route::get('ckeditor', [CKEditorController::class, 'index']);

    Route::controller(BannerController::class)->group(function () {
        Route::get('/banners', 'index')->name('banners.index');
        Route::get('/banners/create', 'create')->name('banners.create');
        Route::post('/banners/store', 'store')->name('banners.store');
        Route::get('/banners/edit/{banner}', 'edit')->name('banners.edit');
        Route::post('/banners/{banner}', 'update')->name('banners.update');
        Route::post('/banners/status/{banner}', 'status')->name('banners.status');
        Route::delete('/banners/{banner}', 'destroy')->name('banners.destroy');
    });

    Route::controller(PromotionController::class)->group(function () {
        Route::get('/promotions', 'index')->name('promotions.index');
        Route::get('/promotions/create', 'create')->name('promotions.create');
        Route::post('/promotions/store', 'store')->name('promotions.store');
        Route::get('/promotions/edit/{promotion}', 'edit')->name('promotions.edit');
        Route::post('/promotions/{promotion}', 'update')->name('promotions.update');
        Route::post('/promotions/status/{promotion}', 'status')->name('promotions.status');
        Route::delete('/promotions/{promotion}', 'destroy')->name('promotions.destroy');
    });

    Route::controller(CouponController::class)->group(function () {
        Route::get('/coupons', 'index')->name('coupons.index');
        Route::get('/coupons/create', 'create')->name('coupons.create');
        Route::post('/coupons/store', 'store')->name('coupons.store');
        Route::get('/coupons/edit/{coupon}', 'edit')->name('coupons.edit');
        Route::post('/coupons/update/{coupon}', 'update')->name('coupons.update');
        Route::post('/coupons/status/{coupon}', 'status')->name('coupons.status');
        Route::delete('/coupons/{coupon}', 'destroy')->name('coupons.destroy');
    });

    Route::controller(NewsCategoryController::class)->group(function () {
        Route::get('/news-categories', 'index')->name('news-categories.index');
        Route::get('/news-categories/create', 'create')->name('news-categories.create');
        Route::post('/news-categories/store', 'store')->name('news-categories.store');
        Route::get('/news-categories/edit/{category}', 'edit')->name('news-categories.edit');
        Route::post('/news-categories/update/{category}', 'update')->name('news-categories.update');
        Route::post('/news-categories/status/{category}', 'status')->name('news-categories.status');
        Route::delete('/news-categories/{category}', 'destroy')->name('news-categories.destroy');
    });

    Route::controller(NewsController::class)->group(function () {
        Route::get('/news', 'index')->name('news.index');
        Route::get('/news/create', 'create')->name('news.create');
        Route::post('/news/store', 'store')->name('news.store');
        Route::get('/news/edit/{news}', 'edit')->name('news.edit');
        Route::post('/news/update/{news}', 'update')->name('news.update');
        Route::post('/news/status/{news}', 'status')->name('news.status');
        Route::delete('/news/{news}', 'destroy')->name('news.destroy');
    });

    Route::controller(ExclusiveOfferController::class)->group(function () {
       Route::get('/exclusive-offers', 'index')->name('exclusive-offers.index');
       Route::get('/exclusive-offers/create', 'create')->name('exclusive-offers.create');
       Route::post('/exclusive-offers/store', 'store')->name('exclusive-offers.store');
       Route::get('/exclusive-offers/edit/{offer}', 'edit')->name('exclusive-offers.edit');
       Route::post('/exclusive-offers/update/{offer}', 'update')->name('exclusive-offers.update');
       Route::post('/exclusive-offers/status/{offer}', 'status')->name('exclusive-offers.status');
       Route::delete('/exclusive-offers/{offer}', 'destroy')->name('exclusive-offers.destroy'); 
    });

    Route::controller(CardCategoryController::class)->group(function () {
        Route::get('/card-categories', 'index')->name('card-categories.index');
        Route::get('/card-categories/edit/{category}', 'edit')->name('card-categories.edit');
        Route::post('/card-categories/update/{category}', 'update')->name('card-categories.update');
        Route::delete('/card-categories/{category}', 'destroy')->name('card-categories.destroy');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products.index');
        Route::get('/products/edit/{product}', 'edit')->name('products.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::delete('/products/{product}', 'destroy')->name('products.destroy');
    });
    
});

require __DIR__.'/settings.php';
require __DIR__.'/command.php';
