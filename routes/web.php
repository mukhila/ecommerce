<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/order/success/{order}', [App\Http\Controllers\OrderController::class, 'success'])->name('order.success');
    Route::get('/order/tracking/{order}', [App\Http\Controllers\OrderController::class, 'tracking'])->name('order.tracking');
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('payment.callback');
    Route::get('/payment/failed', [App\Http\Controllers\PaymentController::class, 'failed'])->name('payment.failed');
});

// Razorpay Webhook (no auth required)
Route::post('/payment/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

// Support Ticket Routes
Route::get('/support/create', [App\Http\Controllers\SupportController::class, 'create'])->name('support.create');
Route::post('/support/store', [App\Http\Controllers\SupportController::class, 'store'])->name('support.store');
Route::get('/support/success/{ticketNumber}', [App\Http\Controllers\SupportController::class, 'success'])->name('support.success');

Route::middleware(['auth'])->group(function () {
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{ticketNumber}', [App\Http\Controllers\SupportController::class, 'show'])->name('support.show');
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

// Admin routes are now handled by the Admin Module

// Auth Routes
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
});
