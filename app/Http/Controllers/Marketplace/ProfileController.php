<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Services\InternalApiService;
use App\Services\KycService;
use App\Services\StorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected StorageService $storageService,
        protected KycService $kycService
    ) {}

    /**
     * Halaman Utama Profil & Status KYC
     */
    public function index(Request $request): View
    {
        $user = $request->user()->fresh();

        // Mengambil status KYC via InternalApiService tanpa cURL HTTP external
        $kycResponse = InternalApiService::get('kyc/status');
        $kycData = $kycResponse['data'] ?? null;

        return view('marketplace.profile', compact('user', 'kycData'));
    }

    /**
     * Halaman Form Edit Profil Terpisah
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('marketplace.profile-edit', compact('user'));
    }

    /**
     * Update Data Profil, Upload Avatar (Public S3), & Password
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi data profil dasar
        $rules = [
            'full_name'    => 'required|string|max:100',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        // Validasi kata sandi jika toggle diaktifkan
        if ($request->boolean('change_password')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $validated = $request->validate($rules, [
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak sesuai.',
            'password.min'       => 'Kata sandi minimal harus terdiri dari 8 karakter.',
            'avatar.max'        => 'Ukuran foto avatar maksimal 2MB.',
        ]);

        // Upload Avatar Baru ke Public Storage (S3 / Local)
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                $this->storageService->delete($user->avatar, 'public');
            }

            $validated['avatar'] = $this->storageService->upload(
                $request->file('avatar'),
                'avatars',
                'public'
            );
        }

        // Hash password baru jika diubah
        if ($request->boolean('change_password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        unset($validated['change_password']);

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Submit Dokumen KYC / KTP 21+ (Private S3 Storage)
     */
    public function submitKyc(Request $request): RedirectResponse
    {
        $request->validate([
            'nik'          => 'required|digits:16',
            'ktp_image'    => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'selfie_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'nik.digits'            => 'NIK KTP wajib berjumlah 16 digit angka.',
            'ktp_image.required'    => 'Foto KTP wajib diunggah.',
            'selfie_image.required' => 'Foto selfie memegang KTP wajib diunggah.',
        ]);

        $this->kycService->submitKyc(
            $request->user(),
            $request->nik,
            $request->file('ktp_image'),
            $request->file('selfie_image')
        );

        return back()->with('success', 'Dokumen KTP berhasil dikirim dan sedang dalam proses verifikasi tim admin.');
    }
}