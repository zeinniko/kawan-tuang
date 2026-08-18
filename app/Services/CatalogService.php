<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Vibe;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CatalogService
{
    public function getHomeData(): array
    {
        return [
            'banners' => Banner::where('is_active', true)->orderBy('sort_order')->get(),
            'categories' => Category::where('is_active', true)->withCount('products')->get(),
            'vibes' => Vibe::where('is_active', true)->get(),
            'featured_products' => Product::where('is_active', true)
                ->where('is_featured', true)
                ->with(['category', 'brand'])
                ->latest()
                ->take(8)
                ->get(),
        ];
    }

    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        $query = Product::where('is_active', true)->with(['category', 'brand', 'vibes']);

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

        return $query->paginate($filters['per_page'] ?? 15);
    }
}