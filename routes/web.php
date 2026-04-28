<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

// ── Dev-only utility routes — only available when APP_ENV=local ──────────────
// WARNING: Never set APP_ENV=local on production. Run migrations via CLI only.
if (app()->environment('local')) {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('optimize:clear');
        return "Cache cleared!";
    });

    Route::get('/db-check', function () {
        try {
            DB::connection()->getPdo();
            return '✅ Database connection is working!';
        } catch (\Exception $e) {
            return '❌ DB Error: ' . $e->getMessage();
        }
    });
    // /run-migrations removed — running migrations via an unauthenticated HTTP
    // route is unsafe even in local mode. Use: php artisan migrate
}
// ─────────────────────────────────────────────────────────────────────────────


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::view('/about-us', 'pages.about')->name('about');
Route::get('/contact-us', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact-us', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::view('/page/faqs', 'pages.faq')->name('faqs');
Route::view('/page/size-guide', 'pages.size-guide')->name('size-guide');
Route::view('/page/privacy-policy', 'pages.privacy-policy')->name('privacy-policy');
Route::view('/page/terms-and-conditions', 'pages.terms-and-conditions')->name('terms-and-conditions');
Route::get('/page/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'index'])->name('data-deletion');
Route::post('/page/data-deletion', [App\Http\Controllers\DataDeletionController::class, 'submit'])->name('data-deletion.submit');

// Search Route
Route::get('/search', [App\Http\Controllers\ProductSearchController::class, 'index'])->name('search.index');

// Newsletter Routes
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe')->middleware('throttle:5,1');
Route::post('/newsletter/unsubscribe', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// Product Routes
Route::get('/product/{slug}', [App\Http\Controllers\ProductController::class, 'show'])->name('product.show');
Route::post('/product/variation', [App\Http\Controllers\ProductController::class, 'getVariation'])->name('product.variation');

// Cart Routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{item}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{item}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
Route::get('/cart/offcanvas', [App\Http\Controllers\CartController::class, 'offcanvas'])->name('cart.offcanvas');

// Checkout Routes — open to guests (auth optional)
Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');

// Order confirmation for guests (session-token verified, no auth required)
Route::get('/order/confirmation', [App\Http\Controllers\OrderController::class, 'guestConfirmation'])->name('order.guest-confirmation');

// Auth-protected order routes for logged-in users
Route::middleware(['auth'])->group(function () {
    Route::get('/order/success/{order}', [App\Http\Controllers\OrderController::class, 'success'])->name('order.success');
    Route::get('/order/tracking/{order}', [App\Http\Controllers\OrderController::class, 'tracking'])->name('order.tracking');
    Route::post('/order/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])->name('order.cancel');
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/failed', [App\Http\Controllers\PaymentController::class, 'failed'])->name('payment.failed');
});

// Razorpay Webhook (no auth required - verified by signature)
Route::post('/payment/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

// Support Ticket Routes
Route::get('/support/create', [App\Http\Controllers\SupportController::class, 'create'])->name('support.create');
Route::post('/support/store', [App\Http\Controllers\SupportController::class, 'store'])->name('support.store')->middleware('throttle:5,10');
Route::get('/support/success/{ticketNumber}', [App\Http\Controllers\SupportController::class, 'success'])->name('support.success');

Route::middleware(['auth'])->group(function () {
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticketNumber}', [App\Http\Controllers\SupportController::class, 'show'])->name('support.show');
    Route::post('/support/{ticketNumber}/reply', [App\Http\Controllers\SupportController::class, 'reply'])->name('support.reply')->middleware('throttle:10,1');
});

// Review Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/review/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('review.create');
    Route::post('/review/store', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    Route::get('/review/{id}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/{id}', [App\Http\Controllers\ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{id}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('review.destroy');
    Route::post('/review/{id}/helpful', [App\Http\Controllers\ReviewController::class, 'toggleHelpful'])->name('review.helpful');
});

// Public Track Order
Route::get('/track-order', [App\Http\Controllers\OrderController::class, 'showTrackLookup'])->name('order.track');
Route::post('/track-order', [App\Http\Controllers\OrderController::class, 'trackLookup'])->name('order.track.lookup');

// Social Auth Routes
Route::get('/auth/google', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/facebook', [App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [App\Http\Controllers\Auth\SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// Auth Routes
Route::middleware('throttle:10,1')->group(function () {
    // Login via OTP
    Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Auth\OtpController::class, 'sendLoginOtp'])->name('login.send-otp');
    Route::get('login/verify', [App\Http\Controllers\Auth\OtpController::class, 'showLoginVerify'])->name('login.verify');
    Route::post('login/verify', [App\Http\Controllers\Auth\OtpController::class, 'verifyLoginOtp'])->name('login.verify.submit');
    Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Register via OTP
    Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\OtpController::class, 'sendRegisterOtp'])->name('register.send-otp');
    Route::get('register/verify', [App\Http\Controllers\Auth\OtpController::class, 'showRegisterVerify'])->name('register.verify');
    Route::post('register/verify', [App\Http\Controllers\Auth\OtpController::class, 'verifyRegisterOtp'])->name('register.verify.submit');

    // Resend OTP
    Route::post('otp/resend', [App\Http\Controllers\Auth\OtpController::class, 'resendOtp'])->name('otp.resend');
});

// Rayaz Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/rayaz/callback', [App\Http\Controllers\RayazPaymentController::class, 'callback'])->name('payment.rayaz.callback');
    Route::post('/payment/rayaz/callback', [App\Http\Controllers\RayazPaymentController::class, 'callback']);
    Route::get('/payment/rayaz/cancel', [App\Http\Controllers\RayazPaymentController::class, 'cancel'])->name('payment.rayaz.cancel');
    Route::get('/payment/success/{order}', [App\Http\Controllers\RayazPaymentController::class, 'success'])
        ->name('payment.success')
        ->middleware(\App\Http\Middleware\EnsureOrderIsPaid::class);
    Route::get('/payment/failure', [App\Http\Controllers\RayazPaymentController::class, 'failure'])->name('payment.failure');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

    // Wishlist Routes
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{productId}', [App\Http\Controllers\WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Notification Routes
    Route::get('/notifications/{id}/read', [App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/read-all', [App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{id}', [App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('notifications.destroy');
});
