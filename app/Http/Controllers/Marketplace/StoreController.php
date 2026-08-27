<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Store;
use App\Services\InternalApiService;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::where('is_active', true)->get();

        $brands = Brand::withCount('products')
            ->orderBy('name', 'asc')
            ->get();

        $countries = Brand::whereNotNull('country_origin')
            ->where('country_origin', '!=', '')
            ->distinct()
            ->pluck('country_origin');

        return view('marketplace.our-store', compact('stores', 'brands', 'countries'));
    }
}