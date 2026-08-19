<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Parameter filter & pencarian diteruskan dari URL query string ke API
        $filters = [
            'search'    => $request->query('search'),
            'category'  => $request->query('category'),
            'vibe'      => $request->query('vibe'),
            'brand'     => $request->query('brand'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'sort_by'   => $request->query('sort_by', 'popular'),
            'page'      => $request->query('page', 1),
        ];

        // 1. Ambil daftar produk dari API
        $productsResponse = InternalApiService::get('products', array_filter($filters));
        
        // 2. Ambil master data untuk opsi filter
        $categoriesResponse = InternalApiService::get('categories');
        $vibesResponse      = InternalApiService::get('vibes');

        return view('marketplace.catalog', [
            'products'   => $productsResponse['data'] ?? [],
            'pagination' => $productsResponse['meta'] ?? [],
            'categories' => $categoriesResponse['data'] ?? $categoriesResponse ?? [],
            'vibes'      => $vibesResponse['data'] ?? $vibesResponse ?? [],
            'filters'    => $filters,
        ]);
    }

    public function show($slug)
    {
        // Memanggil API /api/v1/products/{slug}
        $response = InternalApiService::get("products/{$slug}");

        if (!isset($response['data'])) {
            abort(404, 'Produk tidak ditemukan.');
        }

        return view('marketplace.product-detail', [
            'product' => $response['data'],
        ]);
    }
}