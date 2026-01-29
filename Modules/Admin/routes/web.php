<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\AnalyticsController;
use Modules\Admin\Http\Controllers\SearchController;
use Modules\Admin\Http\Controllers\ReportController;
use Modules\Admin\Http\Controllers\Auth\LoginController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\AttributeController;
use Modules\Admin\Http\Controllers\AttributeValueController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\CouponController;
use Modules\Admin\Http\Controllers\DiscountController;
use Modules\Admin\Http\Controllers\SliderController;
use Modules\Admin\Http\Controllers\MenuController;
use Modules\Admin\Http\Controllers\OrderController;
use Modules\Admin\Http\Controllers\SupportController;
use Modules\Admin\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('login.submit');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');

        // Change Password
        Route::get('/change-password', [AdminController::class, 'changePassword'])->name('password.change');
        Route::post('/change-password', [AdminController::class, 'updatePassword'])->name('password.update');

        // Analytics Routes (Admin & Staff)
        Route::middleware('admin.role:admin,staff')->group(function () {
            Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
            Route::get('analytics/revenue', [AnalyticsController::class, 'revenue'])->name('analytics.revenue');
            Route::get('analytics/sales', [AnalyticsController::class, 'sales'])->name('analytics.sales');
            Route::get('analytics/chart-data', [AnalyticsController::class, 'chartData'])->name('analytics.chart-data');
        });

        // Search Routes (Admin & Staff)
        Route::middleware('admin.role:admin,staff')->group(function () {
            Route::get('search/global', [SearchController::class, 'global'])->name('search.global');
            Route::get('search/orders', [SearchController::class, 'orders'])->name('search.orders');
            Route::get('search/products', [SearchController::class, 'products'])->name('search.products');
            Route::get('search/customers', [SearchController::class, 'customers'])->name('search.customers');
            Route::get('search/tickets', [SearchController::class, 'tickets'])->name('search.tickets');
            Route::get('search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
        });

        // Report Routes (Admin Only)
        Route::middleware('admin.role:admin')->group(function () {
            Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
            Route::get('reports/revenue/pdf', [ReportController::class, 'revenuePdf'])->name('reports.revenue.pdf');
            Route::get('reports/sales/pdf', [ReportController::class, 'salesPdf'])->name('reports.sales.pdf');
            Route::get('reports/customer/pdf', [ReportController::class, 'customerPdf'])->name('reports.customer.pdf');
            Route::get('reports/order/pdf', [ReportController::class, 'orderPdf'])->name('reports.order.pdf');
            Route::get('reports/orders/excel', [ReportController::class, 'ordersExcel'])->name('reports.orders.excel');
            Route::get('reports/products/excel', [ReportController::class, 'productsExcel'])->name('reports.products.excel');
            Route::get('reports/customers/excel', [ReportController::class, 'customersExcel'])->name('reports.customers.excel');
        });

        // Category Routes
        Route::resource('categories', CategoryController::class);

        // Attribute Routes
        Route::resource('attributes', AttributeController::class);

        // Attribute Value Routes
        Route::resource('attribute_values', AttributeValueController::class);

        // Product Routes
        Route::resource('products', ProductController::class);
        Route::get('products/image/{id}/delete', [ProductController::class, 'destroyImage'])->name('products.image.destroy');

        // Coupon Routes
        Route::resource('coupons', CouponController::class);

        // Discount Routes
        Route::resource('discounts', DiscountController::class);

        // Slider Routes
        Route::resource('sliders', SliderController::class);

        // Menu Routes
        Route::resource('menus', MenuController::class);

        // Order Routes
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/update-payment', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
        Route::post('orders/{order}/update-tracking', [OrderController::class, 'updateTracking'])->name('orders.update-tracking');

        // Support Ticket Routes
        Route::get('support', [SupportController::class, 'index'])->name('support.index');
        Route::get('support/{ticketNumber}', [SupportController::class, 'show'])->name('support.show');
        Route::post('support/{ticket}/status', [SupportController::class, 'updateStatus'])->name('support.update-status');
        Route::post('support/{ticket}/assign', [SupportController::class, 'assign'])->name('support.assign');
        Route::post('support/{ticket}/reply', [SupportController::class, 'reply'])->name('support.reply');
        Route::post('support/bulk-update', [SupportController::class, 'bulkUpdate'])->name('support.bulk-update');

        // Customer Routes
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

        // Review Routes
        Route::get('reviews', [\Modules\Admin\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('reviews/{id}', [\Modules\Admin\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
        Route::post('reviews/{id}/approve', [\Modules\Admin\Http\Controllers\ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{id}/reject', [\Modules\Admin\Http\Controllers\ReviewController::class, 'reject'])->name('reviews.reject');
        Route::post('reviews/{id}/status', [\Modules\Admin\Http\Controllers\ReviewController::class, 'updateStatus'])->name('reviews.update-status');
        Route::post('reviews/{id}/reply', [\Modules\Admin\Http\Controllers\ReviewController::class, 'reply'])->name('reviews.reply');
        Route::delete('reviews/{id}', [\Modules\Admin\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::post('reviews/bulk-action', [\Modules\Admin\Http\Controllers\ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');

        // Company Settings Routes
        Route::get('company-settings', [Modules\Admin\Http\Controllers\CompanySettingController::class, 'edit'])->name('company_settings.edit');
        Route::post('company-settings', [Modules\Admin\Http\Controllers\CompanySettingController::class, 'update'])->name('company_settings.update');

        // Page Routes
        Route::resource('pages', Modules\Admin\Http\Controllers\PageController::class);
    });
});
