<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

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
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Warn loudly when mail credentials are still placeholder values
        $mailUser = config('mail.mailers.smtp.username', '');
        if (str_contains((string) $mailUser, 'your-email') || str_contains((string) $mailUser, 'example.com')) {
            Log::warning('MAIL NOT CONFIGURED: MAIL_USERNAME is still a placeholder. ' .
                'No emails will be delivered. Update .env with real SMTP credentials ' .
                'or set MAIL_MAILER=log for local testing.');
        }

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

            if (\Illuminate\Support\Facades\Schema::hasTable('company_settings')) {
                $companySetting = \Modules\Admin\Models\CompanySetting::first();
                \Illuminate\Support\Facades\View::share('companySetting', $companySetting);
            }
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
        }

        // Register Cart View Composer
        \Illuminate\Support\Facades\View::composer('layouts.master', \App\Http\View\Composers\CartComposer::class);
    }
}
