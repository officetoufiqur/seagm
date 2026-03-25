<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PromotionController;
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
    
});

require __DIR__.'/settings.php';
require __DIR__.'/command.php';
