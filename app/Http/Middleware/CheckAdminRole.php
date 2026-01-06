<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $admin = auth()->guard('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login')->with('error', 'Please login first');
        }

        // Check if admin has required role
        if (in_array($admin->role, $roles)) {
            return $next($request);
        }

        // Admin role bypasses all checks
        if ($admin->role === 'admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
