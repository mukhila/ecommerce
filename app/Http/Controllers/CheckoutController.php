<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingAddress;
use Modules\Product\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     * Display checkout page (auth optional — guests allowed).
     */
    public function index(Request $request)
    {
        try {
            $cart = $this->resolveCart($request);

            if (!$cart || $cart->items->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Check stock availability before checkout
            foreach ($cart->items as $item) {
                if ($item->variation_id && $item->variation) {
                    if ($item->variation->stock < $item->quantity) {
                        $sizeName = $item->variation->attributeValue->value ?? 'selected size';
                        return redirect()->route('cart.index')
                            ->with('error', "Product '{$item->product->name}' ({$sizeName}) has insufficient stock");
                    }
                } else {
                    if ($item->product->stock < $item->quantity) {
                        return redirect()->route('cart.index')
                            ->with('error', "Product '{$item->product->name}' has insufficient stock");
                    }
                }
            }

            $user = Auth::user(); // null for guests

            return view('checkout.index', compact('cart', 'user'));
        } catch (Exception $e) {
            Log::error('Error loading checkout: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Unable to load checkout page');
        }
    }

    /**
     * Process checkout and create order (auth optional — guests can use COD).
     */
    public function process(Request $request)
    {
        $isGuest = !Auth::check();

        try {
            $allowedMethods = 'cod,razorpay,rayaz';

            $validated = $request->validate([
                'full_name'       => 'required|string|max:255',
                'email'           => 'required|email|max:255',
                'phone'           => 'required|string|max:20',
                'alternate_phone' => 'nullable|string|max:20',
                'address_line1'   => 'required|string|max:255',
                'address_line2'   => 'nullable|string|max:255',
                'city'            => 'required|string|max:100',
                'state'           => 'required|string|max:100',
                'postal_code'     => ['required', 'string', 'regex:/^\d{6}$/'],
                'country'         => 'required|string|max:100',
                'payment_method'  => 'required|in:' . $allowedMethods,
                'notes'           => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            $cart = $this->resolveCart($request);

            if (!$cart || $cart->items->count() === 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            // Calculate totals with GST
            $subtotal     = $cart->subtotal;
            $gstAmount    = $cart->gst_amount;
            $gstBreakdown = $cart->gst_breakdown;
            $cartTotal    = $cart->total;
            $shippingCost = $cartTotal >= 3000 ? 0 : 100;
            $discount     = 0;
            $total        = $cartTotal + $shippingCost - $discount;

            // Create order — user_id is null for guests
            $order = Order::create([
                'user_id'        => Auth::id(),
                'guest_email'    => $isGuest ? $validated['email']       : null,
                'guest_name'     => $isGuest ? $validated['full_name']   : null,
                'guest_phone'    => $isGuest ? $validated['phone']        : null,
                'subtotal'       => $subtotal,
                'gst_amount'     => $gstAmount,
                'gst_breakdown'  => $gstBreakdown,
                'tax'            => $gstAmount,
                'shipping_cost'  => $shippingCost,
                'discount'       => $discount,
                'total'          => $total,
                'status'         => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            // Create order items (with pessimistic locking)
            foreach ($cart->items as $item) {
                $sizeLabel = null;

                if ($item->variation_id && $item->variation) {
                    $variation = \Modules\Product\Models\ProductAttribute::where('id', $item->variation_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$variation || $variation->stock < $item->quantity) {
                        DB::rollBack();
                        $sizeName = $item->variation->attributeValue->value ?? 'selected size';
                        return redirect()->route('cart.index')
                            ->with('error', "Product '{$item->product->name}' ({$sizeName}) is out of stock");
                    }

                    $variation->decrement('stock', $item->quantity);
                    $sizeLabel = $variation->attributeValue->value ?? null;
                } else {
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();

                    if (!$product || $product->stock < $item->quantity) {
                        DB::rollBack();
                        return redirect()->route('cart.index')
                            ->with('error', "Product '{$item->product->name}' is out of stock");
                    }

                    $product->decrement('stock', $item->quantity);
                }

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item->product_id,
                    'variation_id'=> $item->variation_id,
                    'size_label'  => $sizeLabel,
                    'product_name'=> $item->product->name,
                    'product_sku' => $item->product->slug,
                    'quantity'    => $item->quantity,
                    'price'       => $item->price,
                    'total'       => $item->price * $item->quantity,
                    'attributes'  => $item->attributes,
                ]);
            }

            // Create shipping address
            ShippingAddress::create([
                'order_id'     => $order->id,
                'full_name'    => $validated['full_name'],
                'email'        => $validated['email'],
                'phone'        => $validated['phone'],
                'address_line1'=> $validated['address_line1'],
                'address_line2'=> $validated['address_line2'] ?? null,
                'city'         => $validated['city'],
                'state'        => $validated['state'],
                'postal_code'  => $validated['postal_code'],
                'country'      => $validated['country'],
            ]);

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            // Handle payment routing
            if ($validated['payment_method'] === 'razorpay') {
                // Razorpay: auth users only (guard is enforced by validation above)
                $razorpayData = $this->razorpayService->createOrder($order);

                if (!$razorpayData['success']) {
                    return redirect()->route('cart.index')
                        ->with('error', 'Unable to initialize payment. Please try again.');
                }

                return view('payment.razorpay-checkout', compact('order', 'razorpayData'));

            } elseif ($validated['payment_method'] === 'rayaz') {
                $rayazService = app(\App\Services\RayazPaymentService::class);
                $paymentData  = $rayazService->initiatePayment($order);

                if ($paymentData['success']) {
                    Transaction::create([
                        'order_id'              => $order->id,
                        'gateway_transaction_id'=> $paymentData['transaction_id'],
                        'amount'                => $order->total,
                        'status'                => 'pending',
                        'payment_method'        => 'rayaz',
                        'raw_response'          => $paymentData,
                    ]);

                    return view('payment.redirect', [
                        'url'    => $paymentData['url'],
                        'params' => $paymentData['params'],
                    ]);
                }

                return redirect()->route('cart.index')->with('error', 'Payment initialization failed.');

            } else {
                // COD — send notifications
                $this->sendOrderNotifications($order, $isGuest, $validated['email']);

                if ($isGuest) {
                    // Store a one-time session token for the guest confirmation page
                    session(['guest_order_id' => $order->id]);
                    return redirect()->route('order.guest-confirmation')
                        ->with('success', 'Order placed successfully!');
                }

                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Order placed successfully!');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error processing checkout: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->route('cart.index')
                ->with('error', 'Unable to process order. Please try again.');
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Resolve the active cart for the current user or guest session.
     */
    private function resolveCart(Request $request): ?Cart
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())
                ->with(['items.product.images', 'items.variation.attributeValue'])
                ->first();
        }

        return Cart::where('session_id', $request->session()->getId())
            ->with(['items.product.images', 'items.variation.attributeValue'])
            ->first();
    }

    /**
     * Send order-placed notifications to the customer and all admins.
     */
    private function sendOrderNotifications(Order $order, bool $isGuest, string $guestEmail): void
    {
        try {
            if (!$isGuest && $order->user) {
                $order->user->notify(new OrderPlaced($order));
            } else {
                // Notify guest via anonymous mail route
                Notification::route('mail', $guestEmail)
                    ->notify(new OrderPlaced($order));
            }

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewOrderNotification($order));
        } catch (Exception $e) {
            Log::error('Error sending order notifications: ' . $e->getMessage());
            // Do not fail the order if notifications fail
        }
    }
}
