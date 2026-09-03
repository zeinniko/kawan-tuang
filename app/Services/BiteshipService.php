<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\UserAddress;
use App\Models\StoreStock;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BiteshipService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = (string) config('services.biteship.api_key', '');
        $this->baseUrl = (string) config('services.biteship.base_url', 'https://api.biteship.com/v1');
    }

    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    /**
     * Cek Tarif / Rates API
     */
    public function calculateRates(Store $store, UserAddress $address, array $itemsInput): array
    {
        $userId = auth()->id();

        // 1. Validasi Stok Produk di Cabang Toko Dulu (Mencegah Call API Sia-sia)
        $biteshipItems = [];
        $itemFingerprints = [];

        foreach ($itemsInput as $item) {
            $product = Product::findOrFail($item['product_id']);

            $storeStock = StoreStock::where('store_id', $store->id)
                ->where('product_id', $product->id)
                ->first();

            if (!$storeStock || $storeStock->stock < $item['quantity']) {
                $available = $storeStock ? $storeStock->stock : 0;
                throw new Exception("Produk '{$product->name}' tidak memenuhi kuantitas di outlet {$store->name} (Sisa stok: {$available}).");
            }

            $weight = $product->volume_ml ? ($product->volume_ml + 250) : 1000;

            $biteshipItems[] = [
                'name'     => $product->name,
                'sku'      => $product->sku,
                'value'    => (int) $product->price,
                'quantity' => (int) $item['quantity'],
                'weight'   => (int) $weight,
            ];

            $itemFingerprints[] = "{$product->id}:{$item['quantity']}";
        }

        sort($itemFingerprints); // Urutkan agar susunan item konsisten

        // 2. Buat Hash Key Unik dari 3 Variabel Utama + Titik Koordinat + Items
        $cachePayload = [
            'user_id'     => $userId,
            'store_id'    => $store->id,
            'address_id'  => $address->id,
            'origin_lat'  => (string) $store->latitude,
            'origin_lng'  => (string) $store->longitude,
            'dest_lat'    => (string) $address->latitude,
            'dest_lng'    => (string) $address->longitude,
            'items'       => $itemFingerprints,
        ];

        $cacheKey = hash('sha256', json_encode($cachePayload));

        // 3. CEK DATABASE CACHE (Query Builder)
        $cachedRecord = DB::table('shipping_rate_caches')
            ->where('cache_key', $cacheKey)
            ->first();

        // JIKA DITEMUKAN: Langsung kembalikan data dari DB (0 Rupiah ke Biteship)
        if ($cachedRecord) {
            Log::info("[BITESHIP CACHE HIT] Menggunakan data ongkir tersimpan dari Database.", ['key' => $cacheKey]);
            return json_decode($cachedRecord->rates_data, true);
        }

        // 4. JIKA TIDAK DITEMUKAN: Panggil Biteship API
        Log::info("[BITESHIP API CALL] Memanggil API Rates Biteship.", ['key' => $cacheKey]);

        $payload = [
            'origin_latitude'       => (float) $store->latitude,
            'origin_longitude'      => (float) $store->longitude,
            'destination_latitude'  => (float) $address->latitude,
            'destination_longitude' => (float) $address->longitude,
            'couriers'              => 'gojek,grab,jne,sicepat,jnt,paxel',
            'items'                 => $biteshipItems,
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post("{$this->baseUrl}/rates/couriers", $payload);

        if ($response->failed()) {
            throw new Exception('Gagal menghitung tarif pengiriman: ' . $response->json('message', $response->body()));
        }

        $pricing = $response->json('pricing', []);

        $mappedRates = collect($pricing)->map(fn ($rate) => [
            'courier_name'           => $rate['courier_name'],
            'courier_code'           => $rate['courier_code'],
            'courier_service_code'   => $rate['courier_service_code'],
            'service_name'           => $rate['courier_service_name'],
            'description'            => $rate['description'] ?? null,
            'price'                  => (float) $rate['price'],
            'estimated_days'         => $rate['shipment_duration_range'] ?? null,
            'shipment_duration_unit' => $rate['shipment_duration_unit'] ?? 'days',
        ])->toArray();

        // 5. SIMPAN HASIL BITESIP PERMANEN KE DATABASE CACHE
        if (!empty($mappedRates) && $userId) {
            DB::table('shipping_rate_caches')->updateOrInsert(
                ['cache_key' => $cacheKey],
                [
                    'user_id'         => $userId,
                    'store_id'        => $store->id,
                    'user_address_id' => $address->id,
                    'origin_lat'      => $store->latitude,
                    'origin_lng'      => $store->longitude,
                    'dest_lat'        => $address->latitude,
                    'dest_lng'        => $address->longitude,
                    'rates_data'      => json_encode($mappedRates),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]
            );
        }

        return $mappedRates;
    }

    /**
     * Create Order & Request Pickup di Biteship
     */
    public function createOrder(Order $order): array
    {
        $store = $order->store;
        $snapshot = $order->address_snapshot;

        $items = [];
        foreach ($order->items as $item) {
            $product = $item->product;
            $weight = ($product && $product->volume_ml) ? ($product->volume_ml + 250) : 1000;

            $items[] = [
                'name'     => $item->product_name_snapshot,
                'value'    => (int) $item->unit_price,
                'quantity' => (int) $item->quantity,
                'weight'   => (int) $weight,
            ];
        }

        $payload = [
            'merchant_order_id'         => $order->order_number,
            'shipper_contact_name'      => $store->name,
            'shipper_contact_phone'     => $store->phone_number,
            'origin_address'            => $store->address,
            'origin_coordinate'         => [
                'latitude'  => (float) $store->latitude,
                'longitude' => (float) $store->longitude,
            ],
            'destination_contact_name'  => $snapshot['recipient_name'] ?? $order->user->full_name,
            'destination_contact_phone' => $snapshot['recipient_phone'] ?? $order->user->phone_number,
            'destination_address'       => $snapshot['full_address'],
            'destination_coordinate'    => [
                'latitude'  => (float) ($snapshot['latitude'] ?? 0),
                'longitude' => (float) ($snapshot['longitude'] ?? 0),
            ],
            'courier_company'           => $order->courier_company,
            'courier_type'              => $order->courier_type,
            'delivery_type'             => 'now',
            'items'                     => $items,
        ];
        Log::info($payload);

        $response = $this->client()->post('/orders', $payload);

        if ($response->failed()) {
            Log::error('Biteship Create Order Failed:', ['order' => $order->order_number, 'response' => $response->body()]);
            throw new Exception('Gagal membuat pesanan pengiriman Biteship: ' . $response->json('message', $response->body()));
        }

        return $response->json();
    }

    /**
     * Tracking API
     */
    public function trackOrder(Order $order): array
    {
        $target = $order->biteship_order_id ?? $order->waybill_number;

        if (!$target) {
            throw new Exception('Pesanan belum memiliki nomor resi atau ID pengiriman Biteship.');
        }

        $response = $this->client()->get("/trackings/{$target}");

        if ($response->failed()) {
            throw new Exception('Gagal mengambil informasi pelacakan: ' . $response->json('message', $response->body()));
        }

        return $response->json();
    }
}