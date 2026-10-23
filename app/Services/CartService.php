<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CartService
{
    // Definisikan relasi standar termasuk storeStocks.store untuk cegah N+1 Query
    protected array $cartRelations = [
        'items.product.primaryImage',
        'items.product.images',
        'items.product.category',
        'items.product.brand',
        'items.product.storeStocks.store', // <--- Sangat penting untuk perhitungan harga & stok toko
    ];

    public function getOrCreateCart(User $user): Cart
    {
        // Pastikan relasi toko milik user ter-load
        $user->loadMissing('store');

        return Cart::with($this->cartRelations)
            ->firstOrCreate(['user_id' => $user->id]);
    }

    public function addItem(User $user, string $productId, int $quantity): Cart
    {
        $user->loadMissing('store');
    
        $product = Product::with('storeStocks')->findOrFail($productId);
    
        if (!$product->is_active) {
            throw ValidationException::withMessages([
                'product_id' => ['Produk sedang tidak tersedia.'],
            ]);
        }
    
        $cart = $this->getOrCreateCart($user);
        $cartItem = $cart->items()->where('product_id', $productId)->first();
    
        // 1. Validasi Stok Cabang Toko Aktif User
        if ($user->store_id) {
            $storeStock = $product->storeStocks->firstWhere('store_id', $user->store_id);
            $availableStock = $storeStock ? (int) $storeStock->stock : 0;
    
            $existingQty = $cartItem ? $cartItem->quantity : 0;
            $totalRequestedQty = $existingQty + $quantity;
    
            if ($availableStock < $totalRequestedQty) {
                throw ValidationException::withMessages([
                    'quantity' => ["Stok produk di cabang terpilih tidak mencukupi (Tersedia: {$availableStock})."],
                ]);
            }
        }
    
        // 2. Simpan unit_price menggunakan Harga Dasar Produk (Tanpa Markup)
        $basePrice = (float) $product->price;
    
        if ($cartItem) {
            $cartItem->update([
                'quantity'   => $cartItem->quantity + $quantity,
                'unit_price' => $basePrice,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'unit_price' => $basePrice,
            ]);
        }
    
        return $cart->fresh($this->cartRelations);
    }

    public function updateItemQuantity(CartItem $cartItem, int $quantity): Cart
    {
        $cart = $cartItem->cart;
        $user = $cart->user;

        // Validasi stok toko saat update quantity
        if ($user && $user->store_id) {
            $user->loadMissing('store');
            $storeStock = $cartItem->product->storeStocks()->where('store_id', $user->store_id)->first();
            $availableStock = $storeStock ? (int) $storeStock->stock : 0;

            if ($availableStock < $quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ["Stok produk di cabang terpilih tidak mencukupi (Tersedia: {$availableStock})."],
                ]);
            }
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cart->load($this->cartRelations);
    }

    public function removeItem(CartItem $cartItem): Cart
    {
        $cart = $cartItem->cart;
        $cartItem->delete();

        if ($cart->user) {
            $cart->user->loadMissing('store');
        }

        return $cart->load($this->cartRelations);
    }

    public function clearCart(User $user): void
    {
        $cart = $user->cart;
        if ($cart) {
            $cart->items()->delete();
        }
    }

    public function toggleItemSelect(CartItem $cartItem, bool $isSelected): Cart
    {
        $cartItem->update(['is_selected' => $isSelected]);

        $cart = $cartItem->cart;
        if ($cart->user) {
            $cart->user->loadMissing('store');
        }

        return $cart->load($this->cartRelations);
    }

    public function toggleAllSelect(User $user, bool $isSelected): Cart
    {
        $cart = $this->getOrCreateCart($user);
        $cart->items()->update(['is_selected' => $isSelected]);

        return $cart->fresh($this->cartRelations);
    }
}
