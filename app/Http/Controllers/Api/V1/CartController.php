<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddToCartRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Http\Resources\V1\CartResource;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getOrCreateCart($request->user());

        return response()->json([
            'data' => new CartResource($cart),
        ]);
    }

    public function store(AddToCartRequest $request): JsonResponse
    {
        $cart = $this->cartService->addItem(
            $request->user(),
            $request->product_id,
            (int) $request->quantity
        );

        return response()->json([
            'message' => 'Produk berhasil ditambahkan ke keranjang.',
            'data' => new CartResource($cart),
        ], 201);
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): JsonResponse
    {
        $cart = $this->cartService->updateItemQuantity($cartItem, (int) $request->quantity);

        return response()->json([
            'message' => 'Jumlah item berhasil diperbarui.',
            'data' => new CartResource($cart),
        ]);
    }

    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $cart = $this->cartService->removeItem($cartItem);

        return response()->json([
            'message' => 'Item berhasil dihapus dari keranjang.',
            'data' => new CartResource($cart),
        ]);
    }

    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clearCart($request->user());

        return response()->json([
            'message' => 'Keranjang belanja berhasil dikosongkan.',
        ]);
    }

    public function toggleSelect(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate(['is_selected' => 'required|boolean']);

        if ($cartItem->cart->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $cart = $this->cartService->toggleItemSelect($cartItem, (bool) $request->is_selected);

        return response()->json([
            'message' => 'Status item berhasil diperbarui.',
            'data' => new CartResource($cart),
        ]);
    }

    public function toggleSelectAll(Request $request): JsonResponse
    {
        $request->validate(['is_selected' => 'required|boolean']);

        $cart = $this->cartService->toggleAllSelect($request->user(), (bool) $request->is_selected);

        return response()->json([
            'message' => 'Status semua item berhasil diperbarui.',
            'data' => new CartResource($cart),
        ]);
    }
}
