<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function index()
    {
        $user = Auth::user();
        return view('marketplace.profile', compact('user'));
    }

    // Menangani update data diri
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name'    => ['required', 'string', 'max:100'],
            'email'        => ['required', 'string', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone_number' => ['required', 'string', 'max:20', 'unique:users,phone_number,' . $user->id],
        ]);

        $user->update([
            'full_name'    => $request->full_name,
            'email'        => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // Menangani perubahan kata sandi
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password'     => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}