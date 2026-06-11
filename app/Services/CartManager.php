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
     * @param  array|null  $personalisation  Personalisation data for this product (name, dedication, custom fields).
     * @throws CartException if the product is not found or is inactive.
     */
    public function add(int $productId, int $quantity = 1, ?array $personalisation = null): void
    {
        $product = Product::find($productId);

        if (! $product) {
            throw new CartException('This product is not available.');
        }

        if ($product->status !== true) {
            throw new CartException('This product is not available.');
        }

        $cart = $this->getCart();

        // Check if the product is already in the cart
        foreach ($cart as $item) {
            if ($item['product_id'] === $productId) {
                throw new CartException('This product is already in your cart.');
            }
        }

        // Cart key: if personalisation is provided, make item unique per personalisation
        // so the same product can appear multiple times with different names/options.
        $cartKey = $personalisation
            ? $productId . '_' . md5(json_encode($personalisation))
            : $productId;

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            $newQuantity = min($newQuantity, self::MAX_QUANTITY);

            $cart[$cartKey]['quantity']   = $newQuantity;
            $cart[$cartKey]['line_total'] = round($cart[$cartKey]['unit_price'] * $newQuantity, 2);
        } else {
            $unitPrice = round((float) $product->price, 2);

            $cart[$cartKey] = [
                'product_id'      => $product->id,
                'title'           => $product->title,
                'image'           => $product->imageUrl ?? '',
                'unit_price'      => $unitPrice,
                'quantity'        => min($quantity, self::MAX_QUANTITY),
                'line_total'      => round($unitPrice * min($quantity, self::MAX_QUANTITY), 2),
                'personalisation' => $personalisation,
            ];
        }

        $this->putCart($cart);
    }

    /**
     * Update the quantity of an existing cart item.
     *
     * Removes the item if quantity is ≤ 0. Caps at 99.
     */
    public function update(int $productId, int $quantity, ?array $personalisation = null): void
    {
        $cart = $this->getCart();

        $cartKey = $personalisation
            ? $productId . '_' . md5(json_encode($personalisation))
            : $productId;

        // If the specific key is not found, fallback to search by prefix if no specific personalisation is provided
        if (!isset($cart[$cartKey]) && !$personalisation) {
            foreach (array_keys($cart) as $key) {
                if ($key == $productId || str_starts_with((string) $key, $productId . '_')) {
                    $cartKey = $key;
                    break;
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
    public function remove(int $productId, ?array $personalisation = null): void
    {
        $cart = $this->getCart();

        $cartKey = $personalisation
            ? $productId . '_' . md5(json_encode($personalisation))
            : $productId;

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
        } else if (!$personalisation) {
            // Remove all items matching the product ID if no specific personalisation is given
            foreach (array_keys($cart) as $key) {
                if ($key == $productId || str_starts_with((string) $key, $productId . '_')) {
                    unset($cart[$key]);
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
            $product = Product::find($item['product_id']);
            $item['description'] = $product ? $product->description : '';

            if (!empty($item['personalisation']) && !empty($item['personalisation']['preview_image'])) {
                $path = $item['personalisation']['preview_image'];
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
    }
}
