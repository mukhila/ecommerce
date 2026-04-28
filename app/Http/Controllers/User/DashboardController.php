<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
                          ->where('status', '!=', 'cancelled')
                          ->sum('total');

        // Get all orders paginated (custom page name preserves other query params)
        $recentOrders = Order::where('user_id', $user->id)
                            ->with('items.product')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10, ['*'], 'orders_page');

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

        // Get wishlist items
        $wishlistItems = Wishlist::where('user_id', $user->id)
            ->with('product.images')
            ->latest()
            ->get();

        // Refund history: paid orders that were subsequently cancelled
        $refundOrders = Order::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->whereIn('payment_status', ['paid', 'refunded'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Pre-fill saved address: user's stored address, else latest order's shipping address
        $savedAddress = null;
        if ($user->address_line1) {
            $savedAddress = $user;
        } else {
            $latestOrder = Order::where('user_id', $user->id)
                ->whereHas('shippingAddress')
                ->latest()
                ->first();
            if ($latestOrder) {
                $savedAddress = $latestOrder->shippingAddress;
            }
        }

        return view('user.dashboard', compact(
            'totalOrders',
            'totalSpent',
            'recentOrders',
            'pendingOrders',
            'deliveredOrders',
            'cartCount',
            'cart',
            'notifications',
            'wishlistItems',
            'savedAddress',
            'refundOrders'
        ));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $user = Auth::user();
        $avatarDir = public_path('uploads/avatars');

        if (!is_dir($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        // Delete old avatar file
        if ($user->avatar && file_exists($avatarDir . '/' . $user->avatar)) {
            unlink($avatarDir . '/' . $user->avatar);
        }

        $filename = 'avatar_' . $user->id . '_' . time() . '.' . $request->avatar->extension();
        $request->avatar->move($avatarDir, $filename);

        $user->update(['avatar' => $filename]);

        return back()->with('avatar_success', 'Profile photo updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('password_success', 'Password updated successfully.');
    }

    public function saveAddress(Request $request)
    {
        $data = $request->validate([
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city'          => ['required', 'string', 'max:100'],
            'state'         => ['required', 'string', 'max:100'],
            'postal_code'   => ['required', 'string', 'max:20'],
            'country'       => ['required', 'string', 'max:100'],
        ]);

        Auth::user()->update($data);

        return back()->with('address_success', 'Address saved successfully.');
    }
}
