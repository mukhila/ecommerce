<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('menus')) {
                $mainMenus = \Modules\Admin\Models\Menu::where('type', 'main')
                    ->whereNull('parent_id')
                    ->with('children')
                    ->orderBy('sort_order')
                    ->get();

                $footerMenus = \Modules\Admin\Models\Menu::where('type', 'footer')
                    ->whereNull('parent_id')
                    ->with('children')
                    ->orderBy('sort_order')
                    ->get();

                \Illuminate\Support\Facades\View::share('mainMenus', $mainMenus);
                \Illuminate\Support\Facades\View::share('footerMenus', $footerMenus);
            }
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
        }

        // Register Cart View Composer
        \Illuminate\Support\Facades\View::composer('layouts.master', \App\Http\View\Composers\CartComposer::class);
    }
}
