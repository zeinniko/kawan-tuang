<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadKycRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'digits:16'],
            'ktp_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB
            'selfie_image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
        ];
    }
}