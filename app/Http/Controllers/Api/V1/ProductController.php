<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProductFilterRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(protected CatalogService $catalogService) {}

    public function index(ProductFilterRequest $request): JsonResponse
    {
        $products = $this->catalogService->getFilteredProducts($request->validated());

        return ProductResource::collection($products)->response();
    }

    public function show(Product $product): JsonResponse
    {
        if (! $product->is_active) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        return response()->json([
            'data' => new ProductResource($product->load(['category', 'brand', 'vibes'])),
        ]);
    }
}