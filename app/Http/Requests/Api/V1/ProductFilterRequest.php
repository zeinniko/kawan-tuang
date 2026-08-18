<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:100'],
            'category_slug' => ['sometimes', 'nullable', 'string'],
            'brand_slug' => ['sometimes', 'nullable', 'string'],
            'vibe_slug' => ['sometimes', 'nullable', 'string'],
            'min_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'max_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'sort_by' => ['sometimes', 'string', 'in:latest,price_asc,price_desc,popular'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ];
    }
}