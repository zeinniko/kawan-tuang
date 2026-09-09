<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ChangePasswordRequest;
use App\Http\Requests\Api\V1\UpdateProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Services\AuthService;
use App\Services\StorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected StorageService $storageService
    ) {}

    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($request->user()),
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Jika ada berkas avatar yang diunggah
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama dari storage private jika ada
            if ($user->avatar) {
                $this->storageService->delete($user->avatar, 'private');
            }

            // Unggah avatar baru ke folder 'avatars' dengan visibilitas 'private'
            $validated['avatar'] = $this->storageService->upload(
                file: $request->file('avatar'),
                directory: 'avatars',
                visibility: 'private'
            );
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'data' => new UserResource($user->fresh()),
        ]);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $this->authService->changePassword(
            $request->user(),
            $request->current_password,
            $request->new_password
        );

        return response()->json([
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}