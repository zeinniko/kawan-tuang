<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Order;
use App\Services\MidtransPaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(protected MidtransPaymentService $paymentService) {}

    public function handleMidtrans(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('[WEBHOOK MIDTRANS] Payload Received:', [
            'ip'             => $request->ip(),
            'user_agent'     => $request->userAgent(),
            'order_id'       => $payload['order_id'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null,
            'status'         => $payload['transaction_status'] ?? null,
            'gross_amount'   => $payload['gross_amount'] ?? null,
            'payment_type'   => $payload['payment_type'] ?? null,
            'payload'        => $payload,
        ]);

        try {
            $this->paymentService->handleNotification($payload);

            Log::info('[WEBHOOK MIDTRANS] Processed Successfully for Order ID:', [
                'order_id' => $payload['order_id'] ?? null
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Notifikasi pembayaran berhasil diproses.',
            ]);
        } catch (Exception $e) {
            Log::error('[WEBHOOK MIDTRANS] Processing Failed:', [
                'order_id'      => $payload['order_id'] ?? null,
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function handleBiteship(Request $request): JsonResponse
    {
        $payload         = $request->all();
        $biteshipOrderId = $request->input('order_id');
        $merchantOrderId = $request->input('merchant_order_id');
        $statusRaw       = strtolower($request->input('status', ''));
        $event           = strtolower($request->input('event', ''));

        Log::info('[WEBHOOK BITESHIP] Payload Received:', [
            'ip'                => $request->ip(),
            'event'             => $event,
            'status'            => $statusRaw,
            'biteship_order_id' => $biteshipOrderId,
            'merchant_order_id' => $merchantOrderId,
            'payload'           => $payload,
        ]);

        // 1. Cari Order berdasarkan ID Biteship atau Nomor Pesanan
        $order = Order::where('biteship_order_id', $biteshipOrderId)
            ->orWhere('order_number', $merchantOrderId)
            ->first();

        if (!$order) {
            Log::warning('[WEBHOOK BITESHIP] Order Not Found in Database:', [
                'biteship_order_id' => $biteshipOrderId,
                'merchant_order_id' => $merchantOrderId,
            ]);

            return response()->json(['message' => 'Order tidak ditemukan'], 404);
        }

        // 2. Ekstrak Detail Kurir
        $waybillNumber   = $request->input('courier_waybill_id') ?? $request->input('courier.waybill_id');
        $driverName      = $request->input('courier_driver_name') ?? $request->input('courier.driver_name');
        $driverPhone     = $request->input('courier_driver_phone') ?? $request->input('courier.driver_phone');
        $liveTrackingUrl = $request->input('courier_link') ?? $request->input('courier_tracking_url') ?? $request->input('courier.tracking_url');
        $courierCompany  = $request->input('courier_company') ?? $request->input('courier.company');
        $courierType     = $request->input('courier_type') ?? $request->input('courier.type');

        // 3. Susun data update untuk tabel orders
        $orderUpdates = [];
        if (empty($order->biteship_order_id) && !empty($biteshipOrderId)) {
            $orderUpdates['biteship_order_id'] = $biteshipOrderId;
        }
        if ($waybillNumber)   $orderUpdates['waybill_number']    = $waybillNumber;
        if ($driverName)      $orderUpdates['driver_name']       = $driverName;
        if ($driverPhone)     $orderUpdates['driver_phone']      = $driverPhone;
        if ($liveTrackingUrl) $orderUpdates['live_tracking_url'] = $liveTrackingUrl;
        if ($courierCompany)  $orderUpdates['courier_company']   = strtolower($courierCompany);
        if ($courierType)     $orderUpdates['courier_type']      = strtolower($courierType);

        // 4. Normalisasi Status
        $statusKey = !empty($statusRaw) ? $statusRaw : $event;
        $statusKey = str_replace('order.', '', $statusKey); // contoh: 'picking_up', 'delivered'

        // Mapping Status Internal untuk Order (ENUM / General Status)
        switch ($statusKey) {
            case 'allocated':
            case 'courier_not_found':
                $orderUpdates['status'] = Order::STATUS_PROCESSING;
                break;

            case 'picking_up':
            case 'picked':
            case 'in_transit':
            case 'dropping_off':
            case 'delivered': // Sampai di konsumen, tetap 'delivering' sampai konfirmasi/cron 24 jam
                $orderUpdates['status'] = Order::STATUS_DELIVERING;
                break;

            case 'cancelled':
            case 'rejected':
                $orderUpdates['status']        = Order::STATUS_CANCELLED;
                $orderUpdates['cancel_reason'] = $request->input('cancellation_reason')
                    ?? $request->input('note')
                    ?? 'Pengiriman dibatalkan oleh pihak kurir/Biteship';
                break;
        }

        // Eksekusi Update Tabel Orders
        $oldOrderStatus = $order->status;
        if (!empty($orderUpdates)) {
            $order->update($orderUpdates);
        }

        // 5. Simpan / Update Detail Pengiriman Real-Time ke Tabel Deliveries (Model Delivery)
        $deliveryData = array_filter([
            'courier_provider'  => strtolower($courierCompany ?? $order->courier_company ?? 'gojek'),
            'service_type'      => strtolower($courierType ?? $order->courier_type ?? 'instant'),
            'waybill_number'    => $waybillNumber ?? $order->waybill_number,
            'driver_name'       => $driverName ?? $order->driver_name,
            'driver_phone'      => $driverPhone ?? $order->driver_phone,
            'live_tracking_url' => $liveTrackingUrl ?? $order->live_tracking_url,
            'status'            => $statusKey, // Status spesifik dari Biteship (allocated, picking_up, delivered, dll)
        ], fn ($val) => !is_null($val));

        Delivery::updateOrCreate(
            ['order_id' => $order->id],
            $deliveryData
        );

        Log::info('[WEBHOOK BITESHIP] Order & Delivery updated successfully:', [
            'order_number'    => $order->order_number,
            'old_order_status'=> $oldOrderStatus,
            'new_order_status'=> $order->status,
            'delivery_status' => $statusKey,
            'tracking_url'    => $liveTrackingUrl ?? $order->live_tracking_url,
        ]);

        return response()->json(['message' => 'Webhook Biteship berhasil diproses']);
    }
}
