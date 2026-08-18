<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BannerResource;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\ProductResource;
use App\Http\Resources\V1\VibeResource;
use App\Services\CatalogService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function __construct(protected CatalogService $catalogService) {}

    public function index(): JsonResponse
    {
        $data = $this->catalogService->getHomeData();

        return response()->json([
            'banners' => BannerResource::collection($data['banners']),
            'categories' => CategoryResource::collection($data['categories']),
            'vibes' => VibeResource::collection($data['vibes']),
            'featured_products' => ProductResource::collection($data['featured_products']),
        ]);
    }
}