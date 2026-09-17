<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Store extends Model
{
    use HasUuids;

    protected $fillable = [
        'store_code',
        'name',
        'address',
        'latitude',
        'longitude',
        'phone_number',
        'open_time',
        'close_time',
        'is_pickup_active',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_pickup_active' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Cek apakah toko sedang buka berdasarkan status is_active & jam operasional
     */
    public function isOpen(): bool
    {
        // 1. Jika toko di-nonaktifkan secara manual oleh admin
        if (! $this->is_active) {
            return false;
        }

        // 2. Jika jam operasional tidak diisi, anggap toko selalu buka
        if (empty($this->open_time) || empty($this->close_time)) {
            return true;
        }

        $currentTime = now()->format('H:i:s');
        $open = Carbon::parse($this->open_time)->format('H:i:s');
        $close = Carbon::parse($this->close_time)->format('H:i:s');

        // Jam operasional normal (contoh: 08:00 - 22:00)
        if ($open <= $close) {
            return $currentTime >= $open && $currentTime <= $close;
        }

        // Jam operasional melewati tengah malam (contoh: 18:00 - 02:00)
        return $currentTime >= $open || $currentTime <= $close;
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(StoreStock::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
