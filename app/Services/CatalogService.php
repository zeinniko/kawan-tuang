<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Models\Vibe;
use App\Models\Voucher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CatalogService
{
    /**
     * Mengambil data halaman beranda (Home)
     * 
     * @param User|null $user
     * @return array
     */
    public function getHomeData(?User $user = null): array
    {
        $addresses = collect();
        $activeOrders = collect();

        // Jika user telah terautentikasi
        if ($user) {
            $addresses = $user->addresses()
                ->orderByDesc('is_primary')
                ->latest()
                ->get();

            $activeOrders = $user->orders()
                ->whereNotIn('status', [
                    Order::STATUS_COMPLETED,
                    Order::STATUS_CANCELLED,
                ])
                ->with(['store', 'items.product', 'delivery', 'payment'])
                ->latest()
                ->get();
        }

        return [
            'vouchers' => Voucher::where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->latest()
                ->take(5)
                ->get(),
            'categories'        => Category::withCount('products')->get(),
            'vibes'             => Vibe::all(),
            'featured_products' => Product::where('is_active', true)
                ->with(['category', 'brand', 'primaryImage', 'images', 'storeStocks'])
                ->latest()
                ->take(8)
                ->get(),
            'brands'            => Brand::all(),
            'stores'            => Store::all(),
            'addresses'         => $addresses,
            'active_orders'     => $activeOrders,
        ];
    }

    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        // PENTING: Tambahkan 'storeStocks' di relasi eager loading
        $query = Product::where('is_active', true)
            ->with([
                'category',
                'brand',
                'vibes',
                'primaryImage',
                'images',
                'storeStocks',
            ]);

        // Search Filter
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        // Category Filter
        $categorySlug = $filters['category'] ?? $filters['category_slug'] ?? null;
        if (!empty($categorySlug)) {
            $query->whereHas('category', fn($q) => $q->where('slug', $categorySlug));
        }

        // Brand Filter
        $brandSlug = $filters['brand'] ?? $filters['brand_slug'] ?? null;
        if (!empty($brandSlug)) {
            $query->whereHas('brand', fn($q) => $q->where('slug', $brandSlug));
        }

        // Vibe Filter
        $vibeSlug = $filters['vibe'] ?? $filters['vibe_slug'] ?? null;
        if (!empty($vibeSlug)) {
            $query->whereHas('vibes', fn($q) => $q->where('slug', $vibeSlug));
        }

        if (!empty($filters['store_id'])) {
            $query->whereHas('storeStocks', function ($q) use ($filters) {
                $q->where('store_id', $filters['store_id'])
                    ->where('stock', '>', 0);
            });
        }

        // Price Filter
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Alcohol Percentage (ABV) Filter
        if (isset($filters['min_abv']) && $filters['min_abv'] !== '') {
            $query->where('abv', '>=', (float)$filters['min_abv']);
        }
        if (isset($filters['max_abv']) && $filters['max_abv'] !== '') {
            $query->where('abv', '<=', (float)$filters['max_abv']);
        }

        // Sorting
        match ($filters['sort_by'] ?? 'latest') {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest(),
        };

        return $query->paginate($filters['per_page'] ?? 12);
    }
}
