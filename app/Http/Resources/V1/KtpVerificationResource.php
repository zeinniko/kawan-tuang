<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class KtpVerificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'ktp_image_url' => $this->ktp_image_url ? Storage::disk('s3')->url($this->ktp_image_url) : null,
            'selfie_image_url' => $this->selfie_image_url ? Storage::disk('s3')->url($this->selfie_image_url) : null,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}