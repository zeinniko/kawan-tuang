<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartResponse = InternalApiService::get('cart');

        return view('marketplace.cart', [
            'cart' => $cartResponse['data'] ?? $cartResponse ?? [],
        ]);
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $response = InternalApiService::put("cart/items/{$cartItemId}", [
            'quantity' => (int) $request->quantity,
        ]);

        if ($request->wantsJson()) {
            return response()->json($response);
        }

        return back()->with('success', $response['message'] ?? 'Jumlah item diperbarui.');
    }

    public function destroy(Request $request, $cartItemId)
    {
        $response = InternalApiService::delete("cart/items/{$cartItemId}");

        if ($request->wantsJson()) {
            return response()->json($response);
        }

        return back()->with('success', $response['message'] ?? 'Item berhasil dihapus.');
    }
}