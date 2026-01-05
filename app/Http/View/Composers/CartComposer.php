<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->items : collect();
        $cartCount = $cart ? $cart->item_count : 0;
        $cartTotal = $cart ? $cart->total : 0;

        $view->with([
            'sharedCart' => $cart,
            'sharedCartItems' => $cartItems,
            'sharedCartCount' => $cartCount,
            'sharedCartTotal' => $cartTotal
        ]);
    }

    /**
     * Get or create cart for current user/session
     */
    private function getCart()
    {
        try {
            if (Auth::check()) {
                $cart = Cart::where('user_id', Auth::id())
                           ->with('items.product.images')
                           ->first();
            } else {
                $sessionId = Session::getId();
                $cart = Cart::where('session_id', $sessionId)
                           ->with('items.product.images')
                           ->first();
            }

            return $cart;
        } catch (\Exception $e) {
            \Log::error('Error in CartComposer: ' . $e->getMessage());
            return null;
        }
    }
}
