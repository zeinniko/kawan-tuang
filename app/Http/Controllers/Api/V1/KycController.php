<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UploadKycRequest;
use App\Http\Resources\V1\KtpVerificationResource;
use App\Services\KycService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function __construct(protected KycService $kycService) {}

    public function upload(UploadKycRequest $request): JsonResponse
    {
        $kyc = $this->kycService->submitKyc(
            $request->user(),
            $request->nik,
            $request->file('ktp_image'),
            $request->file('selfie_image')
        );

        return response()->json([
            'message' => 'Dokumen KYC 21+ berhasil diunggah dan sedang menanti peninjauan admin.',
            'data' => new KtpVerificationResource($kyc),
        ], 201);
    }

    public function status(Request $request): JsonResponse
    {
        $kyc = $request->user()->ktpVerification;

        if (! $kyc) {
            return response()->json([
                'message' => 'Belum ada dokumen KYC yang diunggah.',
                'data' => null,
            ]);
        }

        return response()->json([
            'data' => new KtpVerificationResource($kyc),
        ]);
    }
}