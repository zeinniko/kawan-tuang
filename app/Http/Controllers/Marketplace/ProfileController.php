<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

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
     * Update Data Profil & Upload Avatar
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'full_name'    => 'required|string|max:100',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|string|max:20',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Max 2MB
        ]);

        // Upload Avatar Baru
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Simpan avatar baru
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profil Anda berhasil diperbarui.');
    }
}