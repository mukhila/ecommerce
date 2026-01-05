<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\OrderShipped;
use App\Notifications\OrderDelivered;
use App\Notifications\OrderCancelled;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        try {
            $query = Order::with(['user', 'items', 'shippingAddress'])
                         ->orderBy('created_at', 'desc');

            // Filter by status if provided
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // Filter by payment status if provided
            if ($request->has('payment_status') && $request->payment_status) {
                $query->where('payment_status', $request->payment_status);
            }

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }

            $orders = $query->paginate(20);

            // Get statistics
            $stats = [
                'total' => Order::count(),
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
                'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
            ];

            return view('admin::orders.index', compact('orders', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Unable to load orders');
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress']);
        return view('admin::orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
                'cancellation_reason' => 'required_if:status,cancelled|nullable|string|max:1000'
            ]);

            DB::beginTransaction();

            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Handle cancellation specially to restore stock
            if ($newStatus === 'cancelled') {
                $reason = $request->input('cancellation_reason', 'Cancelled by administrator');

                if (!$order->cancelOrder($reason)) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Unable to cancel order');
                }
            } else {
                // Regular status update
                $order->update(['status' => $newStatus]);
            }

            // Log status change
            Log::info('Order status updated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            // Send email notifications for status changes
            // Note: Cancellation notification is handled by cancelOrder() method
            try {
                if ($order->user && $newStatus !== 'cancelled') {
                    switch ($newStatus) {
                        case 'shipped':
                            $order->user->notify(new OrderShipped($order));
                            break;
                        case 'delivered':
                            $order->user->notify(new OrderDelivered($order));
                            break;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error sending order status notification: ' . $e->getMessage());
                // Don't fail the status update if notification fails
            }

            return redirect()->back()->with('success', 'Order status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update order status');
        }
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        try {
            $request->validate([
                'payment_status' => 'required|in:pending,paid,failed,refunded'
            ]);

            DB::beginTransaction();

            $order->update(['payment_status' => $request->payment_status]);

            Log::info('Payment status updated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_status' => $request->payment_status,
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Payment status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update payment status');
        }
    }

    /**
     * Update tracking information
     */
    public function updateTracking(Request $request, Order $order)
    {
        try {
            $request->validate([
                'tracking_number' => 'nullable|string|max:100',
                'courier_name' => 'nullable|string|max:100',
                'estimated_delivery_date' => 'nullable|date|after_or_equal:today'
            ]);

            DB::beginTransaction();

            $order->update([
                'tracking_number' => $request->tracking_number,
                'courier_name' => $request->courier_name,
                'estimated_delivery_date' => $request->estimated_delivery_date
            ]);

            Log::info('Tracking information updated', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'tracking_number' => $request->tracking_number,
                'courier_name' => $request->courier_name,
                'updated_by' => auth()->id()
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Tracking information updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating tracking information: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to update tracking information');
        }
    }
}
