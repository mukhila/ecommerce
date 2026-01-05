<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Order success page
     */
    public function success(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $order->load('items.product', 'shippingAddress');

        return view('orders.success', compact('order'));
    }

    /**
     * Order tracking page
     */
    public function tracking(Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $order->load('items.product', 'shippingAddress');

        // Order status timeline
        $statusSteps = [
            'pending' => ['label' => 'Order Placed', 'icon' => 'ri-shopping-cart-line', 'order' => 1],
            'processing' => ['label' => 'Processing', 'icon' => 'ri-settings-3-line', 'order' => 2],
            'shipped' => ['label' => 'Shipped', 'icon' => 'ri-truck-line', 'order' => 3],
            'delivered' => ['label' => 'Delivered', 'icon' => 'ri-checkbox-circle-line', 'order' => 4],
        ];

        $currentStep = isset($statusSteps[$order->status]) ? $statusSteps[$order->status]['order'] : 1;

        if ($order->status === 'cancelled') {
            $currentStep = 0; // Special handling for cancelled orders
        }

        return view('orders.tracking', compact('order', 'statusSteps', 'currentStep'));
    }
}
