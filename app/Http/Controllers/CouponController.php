<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Models\Coupon;

class CouponController extends Controller
{
    public function apply(Request $request): JsonResponse
    {
        $request->validate([
            'code'       => 'required|string|max:50',
            'cart_total' => 'required|numeric|min:0',
            'email'      => 'nullable|email|max:255',
            'phone'      => ['nullable', 'string', 'regex:/^[6-9]\d{9}$/'],
        ]);

        $code      = strtoupper(trim($request->code));
        $cartTotal = (float) $request->cart_total;
        $email     = $request->input('email');

        // Validate email format (extra check for meaningful feedback)
        if ($email !== null && $email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Please enter a valid email address first.']);
        }

        $coupon = Coupon::where('code', $code)->where('status', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        $today = Carbon::today();

        if ($coupon->start_date && Carbon::parse($coupon->start_date)->isAfter($today)) {
            return response()->json(['success' => false, 'message' => 'This coupon is not yet active.']);
        }

        if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->isBefore($today)) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired.']);
        }

        // One-time use check
        if (Auth::check()) {
            $alreadyUsed = Order::where('user_id', Auth::id())
                ->where('coupon_code', $code)
                ->whereNotIn('status', ['cancelled'])
                ->exists();

            if ($alreadyUsed) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon.']);
            }
        } elseif ($email) {
            $alreadyUsed = Order::where('guest_email', $email)
                ->where('coupon_code', $code)
                ->whereNotIn('status', ['cancelled'])
                ->exists();

            if ($alreadyUsed) {
                return response()->json(['success' => false, 'message' => 'This coupon has already been used with this email address.']);
            }
        }

        $discount = $coupon->type === 'percent'
            ? round(($coupon->value / 100) * $cartTotal, 2)
            : min((float) $coupon->value, $cartTotal);

        $label = $coupon->type === 'percent'
            ? "{$coupon->value}% off"
            : '₹' . number_format($coupon->value, 0) . ' off';

        return response()->json([
            'success'  => true,
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
            'label'    => $label,
            'message'  => "Coupon applied — {$label}!",
        ]);
    }
}
