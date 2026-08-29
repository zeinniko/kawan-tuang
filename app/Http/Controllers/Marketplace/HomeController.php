<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;

class HomeController extends Controller
{
    public function index()
    {
        $response = InternalApiService::get('home');

        // Respon dari API V1/HomeController
        $data = $response['data'] ?? $response ?? [];

        return view('marketplace.index', [
            'vouchers'   => $data['vouchers'] ?? [],
            'categories' => $data['categories'] ?? [],
            'vibes'      => $data['vibes'] ?? [],
            'products'   => $data['featured_products'] ?? [],
            'brands'     => $data['brands'] ?? [],
            'stores'     => $data['stores'] ?? [],
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