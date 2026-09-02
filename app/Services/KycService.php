<?php

namespace App\Services;

use App\Models\KtpVerification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;

class KycService
{
    public function __construct(protected StorageService $storageService) {}

    public function submitKyc(User $user, string $nik, UploadedFile $ktp, UploadedFile $selfie): KtpVerification
    {
        // Hapus file private lama di S3 jika user melakukan upload ulang (re-submit)
        if ($user->ktpVerification) {
            if ($user->ktpVerification->ktp_image_url) {
                $this->storageService->delete($user->ktpVerification->ktp_image_url, 'private');
            }
            if ($user->ktpVerification->selfie_image_url) {
                $this->storageService->delete($user->ktpVerification->selfie_image_url, 'private');
            }
        }

        // Upload ke S3 dengan visibilitas PRIVATE via StorageService
        $ktpPath = $this->storageService->upload($ktp, "kyc/{$user->id}/ktp", 'private');
        $selfiePath = $this->storageService->upload($selfie, "kyc/{$user->id}/selfie", 'private');

        return KtpVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik_encrypted'    => Crypt::encryptString($nik),
                'ktp_image_url'    => $ktpPath,
                'selfie_image_url' => $selfiePath,
                'status'           => 'pending',
                'rejection_reason' => null,
                'verified_at'       => null,
            ]
        );
    }
}