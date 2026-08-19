<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\BrandResource;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        $brands = Brand::all();

        return response()->json([
            'data' => BrandResource::collection($brands),
        ]);
    }

    public function show(Brand $brand): JsonResponse
    {
        return response()->json([
            'data' => new BrandResource($brand),
        ]);
    }
}