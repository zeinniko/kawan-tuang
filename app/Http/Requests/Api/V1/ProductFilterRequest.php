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
            'search'        => ['nullable', 'string', 'max:255'],
            'category'      => ['nullable', 'string'],
            'category_slug' => ['nullable', 'string'],
            'brand'         => ['nullable', 'string'],
            'brand_slug'    => ['nullable', 'string'],
            'vibe'          => ['nullable', 'string'],
            'vibe_slug'     => ['nullable', 'string'],
            'min_price'     => ['nullable', 'numeric', 'min:0'],
            'max_price'     => ['nullable', 'numeric', 'min:0'],
            'min_abv'       => ['nullable', 'numeric', 'min:0'],
            'max_abv'       => ['nullable', 'numeric', 'min:0'],
            'sort_by'       => ['nullable', 'string', 'in:latest,price_asc,price_desc,popular'],
            'page'          => ['nullable', 'integer', 'min:1'],
            'per_page'      => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}