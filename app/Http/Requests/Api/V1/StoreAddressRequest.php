<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label'           => ['required', 'string', 'max:50'],
            'recipient_name'  => ['required', 'string', 'max:100'],
            'recipient_phone' => ['required', 'string', 'max:20'],
            'address'         => ['required', 'string', 'max:500'],
            'notes'           => ['nullable', 'string', 'max:255'],
            'postal_code'     => ['nullable', 'string', 'max:10'], // Diubah ke nullable
            'latitude'        => ['required', 'numeric', 'between:-90,90'],
            'longitude'       => ['required', 'numeric', 'between:-180,180'],
            'is_primary'      => ['sometimes', 'boolean'],
        ];
    }
}