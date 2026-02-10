<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\RayazPaymentService;
use Illuminate\Support\Facades\Log;

class RayazPaymentController extends Controller
{
    protected $paymentService;

    public function __construct(RayazPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate payment from checkout
     */
    public function initiate(Request $request)
    {
        // ... (Logic to validate order and initiate payment)
        // This is primarily called from CheckoutController@process, 
        // but if you have a separate "Retry Payment" route, it goes here.
    }

    /**
     * Handle Gateway Callback
     */
    public function callback(Request $request)
    {
        try {
            // 1. Verify Signature
            if (!$this->paymentService->verifySignature($request->all())) {
                Log::error('Rayaz Payment Signature Mismatch', ['data' => $request->all()]);
                return redirect()->route('payment.failure')->with('error', 'Security verification failed.');
            }

            // 2. Find Order & Transaction
            $orderId = $request->input('order_id');
            $status = $request->input('status'); // success / failed
            $gatewayTxnId = $request->input('transaction_id');

            $order = Order::findOrFail($orderId);

            // 3. Log Transaction
            Transaction::create([
                'order_id' => $order->id,
                'gateway_transaction_id' => $gatewayTxnId,
                'amount' => $request->input('amount'),
                'status' => $status === 'success' ? 'successful' : 'failed',
                'payment_method' => 'rayaz',
                'raw_response' => $request->all()
            ]);

            // 4. Update Order Status
            if ($status === 'success') {
                if ($order->total != $request->input('amount')) {
                    Log::error('Payment Amount Mismatch', ['order' => $order->total, 'paid' => $request->input('amount')]);
                    return redirect()->route('payment.failure')->with('error', 'Payment amount mismatch.');
                }

                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_method' => 'rayaz',
                    'payment_reference' => $gatewayTxnId
                ]);

                // Send Notifications (Email/SMS)
                // $order->user->notify(new OrderPlaced($order));

                return redirect()->route('payment.success', ['order' => $order->id]);
            } else {
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('payment.failure', ['order' => $order->id])->with('error', 'Payment failed by gateway.');
            }

        } catch (\Exception $e) {
            Log::error('Payment Callback Error: ' . $e->getMessage());
            return redirect()->route('payment.failure')->with('error', 'An error occurred while processing payment.');
        }
    }

    public function success(Order $order)
    {
        // Covered by middleware, but double check
        if ($order->payment_status !== 'paid') {
            return redirect()->route('home');
        }
        return view('payment.success', compact('order'));
    }

    public function failure(Request $request) 
    {
         $orderId = $request->query('order');
         $order = $orderId ? Order::find($orderId) : null;
         return view('payment.failure', compact('order'));
    }

    public function cancel(Request $request)
    {
        return redirect()->route('cart.index')->with('error', 'Payment cancelled by user.');
    }
}
