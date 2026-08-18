<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\BiteshipService;
use App\Services\MidtransPaymentService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function __construct(protected MidtransPaymentService $paymentService) {}

    public function handleMidtrans(Request $request): JsonResponse
    {
        try {
            $this->paymentService->handleNotification($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Notifikasi pembayaran berhasil diproses.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function handleBiteship(Request $request, BiteshipService $biteshipService): JsonResponse
    {
        try {
            $biteshipService->handleWebhook($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Webhook pengiriman Biteship berhasil diproses.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
