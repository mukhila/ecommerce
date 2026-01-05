<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderPlaced;
use App\Notifications\NewOrderNotification;
use App\Models\User;

class PaymentController extends Controller
{
    protected $razorpayService;

    public function __construct(RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    /**
     * Handle Razorpay payment callback
     */
    public function callback(Request $request)
    {
        try {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);

            // Find order by Razorpay order ID
            $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->firstOrFail();

            // Verify payment signature
            $isValid = $this->razorpayService->verifyPaymentSignature(
                $request->razorpay_order_id,
                $request->razorpay_payment_id,
                $request->razorpay_signature
            );

            if (!$isValid) {
                // Signature verification failed
                Log::error('Payment signature verification failed', [
                    'order_id' => $order->id,
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                ]);

                return redirect()->route('checkout.index')
                    ->with('error', 'Payment verification failed. Please try again or contact support.');
            }

            // Update order with payment details
            DB::beginTransaction();

            try {
                $order->update([
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'payment_status' => 'paid',
                ]);

                DB::commit();

                // Send notifications
                if ($order->user) {
                    $order->user->notify(new OrderPlaced($order));
                }

                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new NewOrderNotification($order));

                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Payment successful! Your order has been confirmed.');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to update order after payment: ' . $e->getMessage(), [
                    'order_id' => $order->id,
                ]);

                return redirect()->route('checkout.index')
                    ->with('error', 'Payment was successful but order update failed. Please contact support with order number: ' . $order->order_number);
            }

        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'request' => $request->all(),
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Payment processing failed. Please try again.');
        }
    }

    /**
     * Handle payment failure
     */
    public function failed(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            $order = Order::find($orderId);

            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                ]);

                Log::info('Payment failed for order', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);
            }
        }

        return redirect()->route('checkout.index')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }

    /**
     * Handle Razorpay webhook
     */
    public function webhook(Request $request)
    {
        try {
            $webhookSignature = $request->header('X-Razorpay-Signature');
            $webhookBody = $request->getContent();

            // Verify webhook signature
            if (!$this->razorpayService->verifyWebhookSignature($webhookBody, $webhookSignature)) {
                Log::warning('Invalid webhook signature received');
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $payload = $request->all();
            $event = $payload['event'] ?? null;

            Log::info('Razorpay Webhook Received', [
                'event' => $event,
                'payload' => $payload,
            ]);

            // Handle different webhook events
            switch ($event) {
                case 'payment.authorized':
                    $this->handlePaymentAuthorized($payload);
                    break;

                case 'payment.captured':
                    $this->handlePaymentCaptured($payload);
                    break;

                case 'payment.failed':
                    $this->handlePaymentFailed($payload);
                    break;

                case 'refund.created':
                    $this->handleRefundCreated($payload);
                    break;

                case 'refund.processed':
                    $this->handleRefundProcessed($payload);
                    break;

                default:
                    Log::info('Unhandled webhook event', ['event' => $event]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle payment.authorized webhook event
     */
    protected function handlePaymentAuthorized($payload)
    {
        $paymentEntity = $payload['payload']['payment']['entity'] ?? null;

        if (!$paymentEntity) {
            return;
        }

        $razorpayOrderId = $paymentEntity['order_id'] ?? null;

        if ($razorpayOrderId) {
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

            if ($order && $order->payment_status === 'pending') {
                Log::info('Payment authorized for order', [
                    'order_id' => $order->id,
                    'razorpay_payment_id' => $paymentEntity['id'],
                ]);
            }
        }
    }

    /**
     * Handle payment.captured webhook event
     */
    protected function handlePaymentCaptured($payload)
    {
        $paymentEntity = $payload['payload']['payment']['entity'] ?? null;

        if (!$paymentEntity) {
            return;
        }

        $razorpayOrderId = $paymentEntity['order_id'] ?? null;
        $razorpayPaymentId = $paymentEntity['id'] ?? null;

        if ($razorpayOrderId && $razorpayPaymentId) {
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

            if ($order && $order->payment_status !== 'paid') {
                $order->update([
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'payment_status' => 'paid',
                ]);

                Log::info('Payment captured for order', [
                    'order_id' => $order->id,
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);
            }
        }
    }

    /**
     * Handle payment.failed webhook event
     */
    protected function handlePaymentFailed($payload)
    {
        $paymentEntity = $payload['payload']['payment']['entity'] ?? null;

        if (!$paymentEntity) {
            return;
        }

        $razorpayOrderId = $paymentEntity['order_id'] ?? null;

        if ($razorpayOrderId) {
            $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'failed',
                ]);

                Log::warning('Payment failed for order', [
                    'order_id' => $order->id,
                    'reason' => $paymentEntity['error_description'] ?? 'Unknown',
                ]);
            }
        }
    }

    /**
     * Handle refund.created webhook event
     */
    protected function handleRefundCreated($payload)
    {
        $refundEntity = $payload['payload']['refund']['entity'] ?? null;

        if (!$refundEntity) {
            return;
        }

        $razorpayPaymentId = $refundEntity['payment_id'] ?? null;

        if ($razorpayPaymentId) {
            $order = Order::where('razorpay_payment_id', $razorpayPaymentId)->first();

            if ($order) {
                Log::info('Refund created for order', [
                    'order_id' => $order->id,
                    'refund_id' => $refundEntity['id'],
                    'amount' => $refundEntity['amount'] / 100,
                ]);
            }
        }
    }

    /**
     * Handle refund.processed webhook event
     */
    protected function handleRefundProcessed($payload)
    {
        $refundEntity = $payload['payload']['refund']['entity'] ?? null;

        if (!$refundEntity) {
            return;
        }

        $razorpayPaymentId = $refundEntity['payment_id'] ?? null;

        if ($razorpayPaymentId) {
            $order = Order::where('razorpay_payment_id', $razorpayPaymentId)->first();

            if ($order) {
                $order->update([
                    'payment_status' => 'refunded',
                ]);

                Log::info('Refund processed for order', [
                    'order_id' => $order->id,
                    'refund_id' => $refundEntity['id'],
                    'amount' => $refundEntity['amount'] / 100,
                ]);
            }
        }
    }
}
