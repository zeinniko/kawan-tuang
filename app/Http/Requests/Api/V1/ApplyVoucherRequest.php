<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ApplyVoucherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'uppercase', 'exists:vouchers,code'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.exists' => 'Kode voucher tidak ditemukan atau sudah tidak berlaku.',
        ];
    }
}