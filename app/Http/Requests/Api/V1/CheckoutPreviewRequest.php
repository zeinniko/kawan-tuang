<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPreviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'store_id' => ['required', 'string', 'exists:stores,id'],
            'user_address_id' => ['required', 'string', 'exists:user_addresses,id'],
            'courier_company' => ['required', 'string'],
            'courier_type' => ['required', 'string'],
            'shipping_cost' => ['sometimes', 'numeric', 'min:0'],
            'voucher_code' => ['nullable', 'string', 'exists:vouchers,code'],
        ];
    }
}