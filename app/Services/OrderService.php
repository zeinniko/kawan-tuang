<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function previewCheckout(User $user, array $data): array
    {
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Keranjang belanja Anda kosong.'],
            ]);
        }

        // Kalkulasi subtotal dengan fallback ke harga produk jika unit_price bernilai 0/null
        $itemsSubtotal = $cart->items->sum(function ($item) {
            $price = (float) ($item->unit_price ?: optional($item->product)->price ?: 0);
            $qty = (int) $item->quantity;
            return $price * $qty;
        });

        $discountAmount = 0;

        if (! empty($data['voucher_code'])) {
            $voucher = Voucher::where('code', $data['voucher_code'])->first();
            $now = now();

            // Validasi voucher aktif & tanggal
            $isValid = $voucher 
                && (! $voucher->valid_from || $voucher->valid_from->isPast())
                && (! $voucher->valid_until || $voucher->valid_until->isFuture())
                && ($voucher->usage_limit === null || $voucher->usage_limit > 0);

            if ($isValid) {
                $minOrder = (float) ($voucher->min_order_amount ?? $voucher->min_spend ?? 0);

                if ($itemsSubtotal >= $minOrder) {
                    $discountVal = (float) $voucher->discount_value;
                    $maxDiscount = (float) ($voucher->max_discount_amount ?? $voucher->max_discount ?? 0);

                    if ($voucher->discount_type === 'percentage') {
                        $discountAmount = ($itemsSubtotal * $discountVal) / 100;
                        if ($maxDiscount > 0 && $discountAmount > $maxDiscount) {
                            $discountAmount = $maxDiscount;
                        }
                    } else {
                        $discountAmount = min($discountVal, $itemsSubtotal);
                    }
                }
            }
        }

        $shippingCost = (float) ($data['shipping_cost'] ?? 15000);
        $grandTotal = max(0, ($itemsSubtotal - $discountAmount)) + $shippingCost;

        return [
            'items_subtotal' => (float) $itemsSubtotal,
            'discount_amount' => (float) $discountAmount,
            'shipping_cost' => (float) $shippingCost,
            'grand_total' => (float) $grandTotal,
            'total_items' => $cart->items->sum('quantity'),
        ];
    }

    public function processCheckout(User $user, array $data): Order
    {
        if (! $user->is_age_verified) {
            throw ValidationException::withMessages([
                'age_verification' => ['Akun Anda belum terverifikasi 21+. Harap selesaikan KYC terlebih dahulu.'],
            ]);
        }

        $cart = $user->cart()->with('items.product')->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Keranjang belanja Anda kosong.'],
            ]);
        }

        return DB::transaction(function () use ($user, $cart, $data) {
            $preview = $this->previewCheckout($user, $data);

            $order = Order::create([
                'order_number' => 'TT-' . strtoupper(Str::random(4)) . '-' . date('YmdHis'),
                'user_id' => $user->id,
                'store_id' => $data['store_id'],
                'user_address_id' => $data['user_address_id'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_status' => 'pending',
                'total_items_price' => $preview['items_subtotal'],
                'discount_amount' => $preview['discount_amount'],
                'shipping_cost' => $preview['shipping_cost'],
                'grand_total' => $preview['grand_total'],
                'courier_company' => $data['courier_company'],
                'courier_type' => $data['courier_type'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cart->items as $item) {
                $unitPrice = (float) ($item->unit_price ?: optional($item->product)->price ?: 0);
                $qty = (int) $item->quantity;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => optional($item->product)->name ?? 'Produk',
                    'product_sku' => optional($item->product)->sku ?? '-',
                    'unit_price' => $unitPrice,
                    'quantity' => $qty,
                    'subtotal' => $qty * $unitPrice,
                ]);
            }

            // Kurangi usage_limit voucher jika voucher digunakan
            if (! empty($data['voucher_code']) && $preview['discount_amount'] > 0) {
                $voucher = Voucher::where('code', $data['voucher_code'])->first();
                if ($voucher && $voucher->usage_limit !== null) {
                    $voucher->decrement('usage_limit');
                }
            }

            $cart->items()->delete();

            return $order->load(['store', 'address', 'items']);
        });
    }

    public function getUserOrders(User $user, ?string $status = null): Collection
    {
        $query = $user->orders()->with(['store', 'address', 'items'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function cancelOrder(User $user, Order $order): Order
    {
        if ($order->user_id !== $user->id) {
            throw ValidationException::withMessages(['order' => ['Akses ditolak.']]);
        }

        if ($order->status !== 'pending' || $order->payment_status === 'paid') {
            throw ValidationException::withMessages([
                'order' => ['Pesanan yang sudah dibayar atau dalam proses pengiriman tidak dapat dibatalkan.'],
            ]);
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled',
        ]);

        return $order;
    }
}