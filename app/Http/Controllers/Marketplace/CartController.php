<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartResponse    = InternalApiService::get('cart');
        $storesResponse  = InternalApiService::get('stores');
        $addressesResponse = InternalApiService::get('addresses');

        $items     = $cartResponse['data']['items'] ?? $cartResponse['items'] ?? [];
        $stores    = $storesResponse['data'] ?? [];
        $addresses = $addressesResponse['data'] ?? [];

        $totalQty = collect($items)->sum('quantity');
        session(['cart_count' => $totalQty]);

        return view('marketplace.cart.index', [
            'cart'      => $cartResponse['data'] ?? $cartResponse ?? [],
            'stores'    => $stores,
            'addresses' => $addresses,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity'   => 'required|integer|min:1',
        ]);

        $response = InternalApiService::post('cart/items', [
            'product_id' => $request->product_id,
            'quantity'   => (int) $request->quantity,
        ]);

        // Refresh & update session cart_count
        $this->updateCartCountSession();

        if ($request->wantsJson()) {
            return response()->json(array_merge($response, [
                'cart_count' => session('cart_count', 0)
            ]));
        }

        return back()->with('success', $response['message'] ?? 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $response = InternalApiService::put("cart/items/{$cartItemId}", [
            'quantity' => (int) $request->quantity,
        ]);

        $this->updateCartCountSession();

        if ($request->wantsJson()) {
            return response()->json(array_merge($response, [
                'cart_count' => session('cart_count', 0)
            ]));
        }

        return back()->with('success', $response['message'] ?? 'Jumlah item diperbarui.');
    }

    public function destroy(Request $request, $cartItemId)
    {
        $response = InternalApiService::delete("cart/items/{$cartItemId}");

        $this->updateCartCountSession();

        if ($request->wantsJson()) {
            return response()->json(array_merge($response, [
                'cart_count' => session('cart_count', 0)
            ]));
        }

        return back()->with('success', $response['message'] ?? 'Item berhasil dihapus.');
    }

    public function clear(Request $request)
    {
        $response = InternalApiService::delete('cart/clear');

        // Reset cart_count ke 0
        session(['cart_count' => 0]);

        if ($request->wantsJson()) {
            return response()->json($response);
        }

        return back()->with('success', $response['message'] ?? 'Keranjang berhasil dikosongkan.');
    }

    /**
     * Menerapkan kode voucher promo melalui InternalApiService
     */
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Kirim request internal ke API /api/v1/vouchers/apply
        $response = InternalApiService::post('vouchers/apply', [
            'code' => $request->code,
        ]);

        // Tentukan HTTP status code berdasarkan respon dari API
        $isSuccess = isset($response['status'])
            ? $response['status'] === 'success'
            : isset($response['data']);

        $statusCode = $isSuccess ? 200 : 400;

        if (isset($response['errors']) || (isset($response['message']) && !$isSuccess)) {
            $statusCode = 422;
        }

        if ($request->wantsJson()) {
            return response()->json($response, $statusCode);
        }

        if (!$isSuccess) {
            return back()->with('error', $response['message'] ?? 'Kode promo tidak valid.');
        }

        return back()->with('success', $response['message'] ?? 'Voucher berhasil diterapkan.');
    }

    // Helper Private untuk sinkronisasi jumlah item
    private function updateCartCountSession()
    {
        $cartResponse = InternalApiService::get('cart');
        $items = $cartResponse['data']['items'] ?? $cartResponse['items'] ?? [];
        $totalQty = collect($items)->sum('quantity');

        session(['cart_count' => $totalQty]);
    }

    /**
     * Menerapkan perhitungan tarif pengiriman via InternalApiService
     */
    public function checkShippingRates(Request $request)
    {
        $request->validate([
            'store_id'        => 'required|string',
            'user_address_id' => 'required|string',
            'items'           => 'required|array',
            'items.*.product_id' => 'required|string',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $response = InternalApiService::post('shipping/rates', [
            'store_id'        => $request->store_id,
            'user_address_id' => $request->user_address_id,
            'items'           => $request->items,
        ]);

        $isSuccess = isset($response['data']) || (isset($response['message']) && str_contains(strtolower($response['message']), 'berhasil'));
        $statusCode = $isSuccess ? 200 : 400;

        return response()->json($response, $statusCode);
    }

    public function toggleSelect(Request $request, $cartItemId)
    {
        $request->validate([
            'is_selected' => 'required|boolean',
        ]);

        $response = InternalApiService::patch("cart/items/{$cartItemId}/toggle-select", [
            'is_selected' => (bool) $request->is_selected,
        ]);

        if ($request->wantsJson()) {
            return response()->json($response);
        }

        return back();
    }

    public function toggleSelectAll(Request $request)
    {
        $request->validate([
            'is_selected' => 'required|boolean',
        ]);

        $response = InternalApiService::patch("cart/toggle-select-all", [
            'is_selected' => (bool) $request->is_selected,
        ]);

        if ($request->wantsJson()) {
            return response()->json($response);
        }

        return back();
    }

    public function getNearestStore(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'limit'     => 'sometimes|integer|min:1|max:20',
        ]);

        $response = InternalApiService::get('stores/nearest', [
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'limit'     => $request->input('limit', 5),
        ]);

        return response()->json($response);
    }
}
