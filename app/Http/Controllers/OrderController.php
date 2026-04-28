<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Public Track Order lookup page (GET)
     */
    public function showTrackLookup()
    {
        return view('orders.track-lookup');
    }

    /**
     * Public Track Order lookup (POST) — verify by order number + email or phone
     */
    public function trackLookup(Request $request)
    {
        $request->validate([
            'order_number' => ['required', 'string'],
            'identity'     => ['required', 'string'],
        ]);

        $orderNumber = strtoupper(trim($request->order_number));
        $identity    = trim($request->identity);

        $order = Order::where('order_number', $orderNumber)
            ->with(['shippingAddress'])
            ->first();

        if ($order) {
            $verified = false;

            // Check against logged-in user
            if (Auth::check() && $order->user_id === Auth::id()) {
                $verified = true;
            }

            // Check against shipping address email / phone
            if (! $verified && $order->shippingAddress) {
                $addr = $order->shippingAddress;
                if (
                    (filter_var($identity, FILTER_VALIDATE_EMAIL) && strtolower($addr->email ?? '') === strtolower($identity)) ||
                    (preg_match('/^\d{10}$/', $identity) && ($addr->phone ?? '') === $identity)
                ) {
                    $verified = true;
                }
            }

            // Check against user email / phone
            if (! $verified && $order->user) {
                $user = $order->user;
                if (
                    (filter_var($identity, FILTER_VALIDATE_EMAIL) && strtolower($user->email ?? '') === strtolower($identity)) ||
                    (preg_match('/^\d{10}$/', $identity) && ($user->phone ?? '') === $identity)
                ) {
                    $verified = true;
                }
            }

            if ($verified) {
                return view('orders.track-lookup', compact('order'));
            }
        }

        return view('orders.track-lookup', ['notFound' => true])
            ->withInput();
    }

    /**
     * Guest order confirmation page (session-token verified, no auth required).
     */
    public function guestConfirmation(Request $request)
    {
        $orderId = $request->session()->pull('guest_order_id');

        if (!$orderId) {
            return redirect()->route('home')
                ->with('info', 'No pending order found. Use Track Order to look up an existing order.');
        }

        $order = Order::with(['items', 'shippingAddress'])->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        return view('orders.success', compact('order'));
    }

    /**
     * Order success page
     */
    public function success(Order $order)
    {
        // dd('Reached success view'); // Uncomment to debug
        // Ensure user can only view their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $order->load('items.product', 'shippingAddress');

        return view('orders.success', compact('order'));
    }

    /**
     * Customer self-cancellation — only pending/processing orders may be cancelled.
     */
    public function cancel(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Order #' . $order->order_number . ' cannot be cancelled at this stage.');
        }

        $request->validate(['reason' => ['nullable', 'string', 'max:500']]);

        $reason = $request->filled('reason') ? $request->reason : 'Cancelled by customer';
        $order->cancelOrder($reason);

        return back()->with('success', 'Order #' . $order->order_number . ' has been cancelled.');
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
