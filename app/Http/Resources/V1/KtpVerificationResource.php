<?php

namespace App\Http\Resources\V1;

use App\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KtpVerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storageService = app(StorageService::class);

        return [
            'id'               => $this->id,
            'status'           => $this->status,
            'rejection_reason' => $this->rejection_reason,
            // Menghasilkan Temporary Signed URL S3 (Privat, kadaluarsa dalam 60 menit)
            'ktp_image_url'    => $storageService->getUrl($this->ktp_image_url, 'private', 60),
            'selfie_image_url' => $storageService->getUrl($this->selfie_image_url, 'private', 60),
            'verified_at'      => $this->verified_at?->toIso8601String(),
            'created_at'       => $this->created_at?->toIso8601String(),
        ];
    }
}