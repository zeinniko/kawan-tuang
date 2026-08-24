<?php

namespace App\Jobs;

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

        try {
            $result = $biteshipService->createOrder($this->order);

            $this->order->update([
                'biteship_order_id' => $result['id'] ?? null,
                'waybill_number'    => $result['courier']['waybill_id'] ?? null,
                'driver_name'       => $result['courier']['driver_name'] ?? null,
                'driver_phone'      => $result['courier']['driver_phone'] ?? null,
                'live_tracking_url' => $result['courier']['tracking_url'] ?? null,
                'status'            => Order::STATUS_PROCESSING,
            ]);
        } catch (Exception $e) {
            Log::error('CreateBiteshipOrderJob Error: ' . $e->getMessage(), ['order_id' => $this->order->id]);
        }
    }
}