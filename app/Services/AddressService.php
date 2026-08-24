<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    public function getUserAddresses(User $user): Collection
    {
        return $user->addresses()->orderByDesc('is_primary')->latest()->get();
    }

    public function createAddress(User $user, array $data): UserAddress
    {
        // Jika belum ada alamat sama sekali, otomatis jadikan alamat utama
        if ($user->addresses()->count() === 0) {
            $data['is_primary'] = true;
        }

        if (!empty($data['is_primary']) && $data['is_primary']) {
            $user->addresses()->update(['is_primary' => false]);
        }

        return $user->addresses()->create($data);
    }

    public function updateAddress(User $user, UserAddress $address, array $data): UserAddress
    {
        if (!empty($data['is_primary']) && $data['is_primary']) {
            $user->addresses()->where('id', '!=', $address->id)->update(['is_primary' => false]);
        }

        $address->update($data);

        if ($address->wasChanged(['latitude', 'longitude'])) {
            DB::table('shipping_rate_caches')
                ->where('user_address_id', $address->id)
                ->delete();
        }

        return $address;
    }

    public function deleteAddress(UserAddress $address): void
    {
        $user = $address->user;
        $isPrimary = $address->is_primary;

        $address->delete();

        // Jika alamat utama dihapus, atur alamat terbaru yang tersisa menjadi alamat utama
        if ($isPrimary) {
            $latestAddress = $user->addresses()->latest()->first();
            $latestAddress?->update(['is_primary' => true]);
        }
    }

    public function setPrimaryAddress(User $user, UserAddress $address): UserAddress
    {
        $user->addresses()->update(['is_primary' => false]);
        $address->update(['is_primary' => true]);

        return $address;
    }
}