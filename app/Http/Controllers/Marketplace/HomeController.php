<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use Illuminate\Http\Request;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil active_store_id dari user login ATAU dari cookie Guest
        $activeStoreId = auth()->check() 
            ? auth()->user()->active_store_id 
            : $request->cookie('active_store_id');

        // 2. Kirim store_id ke Internal API agar produk yang dirender sudah bawa harga toko tersebut
        $response = InternalApiService::get('home', [
            'store_id' => $activeStoreId
        ]);

        $data = $response['data'] ?? $response ?? [];

        return view('marketplace.index', [
            'vouchers'       => $data['vouchers'] ?? [],
            'categories'     => $data['categories'] ?? [],
            'vibes'          => $data['vibes'] ?? [],
            'products'       => $data['featured_products'] ?? [],
            'brands'         => $data['brands'] ?? [],
            'stores'         => $data['stores'] ?? [],
            'activeStoreId'  => $activeStoreId,
        ]);
    }

    public function terms()
    {
        $lastUpdated = '28 Agustus 2026';

        return view('marketplace.pages.term-conditions', compact('lastUpdated'));
    }
    public function privacy()
    {
        $lastUpdated = '28 Agustus 2026';

        return view('marketplace.pages.privacy-policy', compact('lastUpdated'));
    }
    public function pesan()
    {
        return view('marketplace.pages.order-guide');
    }
    public function kirim()
    {
        return view('marketplace.pages.tracking-guide');
    }
}