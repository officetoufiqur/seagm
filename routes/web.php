<?php

use App\Http\Controllers\AboutContactController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AdvantageController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MobileRechargeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CardItemController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DirectTopUpController;
use App\Http\Controllers\EmployeeBenefitController;
use App\Http\Controllers\ExclusiveOfferController;
use App\Http\Controllers\HitPayController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JoinUsController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\NewsCategoryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsVideoController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SkrillController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\ThroughController;
use App\Http\Controllers\UserGuideCategoryController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/register', function () {
    return redirect('/login');
});

Route::get('/dump-autoload', function () {
    exec('composer dump-autoload');

    return 'Composer autoload dumped!';
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    return 'Cache cleared successfully!';
});

Route::get('/run-schedule', function () {
    Artisan::call('send:expires-reminder');
    Artisan::call('send:check-medical-proof');
    Artisan::call('app:task-overdue');
    Artisan::call('company:renewal-status');
    Artisan::call('app:installment');

    return 'Schedule run successfully!';
});

Route::get('/storage-link', function () {
    $target = storage_path('app/public');
    $link = '/home/dwbcdckj/damac.dwbc-bh.com/storage';

    if (file_exists($link)) {
        return 'Symlink already exists.';
    }

    symlink($target, $link);

    return 'Symlink created successfully.';
});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('paypal/payment/success', [PaypalController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PaypalController::class, 'paymentCancel'])->name('paypal.payment.cancel');

Route::get('stripe/payment/success', [StripePaymentController::class, 'success'])->name('stripe.payment.success');

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

    Route::controller(CardController::class)->group(function () {
        Route::get('/card', 'index')->name('card.index');
        Route::get('/card/edit/{category}', 'edit')->name('card.edit');
        Route::post('/card/update/{category}', 'update')->name('card.update');
        Route::delete('/card/{category}', 'destroy')->name('card.destroy');
    });

    Route::controller(CardItemController::class)->group(function () {
        Route::get('/card-items', 'index')->name('card-items.index');
        Route::get('/card-items/edit/{id}', 'edit')->name('card-items.edit');
        Route::post('/card-items/update/{id}', 'update')->name('card-items.update');
        Route::delete('/card-items/{id}', 'destroy')->name('card-items.destroy');
    });

    Route::controller(TermsController::class)->group(function () {
        Route::get('/terms', 'index')->name('terms.index');
        Route::get('/terms/create', 'create')->name('terms.create');
        Route::post('/terms/store', 'store')->name('terms.store');
        Route::get('/terms/edit/{id}', 'edit')->name('terms.edit');
        Route::post('/terms/update/{id}', 'update')->name('terms.update');
        Route::delete('/terms/{id}', 'destroy')->name('terms.destroy');
    });

    Route::controller(DirectTopUpController::class)->group(function () {
        Route::get('/direct-top-up', 'index')->name('direct-top-up.index');
        Route::get('/direct-top-up/edit/{id}', 'edit')->name('direct-top-up.edit');
        Route::post('/direct-top-up/update/{id}', 'update')->name('direct-top-up.update');
        Route::delete('/direct-top-up/{id}', 'destroy')->name('direct-top-up.destroy');
    });

    Route::controller(MobileRechargeController::class)->group(function () {
        Route::get('/mobile-recharge', 'index')->name('mobile-recharge.index');
    });

    Route::controller(NewsVideoController::class)->group(function () {
        Route::get('/news-videos', 'index')->name('news-videos.index');
        Route::get('/news-videos/create', 'create')->name('news-videos.create');
        Route::post('/news-videos/store', 'store')->name('news-videos.store');
        Route::get('/news-videos/edit/{id}', 'edit')->name('news-videos.edit');
        Route::post('/news-videos/update/{id}', 'update')->name('news-videos.update');
        Route::delete('/news-videos/{id}', 'destroy')->name('news-videos.destroy');
    });

    Route::controller(AboutUsController::class)->group(function () {
        Route::get('/about-us', 'index')->name('about-us.index');
        Route::get('/about-us/edit/{id}', 'edit')->name('about-us.edit');
        Route::post('/about-us/update/{id}', 'update')->name('about-us.update');

        Route::get('/vision', 'vision')->name('vision.index');
        Route::get('/vision/edit/{id}', 'visionEdit')->name('vision.edit');
        Route::post('/vision/update/{id}', 'visionUpdate')->name('vision.update');

        Route::get('/departments', 'departments')->name('departments.index');
        Route::get('/departments/edit/{id}', 'departmentsEdit')->name('departments.edit');
        Route::post('/departments/update/{id}', 'departmentsUpdate')->name('departments.update');
    });

    Route::controller(PlatformController::class)->group(function () {
        Route::get('/platform', 'index')->name('platform.index');
        Route::get('/platform/edit/{id}', 'edit')->name('platform.edit');
        Route::post('/platform/update/{id}', 'update')->name('platform.update');
    });

     Route::controller(CareerController::class)->group(function () {
        Route::get('/careers', 'index')->name('careers.index');
        Route::get('/careers/edit/{id}', 'edit')->name('careers.edit');
        Route::post('/careers/update/{id}', 'update')->name('careers.update');
    });

    Route::controller(EmployeeBenefitController::class)->group(function () {
        Route::get('/employee-benefits', 'index')->name('employee-benefits.index');
        Route::get('/employee-benefits/create', 'create')->name('employee-benefits.create');
        Route::post('/employee-benefits/store', 'store')->name('employee-benefits.store');
        Route::get('/employee-benefits/edit/{id}', 'edit')->name('employee-benefits.edit');
        Route::post('/employee-benefits/update/{id}', 'update')->name('employee-benefits.update');
        Route::delete('/employee-benefits/destroy/{id}', 'destroy')->name('employee-benefits.destroy');
    });

    Route::controller(JoinUsController::class)->group(function () {
        Route::get('/join-us', 'index')->name('join-us.index');
        Route::get('/join-us/create', 'create')->name('join-us.create');
        Route::post('/join-us/store', 'store')->name('join-us.store');
        Route::get('/join-us/edit/{id}', 'edit')->name('join-us.edit');
        Route::post('/join-us/update/{id}', 'update')->name('join-us.update');
        Route::delete('/join-us/destroy/{id}', 'destroy')->name('join-us.destroy');
    });

     Route::controller(AboutContactController::class)->group(function () {
        Route::get('/about-contact', 'index')->name('about-contact.index');
        Route::get('/about-contact/edit/{id}', 'edit')->name('about-contact.edit');
        Route::post('/about-contact/update/{id}', 'update')->name('about-contact.update');
    });

    Route::controller(SocialController::class)->group(function () {
        Route::get('/socials', 'index')->name('socials.index');
        Route::get('/socials/create', 'create')->name('socials.create');
        Route::post('/socials/store', 'store')->name('socials.store');
        Route::get('/socials/edit/{id}', 'edit')->name('socials.edit');
        Route::post('/socials/update/{id}', 'update')->name('socials.update');
        Route::delete('/socials/destroy/{id}', 'destroy')->name('socials.destroy');
    });

     Route::controller(HomeController::class)->group(function () {
        Route::get('/home-hero', 'index')->name('home-hero.index');
        Route::get('/home-hero/edit/{id}', 'edit')->name('home-hero.edit');
        Route::post('/home-hero/update/{id}', 'update')->name('home-hero.update');
    });

    Route::controller(HomeController::class)->group(function () {
        Route::get('/home-page', 'page')->name('home-page.index');
        Route::get('/home-page/edit/{id}', 'pageEdit')->name('home-page.edit');
        Route::post('/home-page/update/{id}', 'pageUpdate')->name('home-page.update');
    });

    Route::controller(CmsController::class)->group(function () {
        Route::get('/about-cms', 'index')->name('about-cms.index');
        Route::get('/about-cms/create', 'create')->name('about-cms.create');
        Route::post('/about-cms/store', 'store')->name('about-cms.store');
        Route::get('/about-cms/edit/{id}', 'edit')->name('about-cms.edit');
        Route::post('/about-cms/update/{id}', 'update')->name('about-cms.update');
        Route::delete('/about-cms/destroy/{id}', 'destroy')->name('about-cms.destroy');
    });


    Route::controller(AdvantageController::class)->group(function () {
        Route::get('/advantage', 'index')->name('advantage.index');
        Route::get('/advantage/create', 'create')->name('advantage.create');
        Route::post('/advantage/store', 'store')->name('advantage.store');
        Route::get('/advantage/edit/{id}', 'edit')->name('advantage.edit');
        Route::post('/advantage/update/{id}', 'update')->name('advantage.update');
        Route::delete('/advantage/destroy/{id}', 'destroy')->name('advantage.destroy');

        Route::get('/advantage-card', 'cardEdit')->name('advantage.card');
        Route::post('/advantage-card', 'cardUpdate')->name('advantage.card.update');
    });

    Route::controller(BrandController::class)->group(function () {
        Route::get('/brands', 'index')->name('brands.index');
        Route::get('/brands/create', 'create')->name('brands.create');
        Route::post('/brands/store', 'store')->name('brands.store');
        Route::get('/brands/edit/{id}', 'edit')->name('brands.edit');
        Route::post('/brands/update/{id}', 'update')->name('brands.update');
        Route::delete('/brands/destroy/{id}', 'destroy')->name('brands.destroy');
    });

    Route::controller(ThroughController::class)->group(function () {
        Route::get('/through', 'index')->name('through.index');
        Route::get('/through/create', 'create')->name('through.create');
        Route::post('/through/store', 'store')->name('through.store');
        Route::get('/through/edit/{id}', 'edit')->name('through.edit');
        Route::post('/through/update/{id}', 'update')->name('through.update');
        Route::delete('/through/destroy/{id}', 'destroy')->name('through.destroy');
    });

    Route::controller(MilestoneController::class)->group(function () {
        Route::get('/milestones', 'index')->name('milestones.index');
        Route::get('/milestones/create', 'create')->name('milestones.create');
        Route::post('/milestones/store', 'store')->name('milestones.store');
        Route::get('/milestones/edit/{id}', 'edit')->name('milestones.edit');
        Route::post('/milestones/update/{id}', 'update')->name('milestones.update');
        Route::delete('/milestones/destroy/{id}', 'destroy')->name('milestones.destroy');
    });

    Route::controller(UserGuideCategoryController::class)->group(function () {
        Route::get('/user-guide-categories', 'index')->name('user-guide-categories.index');
        Route::get('/user-guide-categories/create', 'create')->name('user-guide-categories.create');
        Route::post('/user-guide-categories/store', 'store')->name('user-guide-categories.store');
        Route::get('/user-guide-categories/edit/{id}', 'edit')->name('user-guide-categories.edit');
        Route::post('/user-guide-categories/update/{id}', 'update')->name('user-guide-categories.update');
        Route::delete('/user-guide-categories/destroy/{id}', 'destroy')->name('user-guide-categories.destroy');
    });

    Route::controller(SubCategoryController::class)->group(function () {
        Route::get('/sub-categories', 'index')->name('sub-categories.index');
        Route::get('/sub-categories/create', 'create')->name('sub-categories.create');
        Route::post('/sub-categories/store', 'store')->name('sub-categories.store');
        Route::get('/sub-categories/edit/{id}', 'edit')->name('sub-categories.edit');
        Route::post('/sub-categories/update/{id}', 'update')->name('sub-categories.update');
        Route::delete('/sub-categories/destroy/{id}', 'destroy')->name('sub-categories.destroy');
    });

    Route::controller(ArticleController::class)->group(function () {
        Route::get('/articles', 'index')->name('articles.index');
        Route::get('/articles/create', 'create')->name('articles.create');
        Route::post('/articles/store', 'store')->name('articles.store');
        Route::get('/articles/edit/{id}', 'edit')->name('articles.edit');
        Route::post('/articles/update/{id}', 'update')->name('articles.update');
        Route::delete('/articles/destroy/{id}', 'destroy')->name('articles.destroy');
        Route::post('/articles/promoted/{id}', 'promoted')->name('articles.promoted');
    });

});

Route::get('/invoices/{invoice}/download', [DashboardController::class, 'downloadInvoice']);

require __DIR__.'/settings.php';
require __DIR__.'/command.php';
