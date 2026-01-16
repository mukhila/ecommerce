<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderPlaced;
use App\Notifications\NewOrderNotification;
use App\Models\User;
use App\Services\RazorpayService;
use Exception;

class CheckoutController extends Controller
{
    protected $razorpayService;

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    /**
     * Display checkout page
     */
    public function index()
    {
        try {
            $cart = Cart::where('user_id', Auth::id())
                       ->with('items.product.images')
                       ->first();

            if (!$cart || $cart->items->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Check stock availability before checkout
            foreach ($cart->items as $item) {
                if ($item->product->stock < $item->quantity) {
                    return redirect()->route('cart.index')
                                   ->with('error', "Product '{$item->product->name}' has insufficient stock");
                }
            }

            $user = Auth::user();

            return view('checkout.index', compact('cart', 'user'));
        } catch (Exception $e) {
            Log::error('Error loading checkout: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Unable to load checkout page');
        }
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'alternate_phone' => 'nullable|string|max:20',
                'address_line1' => 'required|string|max:255',
                'address_line2' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'payment_method' => 'required|in:cod,razorpay',
                'notes' => 'nullable|string|max:500'
            ]);

            DB::beginTransaction();

            // Get cart
            $cart = Cart::where('user_id', Auth::id())
                       ->with('items.product')
                       ->first();

            if (!$cart || $cart->items->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Calculate totals with proper GST
            $subtotal = $cart->subtotal; // Price excluding GST
            $gstAmount = $cart->gst_amount; // Total GST
            $gstBreakdown = $cart->gst_breakdown; // GST breakdown by rate
            $cartTotal = $cart->total; // Subtotal + GST
            $shippingCost = $cartTotal >= 3000 ? 0 : 100; // Free shipping above ₹3000
            $discount = 0;
            $total = $cartTotal + $shippingCost - $discount;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'guest_email' => null,
                'guest_name' => null,
                'guest_phone' => null,
                'subtotal' => $subtotal,
                'gst_amount' => $gstAmount,
                'gst_breakdown' => $gstBreakdown,
                'tax' => $gstAmount, // For backward compatibility
                'shipping_cost' => $shippingCost,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => $validated['payment_method'] === 'cod' ? 'pending' : 'pending',
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                // Check stock again
                if ($item->product->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                                   ->with('error', "Product '{$item->product->name}' is out of stock");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->slug,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                    'attributes' => $item->attributes
                ]);

                // Reduce stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Create shipping address
            ShippingAddress::create([
                'order_id' => $order->id,
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address_line1' => $validated['address_line1'],
                'address_line2' => $validated['address_line2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country']
            ]);

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Handle payment method
            if ($validated['payment_method'] === 'razorpay') {
                // Create Razorpay order
                $razorpayOrder = $this->razorpayService->createOrder($order);

                if (!$razorpayOrder['success']) {
                    // Razorpay order creation failed
                    return redirect()->route('cart.index')
                                   ->with('error', 'Unable to initialize payment. Please try again.');
                }

                // Return to checkout with payment details
                return view('checkout.payment', [
                    'order' => $order,
                    'razorpayOrder' => $razorpayOrder
                ]);

            } else {
                // COD - Send notifications immediately
                try {
                    // Send notification to user
                    $user = Auth::user();
                    $user->notify(new OrderPlaced($order));

                    // Send notification to admin
                    $admins = User::where('role', 'admin')->get();
                    Notification::send($admins, new NewOrderNotification($order));
                } catch (Exception $e) {
                    Log::error('Error sending notifications: ' . $e->getMessage());
                    // Don't fail the order if notifications fail
                }

                return redirect()->route('order.success', $order->id)
                               ->with('success', 'Order placed successfully!');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                           ->withErrors($e->errors())
                           ->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing checkout: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('cart.index')
                           ->with('error', 'Unable to process order. Please try again.');
        }
    }
}
