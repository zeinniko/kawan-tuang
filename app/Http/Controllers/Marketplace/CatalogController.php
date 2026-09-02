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

        // 1. Ambil data semua toko
        $storesResponse = InternalApiService::get('stores');
        $stores = $storesResponse['data'] ?? $storesResponse ?? [];

        // 2. Tentukan toko terpilih (Hirarki Prioritas)
        $selectedStoreId = $request->query('store_id');
        $selectedStore = null;

        // Priority 1: User memilih toko secara manual via URL / Query String
        if ($selectedStoreId && !empty($stores)) {
            $selectedStore = collect($stores)->firstWhere('id', (int) $selectedStoreId);
        }

        // Priority 2: Jika User sudah LOGIN dan belum pilih toko manual
        if (!$selectedStore && Auth::check()) {
            try {
                // Panggil langsung API stores/nearest (Backend API akan otomatis mendeteksi alamat user login)
                $nearestResponse = InternalApiService::get('stores/nearest');
                $nearestStores = $nearestResponse['data'] ?? [];

                if (!empty($nearestStores)) {
                    $nearestData = $nearestStores[0] ?? null;
                    if ($nearestData && isset($nearestData['id'])) {
                        $selectedStore = collect($stores)->firstWhere('id', (int) $nearestData['id']) ?? $nearestData;
                    }
                }
            } catch (\Throwable $e) {
                Log::error('[CATALOG] Gagal mengambil toko terdekat: ' . $e->getMessage());
            }
        }

        // Priority 3: Fallback default (Guest / Belum ada alamat / API Nearest kosong) -> Toko pertama
        if (!$selectedStore && !empty($stores)) {
            $selectedStore = $stores[0] ?? null;
        }

        // Simpan ID toko terpilih ke filter agar query produk terikat pada toko tersebut
        if ($selectedStore) {
            $filters['store_id'] = data_get($selectedStore, 'id');
        }

        // 3. Ambil daftar produk yang sudah ter-filter
        $productsResponse = InternalApiService::get('products', array_filter($filters, fn($val) => !is_null($val) && $val !== ''));

        // 4. Ambil master data pendukung
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