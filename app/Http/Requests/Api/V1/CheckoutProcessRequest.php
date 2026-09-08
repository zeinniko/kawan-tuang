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
            'fulfillment_type' => ['required', 'string', 'in:delivery,pickup'],
            'store_id'         => ['required', 'string', 'exists:stores,id'],
            
            'user_address_id'  => ['required_if:fulfillment_type,delivery', 'nullable', 'string', 'exists:user_addresses,id'],
            'courier_company'  => ['required_if:fulfillment_type,delivery', 'nullable', 'string'],
            'courier_type'     => ['required_if:fulfillment_type,delivery', 'nullable', 'string'],
            'shipping_cost'    => ['required_if:fulfillment_type,delivery', 'nullable', 'numeric', 'min:0'],
            
            'payment_method'   => ['required', 'string'],
            'voucher_code'     => ['nullable', 'string', 'exists:vouchers,code'],
            'notes'            => ['nullable', 'string', 'max:255'],
        ];
    }
}