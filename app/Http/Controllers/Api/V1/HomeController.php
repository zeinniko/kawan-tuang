<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BrandResource;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\VibeResource;
use App\Http\Resources\V1\VoucherResource;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __construct(protected CatalogService $catalogService) {}

    public function index(): JsonResponse
    {
        $data = $this->catalogService->getHomeData();

        return response()->json([
            'vouchers'          => VoucherResource::collection($data['vouchers']),
            'categories'        => CategoryResource::collection($data['categories']),
            'vibes'             => VibeResource::collection($data['vibes']),
            'featured_products' => ProductResource::collection($data['featured_products']),
            'brands'            => BrandResource::collection($data['brands']),
            'stores'            => $data['stores'],
        ]);
    }
}