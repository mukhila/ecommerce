<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Modules\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the user's wishlist.
     */
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with('product.images')
            ->latest()
            ->get();

        return view('user.wishlist', compact('wishlistItems'));
    }

    /**
     * Toggle a product in/out of the wishlist (AJAX).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'Removed from wishlist']);
        }

        Wishlist::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status' => 'added', 'message' => 'Added to wishlist']);
    }

    /**
     * Remove a specific item from the wishlist.
     */
    public function destroy(int $productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        return back()->with('success', 'Item removed from wishlist.');
    }
}
