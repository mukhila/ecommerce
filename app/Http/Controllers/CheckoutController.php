<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Modules\Product\Models\Product; // Fixed Product Import
use App\Models\Transaction; // Added Transaction Import
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
                       ->with(['items.product.images', 'items.variation.attributeValue'])
                       ->first();

            if (!$cart || $cart->items->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Check stock availability before checkout
            foreach ($cart->items as $item) {
                if ($item->variation_id && $item->variation) {
                    // Check variation stock
                    if ($item->variation->stock < $item->quantity) {
                        $sizeName = $item->variation->attributeValue->value ?? 'selected size';
                        return redirect()->route('cart.index')
                                       ->with('error', "Product '{$item->product->name}' ({$sizeName}) has insufficient stock");
                    }
                } else {
                    // Check product stock
                    if ($item->product->stock < $item->quantity) {
                        return redirect()->route('cart.index')
                                       ->with('error', "Product '{$item->product->name}' has insufficient stock");
                    }
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
                'payment_method' => 'required|in:cod,razorpay,rayaz',
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

            // Create order items with proper stock handling
            foreach ($cart->items as $item) {
                $sizeLabel = null;

                // Handle variation stock
                if ($item->variation_id && $item->variation) {
                    // Use pessimistic locking to prevent overselling
                    $variation = \Modules\Product\Models\ProductAttribute::where('id', $item->variation_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$variation || $variation->stock < $item->quantity) {
                        DB::rollBack();
                        $sizeName = $item->variation->attributeValue->value ?? 'selected size';
                        return redirect()->route('cart.index')
                                       ->with('error', "Product '{$item->product->name}' ({$sizeName}) is out of stock");
                    }

                    // Deduct variation stock
                    $variation->decrement('stock', $item->quantity);
                    $sizeLabel = $variation->attributeValue->value ?? null;
                } else {
                    // No variation - check product stock
                    $product = Product::where('id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$product || $product->stock < $item->quantity) {
                        DB::rollBack();
                        return redirect()->route('cart.index')
                                       ->with('error', "Product '{$item->product->name}' is out of stock");
                    }

                    // Deduct product stock
                    $product->decrement('stock', $item->quantity);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variation_id' => $item->variation_id,
                    'size_label' => $sizeLabel,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->slug,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                    'attributes' => $item->attributes
                ]);
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
                // ... (Existing Razorpay Logic)
                $razorpayOrder = $this->razorpayService->createOrder($order);
                // ...
            } elseif ($validated['payment_method'] === 'rayaz') {
                // Initialize Rayaz Payment
                $rayazService = app(\App\Services\RayazPaymentService::class);
                $paymentData = $rayazService->initiatePayment($order);

                if ($paymentData['success']) {
                    // Log initial pending transaction
                    \App\Models\Transaction::create([
                        'order_id' => $order->id,
                        'gateway_transaction_id' => $paymentData['transaction_id'],
                        'amount' => $order->total,
                        'status' => 'pending',
                        'payment_method' => 'rayaz',
                        'raw_response' => $paymentData
                    ]);

                    // Redirect to Gateway
                    // If it's a GET redirect
                    // return redirect($paymentData['url'] . '?' . http_build_query($paymentData['params']));
                    
                    // If it's a POST redirect (Form Submit), return a view that auto-submits
                    return view('payment.redirect', [
                        'url' => $paymentData['url'],
                        'params' => $paymentData['params']
                    ]);
                } else {
                    return redirect()->route('cart.index')->with('error', 'Payment initialization failed.');
                }
            } else {
                // COD - Send notifications immediately
                try {
                // ... (Existing COD Logic)
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
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing checkout: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Debugging: Show the error on screen
            dd('Checkout Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            return redirect()->route('cart.index')
                           ->with('error', 'Unable to process order. Please try again.');
        }
    }
}
