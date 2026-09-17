<?php

namespace App\Observers;

use App\Models\KtpVerification;
use App\Notifications\UserPushNotification;

class KtpVerificationObserver
{
    /**
     * Trigger saat Status KYC diubah oleh Admin Filament
     */
    public function updated(KtpVerification $ktpVerification): void
    {
        if ($ktpVerification->wasChanged('status')) {
            $user = $ktpVerification->user;

            if (!$user) return;

            $status = $ktpVerification->status;
            $isApproved = in_array($status, ['approved', 'accepted']);

            if ($isApproved || $status === 'rejected') {
                $user->notify(new UserPushNotification(
                    title: $isApproved ? 'KYC Disetujui 🎉' : 'KYC Ditolak ❌',
                    body: $isApproved
                        ? 'Selamat, verifikasi identitas (21+) kamu telah berhasil!'
                        : ($ktpVerification->rejection_reason ?? 'Mohon maaf, pengajuan KYC kamu ditolak. Silakan cek kembali dokumen Anda.'),
                    data: [
                        'type' => 'kyc_update',
                        'status' => $status,
                    ]
                ));
            }
        }
    }
}