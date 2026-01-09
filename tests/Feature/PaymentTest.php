<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Services\RazorpayService;
use Mockery;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private function createPendingOrder(User $user): Order
    {
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_method' => 'razorpay',
            'payment_status' => 'pending',
            'razorpay_order_id' => 'order_test123',
        ]);

        ShippingAddress::factory()->create(['order_id' => $order->id]);

        return $order;
    }

    public function test_payment_callback_requires_authentication(): void
    {
        $response = $this->post(route('payment.callback'), [
            'razorpay_payment_id' => 'pay_test123',
            'razorpay_order_id' => 'order_test123',
            'razorpay_signature' => 'test_signature',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_successful_payment_callback_updates_order(): void
    {
        $user = User::factory()->create();
        $order = $this->createPendingOrder($user);

        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyPaymentSignature')
                ->once()
                ->andReturn(true);
        });

        $response = $this->actingAs($user)->post(route('payment.callback'), [
            'razorpay_payment_id' => 'pay_test456',
            'razorpay_order_id' => 'order_test123',
            'razorpay_signature' => 'valid_signature',
        ]);

        $response->assertRedirect(route('order.success', $order->id));

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('pay_test456', $order->razorpay_payment_id);
    }

    public function test_payment_callback_fails_with_invalid_signature(): void
    {
        $user = User::factory()->create();
        $this->createPendingOrder($user);

        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyPaymentSignature')
                ->once()
                ->andReturn(false);
        });

        $response = $this->actingAs($user)->post(route('payment.callback'), [
            'razorpay_payment_id' => 'pay_test456',
            'razorpay_order_id' => 'order_test123',
            'razorpay_signature' => 'invalid_signature',
        ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error');
    }

    public function test_payment_callback_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('payment.callback'), []);

        // Should fail validation and redirect with errors or return error response
        $this->assertTrue(
            $response->status() === 422 ||
            $response->isRedirect() ||
            $response->status() === 302
        );
    }

    public function test_payment_failed_route_updates_order_status(): void
    {
        $user = User::factory()->create();
        $order = $this->createPendingOrder($user);

        $response = $this->actingAs($user)->get(route('payment.failed', ['order_id' => $order->id]));

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('error');

        $this->assertEquals('failed', $order->fresh()->payment_status);
    }

    public function test_webhook_validates_signature(): void
    {
        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')
                ->once()
                ->andReturn(false);
        });

        $response = $this->postJson(route('payment.webhook'), [
            'event' => 'payment.captured',
        ], [
            'X-Razorpay-Signature' => 'invalid_signature',
        ]);

        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid signature']);
    }

    public function test_webhook_handles_payment_captured_event(): void
    {
        $user = User::factory()->create();
        $order = $this->createPendingOrder($user);

        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')
                ->once()
                ->andReturn(true);
        });

        $response = $this->postJson(route('payment.webhook'), [
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_webhook123',
                        'order_id' => 'order_test123',
                    ],
                ],
            ],
        ], [
            'X-Razorpay-Signature' => 'valid_signature',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('pay_webhook123', $order->razorpay_payment_id);
    }

    public function test_webhook_handles_payment_failed_event(): void
    {
        $user = User::factory()->create();
        $order = $this->createPendingOrder($user);

        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')
                ->once()
                ->andReturn(true);
        });

        $response = $this->postJson(route('payment.webhook'), [
            'event' => 'payment.failed',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'order_id' => 'order_test123',
                        'error_description' => 'Insufficient funds',
                    ],
                ],
            ],
        ], [
            'X-Razorpay-Signature' => 'valid_signature',
        ]);

        $response->assertStatus(200);

        $this->assertEquals('failed', $order->fresh()->payment_status);
    }

    public function test_webhook_handles_refund_processed_event(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'payment_status' => 'paid',
            'razorpay_payment_id' => 'pay_original123',
        ]);

        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')
                ->once()
                ->andReturn(true);
        });

        $response = $this->postJson(route('payment.webhook'), [
            'event' => 'refund.processed',
            'payload' => [
                'refund' => [
                    'entity' => [
                        'id' => 'rfnd_123',
                        'payment_id' => 'pay_original123',
                        'amount' => 100000,
                    ],
                ],
            ],
        ], [
            'X-Razorpay-Signature' => 'valid_signature',
        ]);

        $response->assertStatus(200);

        $this->assertEquals('refunded', $order->fresh()->payment_status);
    }

    public function test_webhook_ignores_unhandled_events(): void
    {
        // Mock Razorpay service
        $this->mock(RazorpayService::class, function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')
                ->once()
                ->andReturn(true);
        });

        $response = $this->postJson(route('payment.webhook'), [
            'event' => 'some.unknown.event',
        ], [
            'X-Razorpay-Signature' => 'valid_signature',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'success']);
    }
}
