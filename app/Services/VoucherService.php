<?php

namespace App\Services;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    public function getAvailableVouchers(User $user): Collection
    {
        $now = now();

        return Voucher::where(function ($query) use ($now) {
                $query->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', $now);
            })
            ->where(function ($query) {
                $query->whereNull('usage_limit')
                    ->orWhere('usage_limit', '>', 0);
            })
            ->latest()
            ->get();
    }

    public function applyVoucher(User $user, string $code): array
    {
        $voucher = Voucher::where('code', $code)->first();

        // 1. Validasi Keberadaan dan Masa Berlaku
        if (! $voucher 
            || ($voucher->valid_from && $voucher->valid_from->isFuture()) 
            || ($voucher->valid_until && $voucher->valid_until->isPast())) {
            throw ValidationException::withMessages([
                'code' => ['Kode voucher tidak ditemukan atau sudah kedaluwarsa.'],
            ]);
        }

        // 2. Validasi Kuota Penggunaan
        if ($voucher->usage_limit !== null && $voucher->usage_limit <= 0) {
            throw ValidationException::withMessages([
                'code' => ['Kuota penggunaan voucher ini telah habis.'],
            ]);
        }

        // 3. Ambil Cart Beserta Item & Relasi Produknya
        $cart = $user->cart()->with(['items.product'])->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Keranjang belanja Anda masih kosong.'],
            ]);
        }

        // 4. Hitung Subtotal secara Presisi (Fallback ke harga produk jika unit_price 0/null)
        $subtotal = $cart->items->sum(function ($item) {
            $price = (float) ($item->unit_price ?: optional($item->product)->price ?: 0);
            $qty = (int) $item->quantity;
            return $price * $qty;
        });

        // 5. Validasi Minimal Pembelian (min_order_amount)
        $minOrder = (float) ($voucher->min_order_amount ?? $voucher->min_spend ?? 0);
        if ($minOrder > 0 && $subtotal < $minOrder) {
            throw ValidationException::withMessages([
                'code' => ['Minimal pembelian untuk voucher ini adalah Rp ' . number_format($minOrder, 0, ',', '.')],
            ]);
        }

        // 6. Hitung Nominal Diskon
        $discountAmount = 0;
        $discountVal = (float) $voucher->discount_value;
        $maxDiscount = (float) ($voucher->max_discount_amount ?? $voucher->max_discount ?? 0);

        if ($voucher->discount_type === 'percentage') {
            $discountAmount = ($subtotal * $discountVal) / 100;
            if ($maxDiscount > 0 && $discountAmount > $maxDiscount) {
                $discountAmount = $maxDiscount;
            }
        } else {
            $discountAmount = min($discountVal, $subtotal);
        }

        return [
            'voucher'              => $voucher,
            'subtotal'             => (float) $subtotal,
            'discount_amount'      => (float) $discountAmount,
            'total_after_discount' => (float) max(0, $subtotal - $discountAmount),
        ];
    }
}