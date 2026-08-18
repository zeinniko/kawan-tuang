<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutProcessRequest extends FormRequest
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
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string'], // e.g. qris, gopay, bank_transfer
            'voucher_code' => ['nullable', 'string', 'exists:vouchers,code'],
            'notes' => ['nullable', 'string', 'max:255'],
        ];
    }
}