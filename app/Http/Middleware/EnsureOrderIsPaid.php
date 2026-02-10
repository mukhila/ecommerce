<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        if (!$order instanceof \App\Models\Order) {
            return redirect()->route('home')->with('error', 'Invalid order.');
        }

        if ($order->payment_status !== 'paid') {
            return redirect()->route('checkout.index')->with('error', 'Payment not completed.');
        }

        // Verify the authenticated user owns this order
        if (Auth::check() && $order->user_id !== Auth::id()) {
            Log::warning('Unauthorized order access attempt', [
                'user_id' => Auth::id(),
                'order_id' => $order->id,
                'order_owner_id' => $order->user_id,
            ]);
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
