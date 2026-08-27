<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Halaman Utama Profil
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        return view('marketplace.profile', compact('user'));
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
     * Update Data Profil & Upload Avatar / Password
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
        ]);

        // Upload Avatar Baru
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
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
}
