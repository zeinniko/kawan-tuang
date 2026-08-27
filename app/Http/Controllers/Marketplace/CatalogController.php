<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search'    => $request->query('search'),
            'category'  => $request->query('category'),
            'brand'     => $request->query('brand'),
            'vibe'      => $request->query('vibe'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
            'min_abv'   => $request->query('min_abv'),
            'max_abv'   => $request->query('max_abv'),
            'sort_by'   => $request->query('sort_by', 'latest'),
            'page'      => $request->query('page', 1),
        ];

        // 1. Ambil daftar produk dari API internal
        $productsResponse = InternalApiService::get('products', array_filter($filters, fn($val) => !is_null($val) && $val !== ''));

        // 2. Ambil master data pendukung
        $categoriesResponse = InternalApiService::get('categories');
        $brandsResponse     = InternalApiService::get('brands');

        return view('marketplace.catalog', [
            'products'   => $productsResponse['data'] ?? [],
            'pagination' => $productsResponse['meta'] ?? $productsResponse['links'] ?? [],
            'categories' => $categoriesResponse['data'] ?? $categoriesResponse ?? [],
            'brands'     => $brandsResponse['data'] ?? $brandsResponse ?? [],
            'filters'    => $filters,
        ]);
    }

    public function show($slug)
    {
        $response = InternalApiService::get("products/{$slug}");

        if (!isset($response['data'])) {
            abort(404, 'Produk tidak ditemukan.');
        }

        return view('marketplace.product-detail', [
            'product' => $response['data'],
        ]);
    }
}