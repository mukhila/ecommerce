<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Seo;

class LoadSeoData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = Route::currentRouteName();

        if ($routeName) {
            $seo = Seo::where('route_name', $routeName)->first();

            if ($seo) {
                View::share('seo_data', $seo);
            }
        }

        return $next($request);
    }
}
