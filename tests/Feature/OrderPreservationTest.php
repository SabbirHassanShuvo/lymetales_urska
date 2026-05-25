<?php

namespace Tests\Feature;

use App\Models\Coupon;
use App\Models\Order;
use App\Services\OrderGenerator;
use App\Services\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Exception\SignatureVerificationException;
use Tests\TestCase;

/**
 * Task 2 — Preservation Property Tests (run on UNFIXED code)
 *
 * These tests verify baseline behaviors that MUST be preserved after the fix.
 * All tests are expected to PASS on unfixed code.
 *
 * **Validates: Requirements 3.1, 3.3, 3.4, 3.5**
 */
class OrderPreservationTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build a minimal valid checkout data array for a COD order.
     */
    private function codCheckoutData(array $overrides = []): array
    {
        return array_merge([
            'email'                    => 'test@example.com',
            'full_name'                => 'Test User',
            'address'                  => '123 Main St',
            'city'                     => 'Amsterdam',
            'postal_code'              => '1000AA',
            'country'                  => 'Netherlands',
            'phone'                    => '+31612345678',
            'payment_method'           => 'cod',
            'fast_production'          => false,
            'stripe_payment_intent_id' => null,
        ], $overrides);
    }

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
     *
     * @param  int    $productId
     * @param  float  $unitPrice
     * @param  int    $quantity
     * @return array
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

    /**
     * Create a coupon in the database and return the session coupon array.
     */
    private function createCoupon(string $code, float $discount, bool $freeShipping = false): array
    {
        Coupon::create([
            'code'        => $code,
            'type'        => 'fixed',
            'value'       => $discount,
            'description' => 'Test coupon',
            'status'      => true,
            'used_count'  => 0,
        ]);

        return [
            'code'         => $code,
            'discount'     => $discount,
            'free_shipping' => $freeShipping,
        ];
    }

    // -------------------------------------------------------------------------
    // Property 2a: Financial Calculation Preservation (COD orders)
    //
    // For all COD orders with random cart items and optional coupons:
    //   subtotal = sum(line_total)
    //   total    = subtotal - discount + shipping_fee + fast_production_fee
    //
    // **Validates: Requirements 3.4**
    // -------------------------------------------------------------------------

    /**
     * Generate random cart configurations for property-based testing.
     * Returns arrays of [cartItems, coupon|null, fastProduction].
     */
    public static function randomCartProvider(): array
    {
        $cases = [];

        // Seed for reproducibility
        srand(42);

        for ($i = 0; $i < 30; $i++) {
            $itemCount = rand(1, 5);
            $cartItems = [];
            for ($j = 0; $j < $itemCount; $j++) {
                $unitPrice = round(rand(100, 10000) / 100, 2); // €1.00 – €100.00
                $quantity  = rand(1, 10);
                $cartItems[] = [
                    'product_id' => $j + 1,
                    'title'      => "Product " . ($j + 1),
                    'image'      => null,
                    'unit_price' => $unitPrice,
                    'quantity'   => $quantity,
                    'line_total' => round($unitPrice * $quantity, 2),
                ];
            }

            // Randomly apply a coupon (50% chance)
            $coupon = null;
            if (rand(0, 1) === 1) {
                $discount = round(rand(100, 1000) / 100, 2); // €1.00 – €10.00
                $freeShipping = (rand(0, 1) === 1);
                $coupon = [
                    'code'         => 'TESTCOUPON' . $i,
                    'discount'     => $discount,
                    'free_shipping' => $freeShipping,
                ];
            }

            $fastProduction = (rand(0, 1) === 1);

            $cases["case_{$i}"] = [$cartItems, $coupon, $fastProduction];
        }

        return $cases;
    }

    /**
     * Property 2a: For all COD orders with random cart items and optional coupons,
     * financial fields must match the expected calculation:
     *   subtotal = sum(line_total)
     *   total    = subtotal - discount + shipping_fee + fast_production_fee
     *
     * **Validates: Requirements 3.4**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('randomCartProvider')]
    public function test_cod_order_financial_calculations_are_correct(
        array $cartItems,
        ?array $coupon,
        bool $fastProduction
    ): void {
        // Create coupon in DB if needed
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

        $checkoutData = $this->codCheckoutData([
            'fast_production' => $fastProduction,
        ]);

        $generator = new OrderGenerator();
        $order     = $generator->create($checkoutData, $cartItems, $coupon);

        // --- Assert subtotal = sum(line_total) ---
        $expectedSubtotal = round(array_sum(array_column($cartItems, 'line_total')), 2);
        $this->assertEquals(
            $expectedSubtotal,
            (float) $order->subtotal,
            "subtotal must equal sum of line_totals"
        );

        // --- Assert shipping_fee ---
        $shippingFee = config('shop.shipping_fee', 5.95);
        if ($coupon && ($coupon['free_shipping'] ?? false)) {
            $expectedShipping = 0.00;
        } else {
            $expectedShipping = round((float) $shippingFee, 2);
        }
        $this->assertEquals(
            $expectedShipping,
            (float) $order->shipping_fee,
            "shipping_fee must be 0 when free_shipping coupon applied, otherwise config value"
        );

        // --- Assert fast_production_fee ---
        $fastFee = config('shop.fast_production_fee', 9.95);
        $expectedFastFee = $fastProduction ? round((float) $fastFee, 2) : 0.00;
        $this->assertEquals(
            $expectedFastFee,
            (float) $order->fast_production_fee,
            "fast_production_fee must match fast_production flag"
        );

        // --- Assert discount ---
        $expectedDiscount = $coupon ? round((float) ($coupon['discount'] ?? 0), 2) : 0.00;
        $this->assertEquals(
            $expectedDiscount,
            (float) $order->discount,
            "discount must match coupon discount"
        );

        // --- Assert total = subtotal - discount + shipping_fee + fast_production_fee ---
        $expectedTotal = round(
            $expectedSubtotal - $expectedDiscount + $expectedShipping + $expectedFastFee,
            2
        );
        $this->assertEquals(
            $expectedTotal,
            (float) $order->total,
            "total must equal subtotal - discount + shipping_fee + fast_production_fee"
        );
    }

    // -------------------------------------------------------------------------
    // Property 2b: Coupon used_count Incremented Exactly Once
    //
    // For all orders with a coupon applied, coupon->used_count is incremented
    // exactly once inside the same DB transaction.
    //
    // **Validates: Requirements 3.3**
    // -------------------------------------------------------------------------

    /**
     * Property 2b: When a coupon is applied, used_count is incremented exactly once.
     *
     * **Validates: Requirements 3.3**
     */
    public function test_coupon_used_count_incremented_exactly_once_on_cod_order(): void
    {
        $couponData = $this->createCoupon('SAVE5', 5.00);

        $cartItems = [
            $this->cartItem(1, 25.00, 2),
        ];

        $generator = new OrderGenerator();
        $generator->create($this->codCheckoutData(), $cartItems, $couponData);

        $coupon = Coupon::where('code', 'SAVE5')->first();
        $this->assertEquals(1, $coupon->used_count, "used_count must be incremented exactly once");
    }

    /**
     * Property 2b: Coupon used_count incremented exactly once across multiple random cart sizes.
     *
     * **Validates: Requirements 3.3**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('couponIncrementProvider')]
    public function test_coupon_used_count_incremented_exactly_once_for_various_carts(
        array $cartItems,
        float $discount
    ): void {
        $code       = 'COUPON_' . uniqid();
        $couponData = $this->createCoupon($code, $discount);

        $generator = new OrderGenerator();
        $generator->create($this->codCheckoutData(), $cartItems, $couponData);

        $coupon = Coupon::where('code', $code)->first();
        $this->assertEquals(
            1,
            $coupon->used_count,
            "used_count must be exactly 1 after one order with coupon"
        );
    }

    public static function couponIncrementProvider(): array
    {
        srand(99);
        $cases = [];
        for ($i = 0; $i < 10; $i++) {
            $itemCount = rand(1, 4);
            $cartItems = [];
            for ($j = 0; $j < $itemCount; $j++) {
                $unitPrice = round(rand(500, 5000) / 100, 2);
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
            $discount = round(rand(100, 500) / 100, 2);
            $cases["coupon_case_{$i}"] = [$cartItems, $discount];
        }
        return $cases;
    }

    /**
     * Property 2b: Coupon used_count is NOT incremented when no coupon is applied.
     *
     * **Validates: Requirements 3.3**
     */
    public function test_coupon_used_count_not_incremented_when_no_coupon(): void
    {
        $coupon = Coupon::create([
            'code'        => 'UNUSED',
            'type'        => 'fixed',
            'value'       => 5.00,
            'description' => 'Unused coupon',
            'status'      => true,
            'used_count'  => 0,
        ]);

        $cartItems = [$this->cartItem(1, 20.00, 1)];

        $generator = new OrderGenerator();
        $generator->create($this->codCheckoutData(), $cartItems, null);

        $coupon->refresh();
        $this->assertEquals(0, $coupon->used_count, "used_count must not change when no coupon applied");
    }

    // -------------------------------------------------------------------------
    // Unit Test: Invalid Stripe Webhook Signature Returns 400
    //
    // **Validates: Requirements 3.5**
    // -------------------------------------------------------------------------

    /**
     * Unit test: invalid Stripe webhook signature returns HTTP 400.
     *
     * **Validates: Requirements 3.5**
     */
    public function test_invalid_stripe_webhook_signature_returns_400(): void
    {
        // Mock StripeGateway to throw SignatureVerificationException
        $mockStripe = $this->createMock(StripeGateway::class);
        $mockStripe->method('constructWebhookEvent')
            ->willThrowException(
                new SignatureVerificationException('Invalid signature', null, null)
            );

        $this->app->instance(StripeGateway::class, $mockStripe);

        $response = $this->postJson('/api/stripe/webhook', [], [
            'Stripe-Signature' => 'invalid_signature_value',
        ]);

        $response->assertStatus(400);
    }

    /**
     * Unit test: completely missing Stripe signature header returns HTTP 400.
     *
     * **Validates: Requirements 3.5**
     */
    public function test_missing_stripe_webhook_signature_returns_400(): void
    {
        $mockStripe = $this->createMock(StripeGateway::class);
        $mockStripe->method('constructWebhookEvent')
            ->willThrowException(
                new SignatureVerificationException('No signature', null, null)
            );

        $this->app->instance(StripeGateway::class, $mockStripe);

        $response = $this->postJson('/api/stripe/webhook', []);

        $response->assertStatus(400);
    }

    // -------------------------------------------------------------------------
    // Unit Test: stripe_payment_intent_id Stored at Order Creation
    //
    // **Validates: Requirements 3.1**
    // -------------------------------------------------------------------------

    /**
     * Unit test: stripe_payment_intent_id is stored on the order at creation time.
     *
     * **Validates: Requirements 3.1**
     */
    public function test_stripe_payment_intent_id_stored_at_order_creation(): void
    {
        $intentId     = 'pi_test_xyz789';
        $checkoutData = $this->stripeCheckoutData([
            'stripe_payment_intent_id' => $intentId,
        ]);

        $cartItems = [$this->cartItem(1, 30.00, 1)];

        $generator = new OrderGenerator();
        $order     = $generator->create($checkoutData, $cartItems, null);

        $this->assertEquals(
            $intentId,
            $order->stripe_payment_intent_id,
            "stripe_payment_intent_id must be stored on the order at creation time"
        );
    }

    /**
     * Unit test: stripe_payment_intent_id is null for COD orders.
     *
     * **Validates: Requirements 3.1**
     */
    public function test_stripe_payment_intent_id_is_null_for_cod_orders(): void
    {
        $cartItems = [$this->cartItem(1, 20.00, 2)];

        $generator = new OrderGenerator();
        $order     = $generator->create($this->codCheckoutData(), $cartItems, null);

        $this->assertNull(
            $order->stripe_payment_intent_id,
            "stripe_payment_intent_id must be null for COD orders"
        );
    }

    /**
     * Unit test: stripe_payment_intent_id is preserved across different intent IDs.
     *
     * **Validates: Requirements 3.1**
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('stripeIntentIdProvider')]
    public function test_stripe_payment_intent_id_stored_for_various_intent_ids(string $intentId): void
    {
        $checkoutData = $this->stripeCheckoutData([
            'stripe_payment_intent_id' => $intentId,
        ]);

        $cartItems = [$this->cartItem(1, 15.00, 1)];

        $generator = new OrderGenerator();
        $order     = $generator->create($checkoutData, $cartItems, null);

        $this->assertEquals(
            $intentId,
            $order->stripe_payment_intent_id,
            "stripe_payment_intent_id '{$intentId}' must be stored exactly as provided"
        );
    }

    public static function stripeIntentIdProvider(): array
    {
        return [
            'standard_intent'  => ['pi_test_abc123'],
            'long_intent'      => ['pi_3NqXYZ1234567890abcdef'],
            'another_intent'   => ['pi_live_xyz987654321'],
            'short_intent'     => ['pi_abc'],
        ];
    }
}
