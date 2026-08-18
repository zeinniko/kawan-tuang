<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ApplyVoucherRequest;
use App\Http\Resources\V1\VoucherResource;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $result = $this->voucherService->applyVoucher(
            $request->user(),
            $request->code
        );

        return response()->json([
            'message' => 'Voucher berhasil diterapkan.',
            'data' => [
                'voucher' => new VoucherResource($result['voucher']),
                'subtotal' => $result['subtotal'],
                'discount_amount' => $result['discount_amount'],
                'total_after_discount' => $result['total_after_discount'],
            ],
        ]);
    }
}