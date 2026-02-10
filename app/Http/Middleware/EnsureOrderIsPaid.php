<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Order;

class EnsureOrderIsPaid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $order = $request->route('order');
        if ($order instanceof \App\Models\Order && $order->payment_status !== 'paid') {
            return redirect()->route('checkout.index')->with('error', 'Payment not completed.');
        }

        return $next($request);
    }
}
