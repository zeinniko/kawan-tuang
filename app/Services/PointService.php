<?php

namespace App\Services;

use App\Models\PointLog;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PointService
{
    /**
     * Mengambil total saldo poin pengguna.
     */
    public function getPoints(User|string $user): int
    {
        $userModel = $user instanceof User ? $user : User::findOrFail($user);
        return (int) $userModel->points;
    }

    /**
     * Menambahkan poin ke pengguna.
     *
     * @throws Exception
     */
    public function addPoints(User|string $user, int $amount, string $description = 'Penambahan Poin', ?string $referenceId = null): PointLog
    {
        if ($amount <= 0) {
            throw new Exception('Jumlah poin yang ditambahkan harus lebih besar dari 0.');
        }

        return DB::transaction(function () use ($user, $amount, $description, $referenceId) {
            $userModel = $user instanceof User 
                ? User::where('id', $user->id)->lockForUpdate()->firstOrFail() 
                : User::where('id', $user)->lockForUpdate()->firstOrFail();

            $userModel->increment('points', $amount);
            $userModel->refresh();

            return PointLog::create([
                'user_id'       => $userModel->id,
                'type'          => 'earn',
                'amount'        => $amount,
                'balance_after' => $userModel->points,
                'description'   => $description,
                'reference_id'  => $referenceId,
            ]);
        });
    }

    /**
     * Menggunakan / mengurangi poin pengguna.
     *
     * @throws Exception
     */
    public function usePoints(User|string $user, int $amount, string $description = 'Penggunaan Poin', ?string $referenceId = null): PointLog
    {
        if ($amount <= 0) {
            throw new Exception('Jumlah poin yang digunakan harus lebih besar dari 0.');
        }

        return DB::transaction(function () use ($user, $amount, $description, $referenceId) {
            $userModel = $user instanceof User 
                ? User::where('id', $user->id)->lockForUpdate()->firstOrFail() 
                : User::where('id', $user)->lockForUpdate()->firstOrFail();

            if ($userModel->points < $amount) {
                throw new Exception("Poin tidak mencukupi. Poin Anda: {$userModel->points}, dibutuhkan: {$amount}.");
            }

            $userModel->decrement('points', $amount);
            $userModel->refresh();

            return PointLog::create([
                'user_id'       => $userModel->id,
                'type'          => 'redeem',
                'amount'        => -$amount,
                'balance_after' => $userModel->points,
                'description'   => $description,
                'reference_id'  => $referenceId,
            ]);
        });
    }

    /**
     * Penyesuaian poin manual (bisa positif/negatif) oleh Admin.
     *
     * @throws Exception
     */
    public function adjustPoints(User|string $user, int $amount, string $description = 'Penyesuaian Poin oleh Admin', ?string $referenceId = null): PointLog
    {
        return DB::transaction(function () use ($user, $amount, $description, $referenceId) {
            $userModel = $user instanceof User 
                ? User::where('id', $user->id)->lockForUpdate()->firstOrFail() 
                : User::where('id', $user)->lockForUpdate()->firstOrFail();

            $newBalance = $userModel->points + $amount;

            if ($newBalance < 0) {
                throw new Exception('Penyesuaian gagal: Saldo poin tidak boleh negatif.');
            }

            $userModel->points = $newBalance;
            $userModel->save();

            return PointLog::create([
                'user_id'       => $userModel->id,
                'type'          => 'adjustment',
                'amount'        => $amount,
                'balance_after' => $userModel->points,
                'description'   => $description,
                'reference_id'  => $referenceId,
            ]);
        });
    }

    /**
     * Mengambil riwayat transaksi poin pengguna dengan pagination.
     */
    public function getHistory(User|string $user, int $perPage = 15): LengthAwarePaginator
    {
        $userId = $user instanceof User ? $user->id : $user;

        return PointLog::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
}