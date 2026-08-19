<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\Vibe;
use App\Models\Voucher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CatalogService
{
    public function getHomeData(): array
    {
        return [
            'vouchers' => Voucher::where('valid_until', '>=', now())
                ->latest()
                ->take(5)
                ->get(),
            'categories' => Category::withCount('products')->get(),
            'vibes' => Vibe::all(),
            'featured_products' => Product::where('is_active', true)
                ->with(['category', 'brand', 'primaryImage', 'images'])
                ->latest()
                ->take(8)
                ->get(),
            'brands' => Brand::all(),
            'stores' => Store::all(),
        ];
    }

    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'brand', 'vibes', 'primaryImage', 'images']);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_slug'])) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $filters['category_slug']));
        }

        if (!empty($filters['brand_slug'])) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $filters['brand_slug']));
        }

        if (!empty($filters['vibe_slug'])) {
            $query->whereHas('vibes', fn ($q) => $q->where('slug', $filters['vibe_slug']));
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        match ($filters['sort_by'] ?? 'latest') {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        return $query->paginate($filters['per_page'] ?? 12);
    }
}