<?php

namespace App\Services;

use App\Models\KtpVerification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;

class KycService
{
    public function submitKyc(User $user, string $nik, UploadedFile $ktp, UploadedFile $selfie): KtpVerification
    {
        // Upload ke AWS S3 / Cloud Storage
        $ktpPath = $ktp->store("kyc/{$user->id}/ktp", 's3');
        $selfiePath = $selfie->store("kyc/{$user->id}/selfie", 's3');

        return KtpVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'nik_encrypted' => Crypt::encryptString($nik),
                'ktp_image_url' => $ktpPath,
                'selfie_image_url' => $selfiePath,
                'status' => 'pending',
                'rejection_reason' => null,
                'verified_at' => null,
            ]
        );
    }
}