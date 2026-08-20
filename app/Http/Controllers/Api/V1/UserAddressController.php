<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAddressRequest;
use App\Http\Requests\Api\V1\UpdateAddressRequest;
use App\Http\Resources\V1\UserAddressResource;
use App\Models\UserAddress;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAddressController extends Controller
{
    public function __construct(protected AddressService $addressService) {}

    public function index(Request $request): JsonResponse
    {
        $addresses = $this->addressService->getUserAddresses($request->user());

        return response()->json([
            'data' => UserAddressResource::collection($addresses),
        ]);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $address = $this->addressService->createAddress(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'message' => 'Alamat pengiriman berhasil ditambahkan.',
            'data' => new UserAddressResource($address),
        ], 201);
    }

    public function show(Request $request, $address): JsonResponse
    {
        $addressModel = $address instanceof UserAddress ? $address : UserAddress::find($address);

        if (!$addressModel || $addressModel->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'data' => new UserAddressResource($addressModel),
        ]);
    }

    public function update(UpdateAddressRequest $request, $address): JsonResponse
    {
        $addressModel = $address instanceof UserAddress ? $address : UserAddress::find($address);

        if (!$addressModel || $addressModel->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $updatedAddress = $this->addressService->updateAddress(
            $request->user(),
            $addressModel,
            $request->validated()
        );

        return response()->json([
            'message' => 'Alamat pengiriman berhasil diperbarui.',
            'data' => new UserAddressResource($updatedAddress),
        ]);
    }

    public function destroy(Request $request, $address): JsonResponse
    {
        $addressModel = $address instanceof UserAddress ? $address : UserAddress::find($address);

        if (!$addressModel || $addressModel->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $this->addressService->deleteAddress($addressModel);

        return response()->json([
            'message' => 'Alamat pengiriman berhasil dihapus.',
        ]);
    }

    public function setPrimary(Request $request, $address): JsonResponse
    {
        $addressModel = $address instanceof UserAddress ? $address : UserAddress::find($address);

        if (!$addressModel || $addressModel->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Akses ditolak.'], 403);
        }

        $primaryAddress = $this->addressService->setPrimaryAddress($request->user(), $addressModel);

        return response()->json([
            'message' => 'Alamat utama berhasil diperbarui.',
            'data' => new UserAddressResource($primaryAddress),
        ]);
    }
}