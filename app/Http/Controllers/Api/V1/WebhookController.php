<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
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
        $event = $request->input('event');
        $biteshipOrderId = $request->input('order_id');
        $merchantOrderId = $request->input('merchant_order_id');
        $payload = $request->all();

        Log::info('[WEBHOOK BITESHIP] Payload Received:', [
            'ip'                => $request->ip(),
            'event'             => $event,
            'biteship_order_id' => $biteshipOrderId,
            'merchant_order_id' => $merchantOrderId,
            'payload'           => $payload,
        ]);

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

        $courierData = $request->input('courier', []);
        $updateData = [];

        if (!empty($courierData['waybill_id'])) {
            $updateData['waybill_number'] = $courierData['waybill_id'];
        }
        if (!empty($courierData['driver_name'])) {
            $updateData['driver_name'] = $courierData['driver_name'];
        }
        if (!empty($courierData['driver_phone'])) {
            $updateData['driver_phone'] = $courierData['driver_phone'];
        }
        if (!empty($courierData['tracking_url'])) {
            $updateData['live_tracking_url'] = $courierData['tracking_url'];
        }

        // Penanganan perubahan status berdasarkan Event Biteship
        switch ($event) {
            case 'order.courier.allocated':
                $updateData['status'] = Order::STATUS_PROCESSING;
                break;
            case 'order.picking_up':
            case 'order.dropping_off':
                $updateData['status'] = Order::STATUS_DELIVERING;
                break;
            case 'order.delivered':
                $updateData['status'] = Order::STATUS_COMPLETED;
                break;
            case 'order.cancelled':
            case 'order.rejected':
                $updateData['status'] = Order::STATUS_CANCELLED;
                $updateData['cancel_reason'] = $request->input('cancellation_reason') 
                    ?? 'Pengiriman dibatalkan oleh pihak kurir/Biteship';
                break;
            default:
                Log::info('[WEBHOOK BITESHIP] Unhandled or Informational Event:', ['event' => $event]);
                break;
        }

        if (!empty($updateData)) {
            $oldStatus = $order->status;
            $order->update($updateData);

            Log::info('[WEBHOOK BITESHIP] Order Updated Successfully:', [
                'order_number' => $order->order_number,
                'old_status'   => $oldStatus,
                'new_status'   => $order->status,
                'updated_fields' => array_keys($updateData),
            ]);
        } else {
            Log::info('[WEBHOOK BITESHIP] Payload received but no attributes updated.', [
                'order_number' => $order->order_number
            ]);
        }

        return response()->json(['message' => 'Webhook Biteship berhasil diproses']);
    }
}