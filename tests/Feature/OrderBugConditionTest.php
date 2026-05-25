<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Services\OrderGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Task 1 — Bug Condition Exploration Test
 *
 * Property 1: Bug Condition — Stripe Order Created With `paid` Status
 *
 * This test encodes the EXPECTED behavior after the fix:
 *   - order_status = 'pending' immediately after creation for Stripe orders
 *   - payment_status = 'pending' immediately after creation for Stripe orders
 *   - status (legacy) is NOT set to 'paid' at creation time
 *
 * On UNFIXED code this test FAILS (confirming the bug exists).
 * On FIXED code this test PASSES (confirming the bug is resolved).
 *
 * **Validates: Requirements 1.1, 2.1**
 */
class OrderBugConditionTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build a minimal valid checkout data array for a Stripe order.
     */
    private function stripeCheckoutData(array $overrides = []): array
    {
        return array_merge([
            'email'                    => 'stripe@example.com',
            'full_name'                => 'Stripe User',
            'address'                  => '456 Stripe Ave',
            'city'                     => 'Rotterdam',
            'postal_code'              => '3000AB',
            'country'                  => 'Netherlands',
            'phone'                    => '+31698765432',
            'payment_method'           => 'stripe',
            'fast_production'          => false,
            'stripe_payment_intent_id' => 'pi_test_abc123',
        ], $overrides);
    }

    /**
     * Build a cart item array.
     */
    private function cartItem(int $productId, float $unitPrice, int $quantity): array
    {
        return [
            'product_id' => $productId,
            'title'      => "Product {$productId}",
            'image'      => null,
            'unit_price' => $unitPrice,
            'quantity'   => $quantity,
            'line_total' => round($unitPrice * $quantity, 2),
        ];
    }

    // -------------------------------------------------------------------------
    // Property 1: Bug Condition — Stripe Order Created With Correct Pending Statuses
    //
    // For all Stripe orders with any valid cart and coupon combination:
    //   order_status  = 'pending' immediately after creation (before webhook fires)
    //   payment_status = 'pending' immediately after creation (before webhook fires)
    //   status (legacy) != 'paid' at creation time
    //
    // **Validates: Requirements 1.1, 2.1**
    // -------------------------------------------------------------------------

    /**
     * Property 1: Stripe order created with order_status = 'pending' (no coupon).
     *
     * **Validates: Requirements 1.1, 2.1**
     */
    public function test_stripe_order_starts_with_pending_order_status_no_coupon(): void
    {
        $cartItems = [$this->cartItem(1, 29.99, 1)];

        $generator = new OrderGenerator();
        $order     = $generator->create($this->stripeCheckoutData(), $cartItems, null);

        $this->assertEquals(
            'pending',
            $order->order_status,
            "order_status must be 'pending' immediately after Stripe order creation (before webhook fires)"
        );

        $this->assertEquals(
            'pending',
            $order->payment_status,
            "payment_status must be 'pending' immediately after Stripe order creation (before webhook fires)"
        );

        $this->assertNotEquals(
            'paid',
            $order->status ?? null,
            "legacy status column must NOT be set to 'paid' at order creation time"
        );
    }

    /**
     * Property 1: Stripe order created with order_status = 'pending' (with coupon).
     *
     * **Validates: Requirements 1.1, 2.1**
     */
    public function test_stripe_order_starts_with_pending_order_status_with_coupon(): void
    {
        Coupon::create([
            'code'        => 'DISCOUNT10',
            'type'        => 'fixed',
            'value'       => 10.00,
            'description' => 'Test coupon',
            'status'      => true,
            'used_count'  => 0,
        ]);

        $coupon = [
            'code'         => 'DISCOUNT10',
            'discount'     => 10.00,
            'free_shipping' => false,
        ];

        $cartItems = [
            $this->cartItem(1, 50.00, 1),
            $this->cartItem(2, 25.00, 2),
        ];

        $generator = new OrderGenerator();
        $order     = $generator->create($this->stripeCheckoutData(), $cartItems, $coupon);

        $this->assertEquals(
            'pending',
            $order->order_status,
            "order_status must be 'pending' for Stripe order with coupon"
        );

        $this->assertEquals(
            'pending',
            $order->payment_status,
            "payment_status must be 'pending' for Stripe order with coupon"
        );

        $this->assertNotEquals(
            'paid',
            $order->status ?? null,
            "legacy status must NOT be 'paid' at creation time even with coupon"
        );
    }

    /**
     * Property 1: Stripe order with fast production still starts with pending statuses.
     *
     * **Validates: Requirements 1.1, 2.1**
     */
    public function test_stripe_order_with_fast_production_starts_with_pending_statuses(): void
    {
        $cartItems = [$this->cartItem(1, 45.00, 3)];

        $checkoutData = $this->stripeCheckoutData([
            'fast_production' => true,
        ]);

        $generator = new OrderGenerator();
        $order     = $generator->create($checkoutData, $cartItems, null);

        $this->assertEquals(
            'pending',
            $order->order_status,
            "order_status must be 'pending' for Stripe order with fast production"
        );

        $this->assertEquals(
            'pending',
            $order->payment_status,
            "payment_status must be 'pending' for Stripe order with fast production"
        );
    }

    /**
     * Property 1 (data-driven): Stripe orders with various cart configurations
     * always start with pending statuses.
     *
     * **Validates: Requirements 1.1, 2.1**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('stripeCartProvider')]
    public function test_stripe_order_always_starts_with_pending_statuses(
        array $cartItems,
        ?array $coupon,
        bool $fastProduction
    ): void {
        if ($coupon !== null) {
            Coupon::create([
                'code'        => $coupon['code'],
                'type'        => 'fixed',
                'value'       => $coupon['discount'],
                'description' => 'Test coupon',
                'status'      => true,
                'used_count'  => 0,
            ]);
        }

        $checkoutData = $this->stripeCheckoutData([
            'fast_production' => $fastProduction,
        ]);

        $generator = new OrderGenerator();
        $order     = $generator->create($checkoutData, $cartItems, $coupon);

        $this->assertEquals(
            'pending',
            $order->order_status,
            "order_status must be 'pending' immediately after Stripe order creation"
        );

        $this->assertEquals(
            'pending',
            $order->payment_status,
            "payment_status must be 'pending' immediately after Stripe order creation"
        );

        $this->assertNotEquals(
            'paid',
            $order->status ?? null,
            "legacy status must NOT be 'paid' at Stripe order creation time"
        );
    }

    public static function stripeCartProvider(): array
    {
        srand(77);
        $cases = [];

        for ($i = 0; $i < 20; $i++) {
            $itemCount = rand(1, 4);
            $cartItems = [];
            for ($j = 0; $j < $itemCount; $j++) {
                $unitPrice = round(rand(200, 8000) / 100, 2);
                $quantity  = rand(1, 5);
                $cartItems[] = [
                    'product_id' => $j + 1,
                    'title'      => "Product " . ($j + 1),
                    'image'      => null,
                    'unit_price' => $unitPrice,
                    'quantity'   => $quantity,
                    'line_total' => round($unitPrice * $quantity, 2),
                ];
            }

            $coupon = null;
            if (rand(0, 1) === 1) {
                $discount = round(rand(100, 500) / 100, 2);
                $freeShipping = (rand(0, 1) === 1);
                $coupon = [
                    'code'         => 'STRIPE_COUPON_' . $i,
                    'discount'     => $discount,
                    'free_shipping' => $freeShipping,
                ];
            }

            $fastProduction = (rand(0, 1) === 1);

            $cases["stripe_case_{$i}"] = [$cartItems, $coupon, $fastProduction];
        }

        return $cases;
    }
}
