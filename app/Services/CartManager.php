<?php

namespace App\Services;

use App\Exceptions\CartException;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartManager
{
    private const MAX_QUANTITY = 99;

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
        return Session::get($this->sessionKey(), []);
    }

    /**
     * Persist the cart back to the session.
     *
     * @param array $cart
     */
    private function putCart(array $cart): void
    {
        Session::put($this->sessionKey(), $cart);
    }

    /**
     * Add a product to the cart.
     *
     * If the product is already in the cart its quantity is incremented.
     * Quantity is capped at 99.
     *
     * @throws CartException if the product is not found or is inactive.
     */
    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::find($productId);

        if (! $product) {
            throw new CartException('This product is not available.');
        }

        if ($product->status !== true) {
            throw new CartException('This product is not available.');
        }

        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            $newQuantity = min($newQuantity, self::MAX_QUANTITY);

            $cart[$productId]['quantity']   = $newQuantity;
            $cart[$productId]['line_total'] = round($cart[$productId]['unit_price'] * $newQuantity, 2);
        } else {
            $unitPrice = round((float) $product->price, 2);

            $cart[$productId] = [
                'product_id' => $product->id,
                'title'      => $product->title,
                'image'      => $product->image ?? '',
                'unit_price' => $unitPrice,
                'quantity'   => min($quantity, self::MAX_QUANTITY),
                'line_total' => round($unitPrice * min($quantity, self::MAX_QUANTITY), 2),
            ];
        }

        $this->putCart($cart);
    }

    /**
     * Update the quantity of an existing cart item.
     *
     * Removes the item if quantity is ≤ 0. Caps at 99.
     */
    public function update(int $productId, int $quantity): void
    {
        $cart = $this->getCart();

        if (! isset($cart[$productId])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $quantity = min($quantity, self::MAX_QUANTITY);

            $cart[$productId]['quantity']   = $quantity;
            $cart[$productId]['line_total'] = round($cart[$productId]['unit_price'] * $quantity, 2);
        }

        $this->putCart($cart);
    }

    /**
     * Remove a single item from the cart by product ID.
     */
    public function remove(int $productId): void
    {
        $cart = $this->getCart();

        unset($cart[$productId]);

        $this->putCart($cart);
    }

    /**
     * Return all cart items as an array.
     *
     * @return array
     */
    public function items(): array
    {
        return array_values($this->getCart());
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
    }
}
