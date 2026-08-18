<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProductReviewRequest;
use App\Http\Resources\V1\ProductReviewResource;
use App\Models\Product;
use App\Services\ProductReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function __construct(protected ProductReviewService $reviewService) {}

    public function index(Request $request, Product $product): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 15);
        $reviews = $this->reviewService->getProductReviews($product, $perPage);
        $summary = $this->reviewService->getProductRatingSummary($product);

        return response()->json([
            'summary' => $summary,
            'reviews' => ProductReviewResource::collection($reviews)->response()->getData(true),
        ]);
    }

    public function store(StoreProductReviewRequest $request, Product $product): JsonResponse
    {
        $review = $this->reviewService->createReview(
            $request->user(),
            $product,
            $request->validated()
        );

        return response()->json([
            'message' => 'Ulasan produk berhasil dikirim.',
            'data' => new ProductReviewResource($review->load('user')),
        ], 201);
    }

    public function userReviews(Request $request): JsonResponse
    {
        $reviews = $this->reviewService->getUserReviews($request->user());

        return response()->json([
            'data' => ProductReviewResource::collection($reviews),
        ]);
    }
}