<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Memastikan alamat yang diubah milik user yang terautentikasi
        $address = $this->route('address');
        return $address && $address->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:50'],
            'receiver_name' => ['required', 'string', 'max:100'],
            'receiver_phone' => ['required', 'string', 'max:20'],
            'full_address' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:10'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'is_primary' => ['sometimes', 'boolean'],
        ];
    }
}