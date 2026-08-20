<?php
namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;

class StoreController extends Controller
{
    /**
     * Mengambil daftar toko aktif via Internal API
     */
    public function index()
    {
        $response = InternalApiService::get('stores');
        
        return response()->json($response);
    }
}