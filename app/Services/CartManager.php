<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartManager
{
    private const MAX_QUANTITY = 99;
    private ?array $cachedCart = null;

    /**
     * Get the session key for the cart.
     */
    private function sessionKey(): string
    {
        return config('shop.cart_session_key');
    }

    /**
     * Read the current cart from the session.
     *
     * @return array<int|string, array{product_id: int, title: string, image: string, unit_price: float, quantity: int, line_total: float}>
     */
    private function getCart(): array
    {
        if ($this->cachedCart !== null) {
            return $this->cachedCart;
        }

        $lastOrderNumber = Session::get('last_order_number');
        if ($lastOrderNumber) {
            $order = \App\Models\Order::where('order_number', $lastOrderNumber)->first();
            if ($order) {
                // If it is Stripe and pending, try to verify with Stripe directly
                if ($order->payment_method === 'stripe' && $order->payment_status !== 'paid') {
                    $sessionId = $order->stripe_payment_intent_id;
                    if ($sessionId && str_starts_with($sessionId, 'cs_')) {
                        try {
                            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                            $session = \Stripe\Checkout\Session::retrieve($sessionId);
                            if ($session && $session->payment_status === 'paid') {
                                $order->update([
                                    'payment_status'           => 'paid',
                                    'stripe_payment_intent_id' => $session->payment_intent,
                                ]);
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Stripe verification in getCart failed: ' . $e->getMessage());
                        }
                    }
                }

                // If paid or COD, clear the cart in the session
                if ($order->payment_status === 'paid' || $order->payment_method === 'cod') {
                    Session::forget($this->sessionKey());
                    Session::forget(config('shop.coupon_session_key'));
                    Session::forget('last_order_number');
                    Session::save();
                    $this->cachedCart = [];
                    return [];
                }
            }
        }

        $this->cachedCart = Session::get($this->sessionKey(), []);
        return $this->cachedCart;
    }

    /**
     * Persist the cart back to the session.
     *
     * @param array $cart
     */
    private function putCart(array $cart): void
    {
        Session::put($this->sessionKey(), $cart);
        $this->cachedCart = $cart;
    }

    /**
     * Add a product to the cart.
     *
     * If the product is already in the cart its quantity is incremented.
     * Quantity is capped at 99.
     *
     * @param  array|null  $personalisation  Personalisation data for this product (name, dedication, custom fields).
     * @throws CartException if the product is not found or is inactive.
     */
    public function add(int $productId, int $quantity = 1, ?array $personalisation = null, string $type = 'product'): void
    {
        if ($type === 'gift') {
            $gift = \App\Models\Gift::find($productId);

            if (! $gift) {
                throw new CartException('This gift product is not available.');
            }

            $cart = $this->getCart();
            $cartKey = 'gift_' . $productId;

            if (isset($cart[$cartKey])) {
                throw new CartException('This gift is already in your cart.');
            } else {
                $unitPrice = round((float) $gift->price, 2);
                $cart[$cartKey] = [
                    'product_id'      => $gift->id,
                    'title'           => $gift->title,
                    'image'           => $gift->image_path ?? '',
                    'unit_price'      => $unitPrice,
                    'quantity'        => min($quantity, self::MAX_QUANTITY),
                    'line_total'      => round($unitPrice * min($quantity, self::MAX_QUANTITY), 2),
                    'type'            => 'gift',
                ];
            }

            $this->putCart($cart);
            return;
        }

        $product = Product::find($productId);

        if (! $product) {
            throw new CartException('This product is not available.');
        }

        if ($product->status !== true) {
            throw new CartException('This product is not available.');
        }

        $cart = $this->getCart();

        $cartKey = $productId;

        // Check if product is already in the cart
        foreach ($cart as $item) {
            if (($item['type'] ?? 'product') === 'product' && $item['product_id'] == $productId) {
                throw new CartException('This product is already in your cart.');
            }
        }

        $unitPrice = round((float) $product->price, 2);

        $cart[$cartKey] = [
            'product_id'      => $product->id,
            'title'           => $product->title,
            'image'           => $product->imageUrl ?? '',
            'unit_price'      => $unitPrice,
            'quantity'        => min($quantity, self::MAX_QUANTITY),
            'line_total'      => round($unitPrice * min($quantity, self::MAX_QUANTITY), 2),
            'personalisation' => $personalisation,
            'type'            => 'product',
        ];

        $this->putCart($cart);
    }

    /**
     * Update the quantity of an existing cart item.
     *
     * Removes the item if quantity is ≤ 0. Caps at 99.
     */
    public function update(int $productId, int $quantity, ?array $personalisation = null, string $type = 'product'): void
    {
        $cart = $this->getCart();

        if ($type === 'gift') {
            $cartKey = 'gift_' . $productId;
        } else {
            $cartKey = $productId;

            // If the specific key is not found, fallback to search by prefix if no specific personalisation is provided
            if (!isset($cart[$cartKey])) {
                foreach (array_keys($cart) as $key) {
                    if (($cart[$key]['type'] ?? 'product') === 'product' && ($key == $productId || str_starts_with((string) $key, $productId . '_'))) {
                        $cartKey = $key;
                        break;
                    }
                }
            }
        }

        if (!isset($cart[$cartKey])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } else {
            $quantity = min($quantity, self::MAX_QUANTITY);

            $cart[$cartKey]['quantity']   = $quantity;
            $cart[$cartKey]['line_total'] = round($cart[$cartKey]['unit_price'] * $quantity, 2);
        }

        $this->putCart($cart);
    }

    /**
     * Remove a single item from the cart by product ID.
     */
    public function remove(int $productId, ?array $personalisation = null, string $type = 'product'): void
    {
        $cart = $this->getCart();

        if ($type === 'gift') {
            $cartKey = 'gift_' . $productId;
            if (isset($cart[$cartKey])) {
                unset($cart[$cartKey]);
            }
        } else {
            $cartKey = $productId;

            if (isset($cart[$cartKey])) {
                unset($cart[$cartKey]);
            } else {
                // Remove all items matching the product ID if no specific personalisation is given
                foreach (array_keys($cart) as $key) {
                    if (($cart[$key]['type'] ?? 'product') === 'product' && ($key == $productId || str_starts_with((string) $key, $productId . '_'))) {
                        unset($cart[$key]);
                    }
                }
            }
        }

        $this->putCart($cart);
    }

    /**
     * Return all cart items as an array.
     *
     * @return array
     */
    public function items(): array
    {
        $items = $this->getCart();
        foreach ($items as &$item) {
            $type = $item['type'] ?? 'product';

            if ($type === 'gift') {
                $gift = \App\Models\Gift::find($item['product_id']);
                $item['description'] = $gift ? $gift->short_description : '';
                $item['type'] = 'gift';

                $defaultImage = $gift ? $gift->image_path : ($item['image'] ?? '');
                if (!empty($defaultImage)) {
                    $path = $defaultImage;
                    if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                        $normalised = '/' . ltrim($path, '/');
                        if (app()->runningInConsole() || !request()->getHost()) {
                            $item['image'] = rtrim(config('app.url'), '/') . $normalised;
                        } else {
                            $item['image'] = request()->getSchemeAndHttpHost() . $normalised;
                        }
                    } else {
                        $item['image'] = $path;
                    }
                } else {
                    $item['image'] = '';
                }
            } else {
                $product = Product::find($item['product_id']);
                $item['description'] = $product ? $product->description : '';
                $item['type'] = 'product';

                $defaultImage = $product ? $product->imageUrl : ($item['image'] ?? '');
                if (!empty($defaultImage)) {
                    $path = $defaultImage;
                    if (!str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
                        $normalised = '/' . ltrim($path, '/');
                        if (app()->runningInConsole() || !request()->getHost()) {
                            $item['image'] = rtrim(config('app.url'), '/') . $normalised;
                        } else {
                            $item['image'] = request()->getSchemeAndHttpHost() . $normalised;
                        }
                    } else {
                        $item['image'] = $path;
                    }
                } else {
                    $item['image'] = '';
                }
            }
            unset($item['personalisation']);
        }
        return array_values($items);
    }

    /**
     * Return the total number of items (sum of all quantities).
     */
    public function count(): int
    {
        return array_sum(array_column($this->getCart(), 'quantity'));
    }

    /**
     * Return the cart subtotal (sum of all line_totals), rounded to 2 decimal places.
     */
    public function subtotal(): float
    {
        $total = 0.0;

        foreach ($this->getCart() as $item) {
            $total += $item['line_total'];
        }

        return round($total, 2);
    }

    /**
     * Return true if the cart has no items.
     */
    public function isEmpty(): bool
    {
        return empty($this->getCart());
    }

    /**
     * Destroy the cart session key entirely.
     */
    public function clear(): void
    {
        Session::forget($this->sessionKey());
        $this->cachedCart = [];
    }
}
