<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Event as StripeEvent;
use Tests\TestCase;

/**
 * Task 4 — Checkpoint: Webhook and Admin Status Endpoint Tests
 *
 * Verifies:
 *   - checkout.session.completed sets payment_status = 'paid' without touching order_status
 *   - payment_intent.payment_failed sets payment_status = 'failed' without touching order_status
 *   - updateOrderStatus works for both COD and Stripe orders
 *   - updatePaymentStatus works for COD and returns 422 for Stripe
 *
 * **Validates: Requirements 2.3, 2.4, 2.5, 2.6, 2.7, 3.2, 3.6**
 */
class WebhookAndAdminStatusTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Create a minimal order in the database.
     */
    private function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number'             => 'LYM-TEST' . uniqid(),
            'order_status'             => 'pending',
            'payment_status'           => 'pending',
            'email'                    => 'test@example.com',
            'full_name'                => 'Test User',
            'address'                  => '123 Main St',
            'city'                     => 'Amsterdam',
            'postal_code'              => '1000AA',
            'country'                  => 'Netherlands',
            'phone'                    => '+31612345678',
            'items'                    => [['product_id' => 1, 'title' => 'Product 1', 'unit_price' => 20.00, 'quantity' => 1, 'line_total' => 20.00]],
            'subtotal'                 => 20.00,
            'shipping_fee'             => 5.95,
            'fast_production_fee'      => 0.00,
            'discount'                 => 0.00,
            'total'                    => 25.95,
            'payment_method'           => 'cod',
            'stripe_payment_intent_id' => null,
        ], $overrides));
    }

    /**
     * Create an admin user and return it.
     */
    private function createAdminUser(): User
    {
        return User::create([
            'first_name'   => 'Admin',
            'last_name'    => 'User',
            'email'        => 'admin@example.com',
            'password'     => bcrypt('password'),
            'role'         => 'admin',
            'status'       => 'active',
            'phone_number' => '+31600000000',
        ]);
    }

    /**
     * Build a mock Stripe Event for checkout.session.completed.
     */
    private function makeCheckoutSessionCompletedEvent(string $orderNumber, string $paymentIntentId): StripeEvent
    {
        $data = (object) [
            'object' => (object) [
                'metadata'       => (object) ['order_number' => $orderNumber],
                'payment_intent' => $paymentIntentId,
            ],
        ];

        $event       = $this->createMock(StripeEvent::class);
        $event->type = 'checkout.session.completed';
        $event->data = $data;

        return $event;
    }

    /**
     * Build a mock Stripe Event for payment_intent.payment_failed.
     */
    private function makePaymentFailedEvent(string $paymentIntentId): StripeEvent
    {
        $data = (object) [
            'object' => (object) [
                'id' => $paymentIntentId,
            ],
        ];

        $event       = $this->createMock(StripeEvent::class);
        $event->type = 'payment_intent.payment_failed';
        $event->data = $data;

        return $event;
    }

    // -------------------------------------------------------------------------
    // Webhook Tests: checkout.session.completed
    // -------------------------------------------------------------------------

    /**
     * checkout.session.completed sets payment_status = 'paid' without touching order_status.
     *
     * **Validates: Requirements 2.3, 3.2**
     */
    public function test_checkout_session_completed_sets_payment_status_paid_without_touching_order_status(): void
    {
        $intentId = 'pi_test_checkout_001';
        $order    = $this->createOrder([
            'payment_method'           => 'stripe',
            'stripe_payment_intent_id' => $intentId,
            'order_status'             => 'pending',
            'payment_status'           => 'pending',
        ]);

        $event = $this->makeCheckoutSessionCompletedEvent($order->order_number, $intentId);

        $mockStripe = $this->createMock(StripeGateway::class);
        $mockStripe->method('constructWebhookEvent')->willReturn($event);
        $this->app->instance(StripeGateway::class, $mockStripe);

        $response = $this->postJson('/api/stripe/webhook', [], [
            'Stripe-Signature' => 'valid_sig',
        ]);

        $response->assertStatus(200);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status,
            "payment_status must be 'paid' after checkout.session.completed");
        $this->assertEquals('pending', $order->order_status,
            "order_status must remain 'pending' — webhook must not touch order_status");
    }

    /**
     * checkout.session.completed is idempotent — does not re-update an already-paid order.
     *
     * **Validates: Requirements 2.3, 3.2**
     */
    public function test_checkout_session_completed_is_idempotent_for_already_paid_order(): void
    {
        $intentId = 'pi_test_checkout_002';
        $order    = $this->createOrder([
            'payment_method'           => 'stripe',
            'stripe_payment_intent_id' => $intentId,
            'order_status'             => 'processing',
            'payment_status'           => 'paid',
        ]);

        $event = $this->makeCheckoutSessionCompletedEvent($order->order_number, $intentId);

        $mockStripe = $this->createMock(StripeGateway::class);
        $mockStripe->method('constructWebhookEvent')->willReturn($event);
        $this->app->instance(StripeGateway::class, $mockStripe);

        $this->postJson('/api/stripe/webhook', [], ['Stripe-Signature' => 'valid_sig'])
            ->assertStatus(200);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status,
            "payment_status must remain 'paid' (idempotent)");
        $this->assertEquals('processing', $order->order_status,
            "order_status must remain 'processing' — webhook must not touch order_status");
    }

    // -------------------------------------------------------------------------
    // Webhook Tests: payment_intent.payment_failed
    // -------------------------------------------------------------------------

    /**
     * payment_intent.payment_failed sets payment_status = 'failed' without touching order_status.
     *
     * **Validates: Requirements 2.4, 3.2**
     */
    public function test_payment_intent_payment_failed_sets_payment_status_failed_without_touching_order_status(): void
    {
        $intentId = 'pi_test_failed_001';
        $order    = $this->createOrder([
            'payment_method'           => 'stripe',
            'stripe_payment_intent_id' => $intentId,
            'order_status'             => 'pending',
            'payment_status'           => 'pending',
        ]);

        $event = $this->makePaymentFailedEvent($intentId);

        $mockStripe = $this->createMock(StripeGateway::class);
        $mockStripe->method('constructWebhookEvent')->willReturn($event);
        $this->app->instance(StripeGateway::class, $mockStripe);

        $response = $this->postJson('/api/stripe/webhook', [], [
            'Stripe-Signature' => 'valid_sig',
        ]);

        $response->assertStatus(200);

        $order->refresh();
        $this->assertEquals('failed', $order->payment_status,
            "payment_status must be 'failed' after payment_intent.payment_failed");
        $this->assertEquals('pending', $order->order_status,
            "order_status must remain 'pending' — webhook must not touch order_status");
    }

    /**
     * payment_intent.payment_failed is idempotent — does not re-update an already-failed order.
     *
     * **Validates: Requirements 2.4, 3.2**
     */
    public function test_payment_intent_payment_failed_is_idempotent_for_already_failed_order(): void
    {
        $intentId = 'pi_test_failed_002';
        $order    = $this->createOrder([
            'payment_method'           => 'stripe',
            'stripe_payment_intent_id' => $intentId,
            'order_status'             => 'cancelled',
            'payment_status'           => 'failed',
        ]);

        $event = $this->makePaymentFailedEvent($intentId);

        $mockStripe = $this->createMock(StripeGateway::class);
        $mockStripe->method('constructWebhookEvent')->willReturn($event);
        $this->app->instance(StripeGateway::class, $mockStripe);

        $this->postJson('/api/stripe/webhook', [], ['Stripe-Signature' => 'valid_sig'])
            ->assertStatus(200);

        $order->refresh();
        $this->assertEquals('failed', $order->payment_status,
            "payment_status must remain 'failed' (idempotent)");
        $this->assertEquals('cancelled', $order->order_status,
            "order_status must remain 'cancelled' — webhook must not touch order_status");
    }

    // -------------------------------------------------------------------------
    // Admin Endpoint Tests: updateOrderStatus
    // -------------------------------------------------------------------------

    /**
     * updateOrderStatus updates order_status for a COD order.
     *
     * **Validates: Requirements 2.5, 3.6**
     */
    public function test_update_order_status_works_for_cod_order(): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder([
            'payment_method' => 'cod',
            'order_status'   => 'pending',
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/order-status", [
                'order_status' => 'shipped',
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'order_status' => 'shipped']);

        $order->refresh();
        $this->assertEquals('shipped', $order->order_status,
            "order_status must be updated to 'shipped'");
        $this->assertEquals('pending', $order->payment_status,
            "payment_status must remain unchanged");
    }

    /**
     * updateOrderStatus works for a Stripe order (fulfillment is admin-controlled regardless of payment method).
     *
     * **Validates: Requirements 2.5, 3.6**
     */
    public function test_update_order_status_works_for_stripe_order(): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder([
            'payment_method'           => 'stripe',
            'stripe_payment_intent_id' => 'pi_test_admin_001',
            'order_status'             => 'pending',
            'payment_status'           => 'paid',
        ]);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/order-status", [
                'order_status' => 'processing',
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'order_status' => 'processing']);

        $order->refresh();
        $this->assertEquals('processing', $order->order_status,
            "order_status must be updated to 'processing' for Stripe order");
        $this->assertEquals('paid', $order->payment_status,
            "payment_status must remain 'paid' — updateOrderStatus must not touch payment_status");
    }

    /**
     * updateOrderStatus rejects invalid order_status values.
     *
     * **Validates: Requirements 2.5**
     */
    public function test_update_order_status_rejects_invalid_values(): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder();

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/order-status", [
                'order_status' => 'invalid_status',
            ]);

        $response->assertStatus(422);
    }

    /**
     * updateOrderStatus accepts all valid order_status values.
     *
     * **Validates: Requirements 2.5**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('validOrderStatusProvider')]
    public function test_update_order_status_accepts_all_valid_values(string $status): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder();

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/order-status", [
                'order_status' => $status,
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'order_status' => $status]);
    }

    public static function validOrderStatusProvider(): array
    {
        return [
            'pending'    => ['pending'],
            'processing' => ['processing'],
            'shipped'    => ['shipped'],
            'delivered'  => ['delivered'],
            'cancelled'  => ['cancelled'],
        ];
    }

    // -------------------------------------------------------------------------
    // Admin Endpoint Tests: updatePaymentStatus
    // -------------------------------------------------------------------------

    /**
     * updatePaymentStatus updates payment_status for a COD order.
     *
     * **Validates: Requirements 2.6, 3.6**
     */
    public function test_update_payment_status_works_for_cod_order(): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder([
            'payment_method' => 'cod',
            'order_status'   => 'processing',
            'payment_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/payment-status", [
                'payment_status' => 'paid',
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'payment_status' => 'paid']);

        $order->refresh();
        $this->assertEquals('paid', $order->payment_status,
            "payment_status must be updated to 'paid' for COD order");
        $this->assertEquals('processing', $order->order_status,
            "order_status must remain unchanged");
    }

    /**
     * updatePaymentStatus returns 422 for Stripe orders (webhook-controlled).
     *
     * **Validates: Requirements 2.7, 3.6**
     */
    public function test_update_payment_status_returns_422_for_stripe_order(): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder([
            'payment_method'           => 'stripe',
            'stripe_payment_intent_id' => 'pi_test_admin_002',
            'order_status'             => 'pending',
            'payment_status'           => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/payment-status", [
                'payment_status' => 'paid',
            ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);

        $order->refresh();
        $this->assertEquals('pending', $order->payment_status,
            "payment_status must NOT be changed for Stripe orders");
    }

    /**
     * updatePaymentStatus rejects invalid payment_status values for COD orders.
     *
     * **Validates: Requirements 2.6**
     */
    public function test_update_payment_status_rejects_invalid_values_for_cod_order(): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder(['payment_method' => 'cod']);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/payment-status", [
                'payment_status' => 'invalid_status',
            ]);

        $response->assertStatus(422);
    }

    /**
     * updatePaymentStatus accepts all valid payment_status values for COD orders.
     *
     * **Validates: Requirements 2.6**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('validPaymentStatusProvider')]
    public function test_update_payment_status_accepts_all_valid_values_for_cod_order(string $status): void
    {
        $admin = $this->createAdminUser();
        $order = $this->createOrder(['payment_method' => 'cod']);

        $response = $this->actingAs($admin)
            ->patchJson("/admin/orders/{$order->id}/payment-status", [
                'payment_status' => $status,
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true, 'payment_status' => $status]);
    }

    public static function validPaymentStatusProvider(): array
    {
        return [
            'pending' => ['pending'],
            'paid'    => ['paid'],
            'failed'  => ['failed'],
        ];
    }

    /**
     * Admin endpoints require authentication — unauthenticated requests are redirected.
     *
     * **Validates: Requirements 2.5, 2.6**
     */
    public function test_admin_endpoints_require_authentication(): void
    {
        $order = $this->createOrder();

        $this->patchJson("/admin/orders/{$order->id}/order-status", ['order_status' => 'shipped'])
            ->assertStatus(302); // redirect to login

        $this->patchJson("/admin/orders/{$order->id}/payment-status", ['payment_status' => 'paid'])
            ->assertStatus(302); // redirect to login
    }
}
