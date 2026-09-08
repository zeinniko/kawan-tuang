<?php

namespace App\Http\Requests\Api\V1;

use App\Models\CartItem;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Ambil parameter dari route (bisa bernilai string ID atau instance CartItem)
        $cartItem = $this->route('cartItem') ?? $this->route('item');

        // Jika variabel masih berupa string ID, cari model-nya dari database
        if (is_string($cartItem)) {
            $cartItem = CartItem::with('cart')->find($cartItem);
        }

        // Pastikan CartItem ditemukan dan milik user yang sedang login
        return $cartItem instanceof CartItem 
            && $cartItem->cart 
            && $cartItem->cart->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ];
    }
}