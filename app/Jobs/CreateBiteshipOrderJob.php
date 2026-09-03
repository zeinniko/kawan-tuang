<?php

namespace App\Jobs;

use App\Models\Delivery;
use App\Models\Order;
use App\Services\BiteshipService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateBiteshipOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Order $order) {}

    public function handle(BiteshipService $biteshipService): void
    {
        if ($this->order->fulfillment_type !== 'delivery') {
            return;
        }

        // Load relasi wajib
        $this->order->loadMissing(['store', 'items.product', 'user']);

        try {
            $result = $biteshipService->createOrder($this->order);

            $biteshipOrderId = $result['id'] ?? data_get($result, 'order.id');
            $waybillNumber   = $result['courier']['waybill_id'] ?? null;
            $driverName      = $result['courier']['driver_name'] ?? null;
            $driverPhone     = $result['courier']['driver_phone'] ?? null;
            $courierCompany  = $result['courier']['company'] ?? $this->order->courier_company;
            $courierType     = $result['courier']['type'] ?? $this->order->courier_type;
            
            $liveTrackingUrl = $result['courier']['link'] 
                ?? $result['courier']['tracking_url'] 
                ?? $result['link'] 
                ?? $result['courier_link'] 
                ?? null;

            // Update langsung ke Tabel Orders
            $this->order->update([
                'biteship_order_id' => $biteshipOrderId,
                'courier_company'   => $courierCompany,
                'courier_type'      => $courierType,
                'waybill_number'    => $waybillNumber,
                'driver_name'       => $driverName,
                'driver_phone'      => $driverPhone,
                'live_tracking_url' => $liveTrackingUrl,
                'status'            => $result['status'] ?? Order::STATUS_PROCESSING,
            ]);

            Log::info('[BITESHIP JOB SUCCESS] Order record updated:', [
                'order_number'      => $this->order->order_number,
                'biteship_order_id' => $biteshipOrderId,
            ]);

        } catch (Exception $e) {
            Log::error('CreateBiteshipOrderJob Error: ' . $e->getMessage(), ['order_id' => $this->order->id]);
            throw $e;
        }
    }
}