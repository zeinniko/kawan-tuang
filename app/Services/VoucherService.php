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
        return Voucher::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })
            ->latest()
            ->get();
    }

    public function applyVoucher(User $user, string $code): array
    {
        $voucher = Voucher::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $voucher || ($voucher->expired_at && $voucher->expired_at->isPast())) {
            throw ValidationException::withMessages([
                'code' => ['Kode voucher telah kedaluwarsa atau tidak aktif.'],
            ]);
        }

        $cart = $user->cart()->with('items')->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Keranjang belanja Anda masih kosong.'],
            ]);
        }

        $subtotal = $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price);

        if ($subtotal < $voucher->min_spend) {
            throw ValidationException::withMessages([
                'code' => ['Minimal pembelian untuk voucher ini adalah Rp ' . number_format($voucher->min_spend, 0, ',', '.')],
            ]);
        }

        $discountAmount = 0;
        if ($voucher->discount_type === 'percentage') {
            $discountAmount = ($subtotal * $voucher->discount_value) / 100;
            if ($voucher->max_discount && $discountAmount > $voucher->max_discount) {
                $discountAmount = $voucher->max_discount;
            }
        } else {
            $discountAmount = min($voucher->discount_value, $subtotal);
        }

        return [
            'voucher' => $voucher,
            'subtotal' => (float) $subtotal,
            'discount_amount' => (float) $discountAmount,
            'total_after_discount' => (float) max(0, $subtotal - $discountAmount),
        ];
    }
}