<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable implements FilamentUser, HasName
{
    /** @use HasFactory<UserFactory> */
    use HasUuids, HasFactory, Notifiable;
    public $incrementing = false;
    protected $keyType = 'string';
    public const ROLE_SUPERADMIN      = 'superadmin';
    public const ROLE_ADMIN          = 'admin';
    public const ROLE_WAREHOUSE_STAFF = 'warehouse_staff';
    public const ROLE_CUSTOMER        = 'customer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'store_id',
        'full_name',
        'email',
        'phone_number',
        'password',
        'birth_date',
        'is_age_verified',
        'role',
        'avatar',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_age_verified' => 'boolean',
    ];

    public function pointLogs(): HasMany
    {
        return $this->hasMany(PointLog::class)->latest();
    }

    public function ktpVerification(): HasOne
    {
        return $this->hasOne(KtpVerification::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isWarehouseStaff(): bool
    {
        return $this->role === self::ROLE_WAREHOUSE_STAFF;
    }

    public function isCustomer(): bool
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    // Akses ke Panel Admin Filament (Superadmin, Admin, dan Warehouse Staff boleh masuk)
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [
            self::ROLE_SUPERADMIN,
            self::ROLE_ADMIN,
            self::ROLE_WAREHOUSE_STAFF,
        ]);
    }
    public function getFilamentName(): string
    {
        return $this->full_name ?? 'Anonymous';
    }
}
