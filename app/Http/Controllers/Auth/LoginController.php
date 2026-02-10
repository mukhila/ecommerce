<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Capture session ID before login (guest session)
        $guestSessionId = $request->session()->getId();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Migrate Guest Cart to User Cart
            $this->migrateGuestCart($guestSessionId, $user->id);

            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Migrate guest cart items to user cart
     */
    protected function migrateGuestCart($guestSessionId, $userId)
    {
        $guestCart = \App\Models\Cart::where('session_id', $guestSessionId)->first();
        
        if (!$guestCart) {
            return;
        }

        $userCart = \App\Models\Cart::where('user_id', $userId)->first();

        if ($userCart) {
            // User already has a cart, merge items
            foreach ($guestCart->items as $guestItem) {
                $existingItem = $userCart->items()
                    ->where('product_id', $guestItem->product_id)
                    ->where('variation_id', $guestItem->variation_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $guestItem->quantity
                    ]);
                    $guestItem->delete();
                } else {
                    $guestItem->update(['cart_id' => $userCart->id]);
                }
            }
            // Delete the empty guest cart
            $guestCart->delete();
        } else {
            // User has no cart, assign guest cart to user
            $guestCart->update([
                'user_id' => $userId,
                'session_id' => null
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
