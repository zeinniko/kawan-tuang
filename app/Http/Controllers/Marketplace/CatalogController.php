<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data semua toko terlebih dahulu
        $storesResponse = InternalApiService::get('stores');
        $stores = $storesResponse['data'] ?? $storesResponse ?? [];

        // 2. OTOMATIS REDIRECT jika 'store_id' tidak ada di URL
        if (!$request->has('store_id') && !empty($stores)) {
            $determinedStoreId = null;

            // Prioritas A: Active Store dari User Login
            if (Auth::check() && Auth::user()->active_store_id) {
                $determinedStoreId = Auth::user()->active_store_id;
            }

            // Prioritas B: Active Store dari Cookie Guest
            if (!$determinedStoreId && $request->cookie('active_store_id')) {
                $determinedStoreId = $request->cookie('active_store_id');
            }

            // Prioritas C: Toko Terdekat via API (User Login)
            if (!$determinedStoreId && Auth::check()) {
                try {
                    $nearestResponse = InternalApiService::get('stores/nearest');
                    $nearestStores = $nearestResponse['data'] ?? [];
                    if (!empty($nearestStores[0]['id'])) {
                        $determinedStoreId = $nearestStores[0]['id'];
                    }
                } catch (\Throwable $e) {
                    Log::error('[CATALOG] Gagal mengambil toko terdekat: ' . $e->getMessage());
                }
            }

            // Prioritas D: Fallback ke Toko Pertama
            if (!$determinedStoreId && !empty($stores[0]['id'])) {
                $determinedStoreId = $stores[0]['id'];
            }

            // Redirect otomatis agar URL berubah menjadi /catalog?store_id=xxx
            if ($determinedStoreId) {
                $queryParams = array_merge($request->query(), ['store_id' => $determinedStoreId]);
                return redirect()->route('catalog.index', $queryParams);
            }
        }

        // 3. Susun Filter Query
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
            'store_id'  => $request->query('store_id'),
            'page'      => $request->query('page', 1),
        ];

        // 4. Cari objek toko yang sedang terpilih
        $selectedStoreId = $filters['store_id'];
        $selectedStore = collect($stores)->firstWhere('id', (string) $selectedStoreId);

        // 5. Ambil daftar produk ter-filter
        $productsResponse = InternalApiService::get('products', array_filter($filters, fn($val) => !is_null($val) && $val !== ''));

        // 6. Ambil master data pendukung
        $categoriesResponse = InternalApiService::get('categories');
        $brandsResponse     = InternalApiService::get('brands');

        return view('marketplace.catalog', [
            'products'      => $productsResponse['data'] ?? [],
            'pagination'    => $productsResponse['meta'] ?? $productsResponse['links'] ?? [],
            'categories'    => $categoriesResponse['data'] ?? $categoriesResponse ?? [],
            'brands'        => $brandsResponse['data'] ?? $brandsResponse ?? [],
            'stores'        => $stores,
            'selectedStore' => $selectedStore,
            'filters'       => $filters,
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