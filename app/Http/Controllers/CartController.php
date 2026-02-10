<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Modules\Product\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CartController extends Controller
{
    /**
     * Get or create cart for current user/session
     */
    private function getCart()
    {
        try {
            if (Auth::check()) {
                $cart = Cart::firstOrCreate(
                    ['user_id' => Auth::id()],
                    ['session_id' => null]
                );
            } else {
                $sessionId = Session::getId();
                $cart = Cart::firstOrCreate(
                    ['session_id' => $sessionId],
                    ['user_id' => null]
                );
            }

            $cart->load('items.product.images');
            return $cart;
        } catch (Exception $e) {
            Log::error('Error getting cart: ' . $e->getMessage());
            throw new Exception('Unable to access cart. Please try again.');
        }
    }

    /**
     * Display the cart page
     */
    public function index()
    {
        try {
            $cart = $this->getCart();
            return view('cart.index', compact('cart'));
        } catch (Exception $e) {
            Log::error('Error loading cart page: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Unable to load cart. Please try again.');
        }
    }

    /**
     * Add product to cart (AJAX)
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'variation_id' => 'nullable|exists:product_attributes,id',
                'quantity' => 'integer|min:1|max:999',
                'attributes' => 'nullable|array'
            ]);

            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity ?? 1;
            $variationId = $request->variation_id;
            $variation = null;

            // Check if product is active/available
            if (!$product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is no longer available'
                ], 400);
            }

            // If product has size variations, variation_id is required
            if ($product->hasSizeVariations()) {
                if (!$variationId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select a size'
                    ], 400);
                }

                $variation = \Modules\Product\Models\ProductAttribute::with('attributeValue')
                    ->where('id', $variationId)
                    ->where('product_id', $product->id)
                    ->first();

                if (!$variation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid size selection'
                    ], 400);
                }

                if (!$variation->isAvailable()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected size is currently unavailable'
                    ], 400);
                }

                $availableStock = $variation->stock;
                $price = $variation->effective_price;
            } else {
                // No variations - use product stock
                $availableStock = $product->stock;
                $price = $product->sale_price ?? $product->price;

                if ($availableStock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This product is currently out of stock'
                    ], 400);
                }
            }

            if ($availableStock < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$availableStock} items available in stock"
                ], 400);
            }

            $cart = $this->getCart();

            // Check if same product + variation already in cart
            $cartItemQuery = $cart->items()->where('product_id', $product->id);

            if ($variationId) {
                $cartItem = $cartItemQuery->where('variation_id', $variationId)->first();
            } else {
                $cartItem = $cartItemQuery->whereNull('variation_id')->first();
            }

            if ($cartItem) {
                // Update quantity
                $newQuantity = $cartItem->quantity + $quantity;

                if ($availableStock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cannot add more items. Only {$availableStock} available, you already have {$cartItem->quantity} in cart"
                    ], 400);
                }

                $cartItem->update([
                    'quantity' => $newQuantity,
                    'price' => $price, // Update price in case it changed
                    'attributes' => $attributes ?? $cartItem->attributes
                ]);
            } else {
                // Build attributes JSON for display purposes
                $attributes = null;
                if ($variation) {
                    $attributes = [
                        'size' => [
                            'id' => $variation->id,
                            'label' => $variation->attributeValue->value
                        ]
                    ];
                }

                // Add new item
                $cartItem = $cart->items()->create([
                    'product_id' => $product->id,
                    'variation_id' => $variationId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'attributes' => $attributes
                ]);
            }

            $cart->refresh();

            DB::commit();

            $redirectUrl = null;
            if ($request->has('buy_now') && $request->buy_now) {
                $redirectUrl = route('checkout.index');
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => $cart->item_count,
                'cart_total' => $cart->total,
                'redirect_url' => $redirectUrl
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Invalid input data',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Product not found: ' . $request->product_id);
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error adding to cart: ' . $e->getMessage(), [
                'product_id' => $request->product_id,
                'variation_id' => $request->variation_id ?? null,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to add product to cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Update cart item quantity (AJAX)
     */
    public function update(Request $request, $itemId)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1|max:999'
            ]);

            DB::beginTransaction();

            $cart = $this->getCart();
            $cartItem = $cart->items()->with('variation')->findOrFail($itemId);

            // Check if product still exists and is active
            if (!$cartItem->product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product no longer exists'
                ], 404);
            }

            if (!$cartItem->product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This product is no longer available'
                ], 400);
            }

            // Check stock based on variation or product
            if ($cartItem->variation_id && $cartItem->variation) {
                $availableStock = $cartItem->variation->stock;

                if (!$cartItem->variation->isAvailable()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Selected size is no longer available'
                    ], 400);
                }
            } else {
                $availableStock = $cartItem->product->stock;

                if ($availableStock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'This product is currently out of stock'
                    ], 400);
                }
            }

            if ($availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Only {$availableStock} items available in stock"
                ], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);
            $cart->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'item_subtotal' => $cartItem->subtotal,
                'cart_total' => $cart->total,
                'cart_count' => $cart->item_count
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Cart item not found: ' . $itemId);
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating cart: ' . $e->getMessage(), [
                'item_id' => $itemId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to update cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove item from cart (AJAX)
     */
    public function remove($itemId)
    {
        try {
            DB::beginTransaction();

            $cart = $this->getCart();
            $cartItem = $cart->items()->findOrFail($itemId);
            $cartItem->delete();

            $cart->refresh();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'cart_total' => $cart->total,
                'cart_count' => $cart->item_count
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Cart item not found for removal: ' . $itemId);
            return response()->json([
                'success' => false,
                'message' => 'Item not found in cart'
            ], 404);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error removing cart item: ' . $e->getMessage(), [
                'item_id' => $itemId,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to remove item. Please try again.'
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            DB::beginTransaction();

            $cart = $this->getCart();

            if ($cart->items->count() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is already empty'
                ], 400);
            }

            $cart->items()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully'
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error clearing cart: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Unable to clear cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Get cart count (for header)
     */
    public function count()
    {
        try {
            $cart = $this->getCart();

            return response()->json([
                'count' => $cart->item_count,
                'total' => $cart->total
            ]);

        } catch (Exception $e) {
            Log::error('Error getting cart count: ' . $e->getMessage());
            return response()->json([
                'count' => 0,
                'total' => 0
            ]);
        }
    }

    /**
     * Get cart offcanvas HTML content (for AJAX refresh)
     */
    public function offcanvas()
    {
        try {
            $cart = $this->getCart();

            return response()->json([
                'success' => true,
                'html' => view('cart.partials.offcanvas-content', [
                    'cart' => $cart,
                    'cartItems' => $cart->items,
                    'cartCount' => $cart->item_count,
                    'cartTotal' => $cart->total
                ])->render(),
                'count' => $cart->item_count,
                'total' => $cart->total
            ]);

        } catch (Exception $e) {
            Log::error('Error getting cart offcanvas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to load cart'
            ], 500);
        }
    }
}
