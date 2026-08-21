<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\UserAddress;
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
        $voucherId = null;

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
                    $voucherId = $voucher->id;
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

        $fulfillmentType = $data['fulfillment_type'] ?? 'delivery';
        $deliveryFee = $fulfillmentType === 'pickup' ? 0 : (float) ($data['delivery_fee'] ?? $data['shipping_cost'] ?? 15000);
        $adminFee = (float) ($data['admin_fee'] ?? 0);
        $totalAmount = max(0, ($itemsSubtotal - $discountAmount)) + $deliveryFee + $adminFee;

        return [
            'subtotal'        => (float) $itemsSubtotal,
            'discount_amount' => (float) $discountAmount,
            'delivery_fee'    => (float) $deliveryFee,
            'admin_fee'       => (float) $adminFee,
            'total_amount'    => (float) $totalAmount,
            'voucher_id'      => $voucherId,
            'total_items'     => $cart->items->sum('quantity'),
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

            // Ambil snapshot alamat jika memilih opsi delivery
            $addressSnapshot = null;
            if (! empty($data['user_address_id'])) {
                $address = UserAddress::find($data['user_address_id']);
                if ($address) {
                    $addressSnapshot = [
                        'recipient_name'  => $address->recipient_name ?? $address->receiver_name,
                        'recipient_phone' => $address->recipient_phone ?? $address->receiver_phone,
                        'full_address'    => $address->full_address,
                        'postal_code'     => $address->postal_code,
                        'latitude'        => $address->latitude,
                        'longitude'       => $address->longitude,
                        'notes'           => $address->notes,
                    ];
                }
            }

            $fulfillmentType = $data['fulfillment_type'] ?? 'delivery';

            $order = Order::create([
                'order_number'     => 'TT-' . strtoupper(Str::random(4)) . '-' . date('YmdHis'),
                'user_id'          => $user->id,
                'store_id'         => $data['store_id'],
                'voucher_id'       => $preview['voucher_id'],
                'fulfillment_type' => $fulfillmentType,
                'pickup_code'      => $fulfillmentType === 'pickup' ? strtoupper(Str::random(6)) : null,
                'subtotal'         => $preview['subtotal'],
                'discount_amount'  => $preview['discount_amount'],
                'delivery_fee'     => $preview['delivery_fee'],
                'admin_fee'        => $preview['admin_fee'],
                'total_amount'     => $preview['total_amount'],
                'status'           => Order::STATUS_PENDING_PAYMENT,
                'address_snapshot' => $addressSnapshot,
            ]);

            foreach ($cart->items as $item) {
                $unitPrice = (float) ($item->unit_price ?: optional($item->product)->price ?: 0);
                $qty = (int) $item->quantity;
            
                $order->items()->create([
                    'product_id'            => $item->product_id,
                    'product_name_snapshot' => optional($item->product)->name ?? 'Produk',
                    'unit_price'            => $unitPrice,
                    'quantity'               => $qty,
                    'subtotal_price'        => $qty * $unitPrice,
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

            return $order->load(['store', 'items', 'voucher']);
        });
    }

    public function getUserOrders(User $user, ?string $status = null): Collection
    {
        $query = $user->orders()->with(['store', 'items', 'payment', 'delivery'])->latest();

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

        if ($order->status !== Order::STATUS_PENDING_PAYMENT) {
            throw ValidationException::withMessages([
                'order' => ['Pesanan yang sudah diproses atau dibayar tidak dapat dibatalkan.'],
            ]);
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED,
        ]);

        return $order;
    }
}