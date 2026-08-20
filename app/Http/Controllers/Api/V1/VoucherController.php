<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ApplyVoucherRequest;
use App\Http\Resources\V1\VoucherResource;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VoucherController extends Controller
{
    public function __construct(protected VoucherService $voucherService) {}

    public function index(Request $request): JsonResponse
    {
        $vouchers = $this->voucherService->getAvailableVouchers($request->user());

        return response()->json([
            'data' => VoucherResource::collection($vouchers),
        ]);
    }

    public function apply(ApplyVoucherRequest $request): JsonResponse
    {
        try {
            $result = $this->voucherService->applyVoucher(
                $request->user(),
                $request->code
            );

            return response()->json([
                'status'  => 'success',
                'message' => 'Voucher berhasil diterapkan.',
                'data'    => [
                    'voucher'              => new VoucherResource($result['voucher']),
                    'subtotal'             => $result['subtotal'],
                    'discount_amount'      => $result['discount_amount'],
                    'total_after_discount' => $result['total_after_discount'],
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => collect($e->errors())->first()[0] ?? 'Voucher tidak dapat digunakan.',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage() ?: 'Gagal menerapkan voucher.',
            ], 400);
        }
    }
}