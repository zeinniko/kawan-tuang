<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class ProductReviewService
{
    public function getProductReviews(Product $product, int $perPage = 15): LengthAwarePaginator
    {
        return $product->reviews()
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function getProductRatingSummary(Product $product): array
    {
        $reviews = $product->reviews();

        return [
            'average_rating' => round((float) $reviews->avg('rating'), 1),
            'total_reviews' => (int) $reviews->count(),
            'rating_breakdown' => [
                '5_star' => (int) (clone $reviews)->where('rating', 5)->count(),
                '4_star' => (int) (clone $reviews)->where('rating', 4)->count(),
                '3_star' => (int) (clone $reviews)->where('rating', 3)->count(),
                '2_star' => (int) (clone $reviews)->where('rating', 2)->count(),
                '1_star' => (int) (clone $reviews)->where('rating', 1)->count(),
            ],
        ];
    }

    public function createReview(User $user, Product $product, array $data): ProductReview
    {
        // Validasi: User harus membeli produk ini dalam pesanan yang sudah selesai (completed)
        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
            ->first();

        if (! $order) {
            throw ValidationException::withMessages([
                'order_id' => ['Ulasan hanya dapat diberikan jika Anda telah membeli produk ini dan pesanan telah selesai.'],
            ]);
        }

        // Cek apakah produk dalam order ini sudah pernah diulas sebelumnya
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->where('order_id', $order->id)
            ->first();

        if ($existingReview) {
            throw ValidationException::withMessages([
                'product_id' => ['Anda sudah memberikan ulasan untuk produk ini pada transaksi tersebut.'],
            ]);
        }

        // Upload foto ulasan ke S3 jika ada
        $uploadedPhotoPaths = [];
        if (! empty($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $uploadedPhotoPaths[] = $photo->store("reviews/{$product->id}", 's3');
            }
        }

        return ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'photos' => $uploadedPhotoPaths,
            'is_anonymous' => $data['is_anonymous'] ?? false,
        ]);
    }

    public function getUserReviews(User $user): Collection
    {
        return $user->reviews()->with('product')->latest()->get();
    }
}