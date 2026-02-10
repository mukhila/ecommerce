<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RayazPaymentService
{
    protected $merchantId;
    protected $secretKey;
    protected $baseUrl;

    public function __construct()
    {
        // These should be in .env
        $this->merchantId = config('services.rayaz.merchant_id');
        $this->secretKey = config('services.rayaz.secret_key');
        // Example Base URL for generic redirect gateway
        $this->baseUrl = config('services.rayaz.base_url', 'https://api.rayaz-gateway.com');
    }

    /**
     * Create a payment token/hash and redirect URL
     */
    public function initiatePayment(Order $order)
    {
        try {
            $transactionId = 'TXN_' . Str::random(16);
            $amount = $order->total;
            
            // 1. Generate Signature/Hash
            // Most gateways use: Hash(merchant_id + order_id + amount + secret_key)
            $signaturePayload = "{$this->merchantId}|{$order->id}|{$amount}|{$this->secretKey}";
            $signature = hash('sha256', $signaturePayload);

            // 2. Prepare Payload
            $payload = [
                'merchant_id' => $this->merchantId,
                'order_id' => $order->id,
                'amount' => $amount,
                'currency' => 'INR',
                'redirect_url' => route('payment.rayaz.callback'),
                'cancel_url' => route('payment.rayaz.cancel'),
                'customer_email' => $order->user->email ?? $order->guest_email,
                'customer_phone' => $order->user->phone ?? $order->guest_phone,
                'signature' => $signature,
                'custom_ref' => $transactionId
            ];

            // In a real integration, you might call an API here to get a token.
            // For a pure redirect flow, you return the URL and parameters to POST.
            
            // Mocking a "Get Token" API call
            // $response = Http::post($this->baseUrl . '/initiate', $payload);
            // $token = $response->json()['token'];

            return [
                'success' => true,
                'url' => $this->baseUrl . '/pay', // The gateway payment page
                'method' => 'POST', // or GET
                'params' => $payload,
                'transaction_id' => $transactionId
            ];

        } catch (\Exception $e) {
            Log::error('Rayaz Payment Initiation Failed: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Verify the callback signature
     */
    public function verifySignature(array $data)
    {
        // Expected signature logic
        // Typically: Hash(merchant_id + order_id + status + secret_key)
        
        $orderId = $data['order_id'] ?? '';
        $status = $data['status'] ?? '';
        $amount = $data['amount'] ?? '';
        $receivedSignature = $data['signature'] ?? '';

        $payload = "{$this->merchantId}|{$orderId}|{$status}|{$amount}|{$this->secretKey}";
        $calculatedSignature = hash('sha256', $payload);

        return hash_equals($calculatedSignature, $receivedSignature);
    }
}
