<?php

namespace App\Services;

use App\Models\Order;
use Razorpay\Api\Api;
use Exception;
use Illuminate\Support\Facades\Log;

class RazorpayService
{
    protected $api;
    protected $keyId;
    protected $keySecret;

    public function __construct()
    {
        $this->keyId = config('razorpay.key_id');
        $this->keySecret = config('razorpay.key_secret');
        $this->api = new Api($this->keyId, $this->keySecret);
    }

    /**
     * Create a Razorpay order for the given order
     *
     * @param Order $order
     * @return array|null
     */
    public function createOrder(Order $order)
    {
        try {
            // Amount in paise (multiply by 100)
            $amountInPaise = $order->total * 100;

            $razorpayOrder = $this->api->order->create([
                'receipt' => config('razorpay.receipt_prefix') . $order->order_number,
                'amount' => $amountInPaise,
                'currency' => config('razorpay.currency'),
                'notes' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->user->name ?? $order->guest_name,
                    'customer_email' => $order->user->email ?? $order->guest_email,
                ]
            ]);

            // Update order with Razorpay order ID
            $order->update([
                'razorpay_order_id' => $razorpayOrder['id']
            ]);

            return [
                'success' => true,
                'razorpay_order_id' => $razorpayOrder['id'],
                'amount' => $amountInPaise,
                'currency' => config('razorpay.currency'),
                'key_id' => $this->keyId,
                'order_number' => $order->order_number,
                'customer_name' => $order->user->name ?? $order->guest_name,
                'customer_email' => $order->user->email ?? $order->guest_email,
                'customer_phone' => $order->user->phone ?? $order->guest_phone,
            ];

        } catch (Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify Razorpay payment signature
     *
     * @param string $razorpayOrderId
     * @param string $razorpayPaymentId
     * @param string $razorpaySignature
     * @return bool
     */
    public function verifyPaymentSignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)
    {
        try {
            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature
            ];

            $this->api->utility->verifyPaymentSignature($attributes);

            return true;

        } catch (Exception $e) {
            Log::error('Razorpay Signature Verification Failed: ' . $e->getMessage(), [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
            ]);

            return false;
        }
    }

    /**
     * Get payment details from Razorpay
     *
     * @param string $paymentId
     * @return array|null
     */
    public function getPaymentDetails($paymentId)
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            return $payment->toArray();

        } catch (Exception $e) {
            Log::error('Failed to fetch payment details: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
            ]);

            return null;
        }
    }

    /**
     * Process refund for a payment
     *
     * @param string $paymentId
     * @param float $amount (in rupees)
     * @return array
     */
    public function processRefund($paymentId, $amount = null)
    {
        try {
            $refundData = [];

            if ($amount !== null) {
                // Partial refund - amount in paise
                $refundData['amount'] = $amount * 100;
            }

            $refund = $this->api->payment->fetch($paymentId)->refund($refundData);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100, // Convert paise to rupees
                'status' => $refund->status,
            ];

        } catch (Exception $e) {
            Log::error('Razorpay Refund Failed: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'amount' => $amount,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature from Razorpay
     *
     * @param string $body
     * @param string $signature
     * @return bool
     */
    public function verifyWebhookSignature($body, $signature)
    {
        try {
            $webhookSecret = config('razorpay.webhook_secret');

            if (empty($webhookSecret)) {
                Log::warning('Razorpay webhook secret not configured');
                return false;
            }

            $expectedSignature = hash_hmac('sha256', $body, $webhookSecret);

            return hash_equals($expectedSignature, $signature);

        } catch (Exception $e) {
            Log::error('Webhook Signature Verification Failed: ' . $e->getMessage());
            return false;
        }
    }
}
