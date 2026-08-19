<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreateCart($user)
    {
        return Cart::with(['items.product.primaryImage', 'items.product.images'])
            ->firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, string $productId, int $quantity): Cart
    {
        $product = Product::findOrFail($productId);

        if (!$product->is_active) {
            throw ValidationException::withMessages([
                'product_id' => ['Produk sedang tidak tersedia.'],
            ]);
        }

        $cart = $this->getOrCreateCart($user);

        $cartItem = $cart->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
                'unit_price' => $product->price,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ]);
        }

        return $cart->fresh(['items.product.category', 'items.product.brand']);
    }

    public function updateItemQuantity(CartItem $cartItem, int $quantity): Cart
    {
        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->cart->load(['items.product.category', 'items.product.brand']);
    }

    public function removeItem(CartItem $cartItem): Cart
    {
        $cart = $cartItem->cart;
        $cartItem->delete();

        return $cart->load(['items.product.category', 'items.product.brand']);
    }

    public function clearCart(User $user): void
    {
        $cart = $user->cart;
        if ($cart) {
            $cart->items()->delete();
        }
    }
}