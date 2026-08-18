<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VibeResource;
use App\Models\Vibe;
use Illuminate\Http\JsonResponse;

class VibeController extends Controller
{
    public function index(): JsonResponse
    {
        $vibes = Vibe::where('is_active', true)->get();

        return response()->json([
            'data' => VibeResource::collection($vibes),
        ]);
    }

    public function show(Vibe $vibe): JsonResponse
    {
        return response()->json([
            'data' => new VibeResource($vibe),
        ]);
    }
}