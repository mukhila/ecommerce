<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AdminAuthorizationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Admin role bypasses all permission checks
        Gate::before(function ($admin, $ability) {
            if ($admin->role === 'admin') {
                return true; // Admins can do everything
            }
        });

        // Define Gates for admin permissions
        Gate::define('view_analytics', function ($admin) {
            return $admin->role === 'admin' || $admin->hasPermission('view_analytics');
        });

        Gate::define('view_financial_reports', function ($admin) {
            return $admin->role === 'admin';
        });

        Gate::define('view_customer_analytics', function ($admin) {
            return $admin->role === 'admin';
        });

        Gate::define('export_reports', function ($admin) {
            return $admin->role === 'admin' || $admin->hasPermission('export_reports');
        });

        Gate::define('manage_products', function ($admin) {
            return $admin->role === 'admin';
        });

        Gate::define('view_products', function ($admin) {
            return true; // All authenticated admins can view
        });

        Gate::define('delete_orders', function ($admin) {
            return $admin->role === 'admin';
        });

        Gate::define('manage_orders', function ($admin) {
            return $admin->role === 'admin' || $admin->hasPermission('manage_orders');
        });

        Gate::define('update_order_status', function ($admin) {
            return $admin->role === 'admin' || $admin->hasPermission('update_order_status');
        });

        Gate::define('manage_admins', function ($admin) {
            return $admin->role === 'admin';
        });

        Gate::define('manage_settings', function ($admin) {
            return $admin->role === 'admin';
        });
    }
}
