<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\UserAddress;
use Exception;
use Illuminate\Support\Facades\Http;

class BiteshipService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.biteship.api_key');
        $this->baseUrl = config('services.biteship.base_url', 'https://api.biteship.com/v1');
    }

    public function calculateRates(Store $store, UserAddress $address, array $itemsInput): array
    {
        $biteshipItems = [];

        foreach ($itemsInput as $item) {
            $product = Product::findOrFail($item['product_id']);
            $biteshipItems[] = [
                'name' => $product->name,
                'sku' => $product->sku,
                'value' => (int) $product->price,
                'quantity' => (int) $item['quantity'],
                'weight' => (int) ($product->weight_gram ?? 1000), // Default 1kg/botol jika belum ditentukan
            ];
        }

        $payload = [
            'origin_latitude' => (float) $store->latitude,
            'origin_longitude' => (float) $store->longitude,
            'destination_latitude' => (float) $address->latitude,
            'destination_longitude' => (float) $address->longitude,
            'couriers' => 'gosend,grab,jne,sicepat,jnt',
            'items' => $biteshipItems,
        ];

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/rates/couriers", $payload);

        if ($response->failed()) {
            throw new Exception('Gagal menghitung tarif pengiriman: ' . $response->body());
        }

        $pricing = $response->json('pricing', []);

        return collect($pricing)->map(fn ($rate) => [
            'courier_name' => $rate['courier_name'],
            'courier_code' => $rate['courier_code'],
            'courier_service_code' => $rate['courier_service_code'],
            'service_name' => $rate['courier_service_name'],
            'description' => $rate['description'] ?? null,
            'price' => (float) $rate['price'],
            'estimated_days' => $rate['shipment_duration_range'] ?? null,
            'shipment_duration_type' => $rate['shipment_duration_unit'] ?? null,
        ])->toArray();
    }

    public function trackOrder(Order $order): array
    {
        if (! $order->biteship_order_id && ! $order->waybill_number) {
            throw new Exception('Pesanan belum memiliki nomor resi atau ID pengiriman dari ekspedisi.');
        }

        $target = $order->waybill_number ?? $order->biteship_order_id;

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])->get("{$this->baseUrl}/trackings/{$target}");

        if ($response->failed()) {
            throw new Exception('Gagal mengambil status pelacakan: ' . $response->body());
        }

        return $response->json();
    }

    public function handleWebhook(array $payload): void
    {
        $biteshipOrderId = $payload['order_id'] ?? null;
        $status = $payload['status'] ?? null;
        $waybillNumber = $payload['courier']['waybill_id'] ?? null;

        if (! $biteshipOrderId) {
            return;
        }

        $order = Order::where('biteship_order_id', $biteshipOrderId)
            ->orWhere('order_number', $payload['merchant_order_id'] ?? null)
            ->first();

        if (! $order) {
            return;
        }

        $updateData = [];

        if ($waybillNumber && ! $order->waybill_number) {
            $updateData['waybill_number'] = $waybillNumber;
        }

        match ($status) {
            'allocated', 'picking_up' => $updateData['shipping_status'] = 'allocated',
            'picked_up', 'dropping_off' => $updateData['shipping_status'] = 'shipping',
            'delivered' => [
                $updateData['shipping_status'] = 'delivered',
                $updateData['status'] = 'completed',
            ],
            'cancelled', 'rejected' => [
                $updateData['shipping_status'] = 'failed',
            ],
            default => null,
        };

        if (! empty($updateData)) {
            $order->update($updateData);
        }
    }
}