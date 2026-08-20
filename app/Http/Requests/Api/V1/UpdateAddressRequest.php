<?php

namespace App\Http\Requests\Api\V1;

use App\Models\UserAddress;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        $address = $this->route('address');

        // Jika $address berupa string ID (efek InternalApiService), cari modelnya terlebih dahulu
        if (is_string($address)) {
            $address = UserAddress::find($address);
        }

        return $address && $address->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'label'           => ['required', 'string', 'max:50'],
            'recipient_name'  => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'full_address'    => ['required', 'string', 'max:500'],
            'notes'           => ['nullable', 'string', 'max:255'],
            'postal_code'     => ['nullable', 'string', 'max:10'], // Diubah ke nullable agar aman
            'latitude'        => ['required', 'numeric', 'between:-90,90'],
            'longitude'       => ['required', 'numeric', 'between:-180,180'],
            'is_primary'      => ['sometimes', 'boolean'],
        ];
    }
}