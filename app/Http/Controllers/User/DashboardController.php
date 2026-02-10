<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get user's cart
        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();

        // Get order statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
                          ->where('payment_status', 'paid')
                          ->sum('total');

        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
                            ->with('items.product')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        // Get pending orders
        $pendingOrders = Order::where('user_id', $user->id)
                             ->where('status', 'pending')
                             ->count();

        // Get delivered orders
        $deliveredOrders = Order::where('user_id', $user->id)
                                ->where('status', 'delivered')
                                ->count();

        // Get cart count
        $cartCount = $cart ? $cart->item_count : 0;

        // Get notifications
        $notifications = $user->notifications()->paginate(10);

        return view('user.dashboard', compact(
            'totalOrders',
            'totalSpent',
            'recentOrders',
            'pendingOrders',
            'deliveredOrders',
            'cartCount',
            'cart',
            'notifications'
        ));
    }
}
