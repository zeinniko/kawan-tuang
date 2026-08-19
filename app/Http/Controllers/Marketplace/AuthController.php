<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // --- LOGIN ---
    public function showLogin()
    {
        return view('marketplace.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cek apakah input berupa email atau nomor HP
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';
        
        $credentials = [
            $fieldType => $request->login_id,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk!');
        }

        return back()->withErrors([
            'login_id' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login_id');
    }

    // --- REGISTER ---
    public function showRegister()
    {
        return view('marketplace.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'phone_number'    => 'required|string|max:20|unique:users',
            'birth_date'      => 'required|date|before:-21 years', // Validasi minimal 21 tahun
            'password'        => ['required', Password::min(8)],
            'is_age_verified' => 'accepted',
        ], [
            'birth_date.before' => 'Anda harus berusia minimal 21 tahun untuk mendaftar.',
            'is_age_verified.accepted' => 'Anda harus menyetujui syarat & ketentuan.',
        ]);

        $user = User::create([
            'full_name'       => $request->full_name,
            'email'           => $request->email,
            'phone_number'    => $request->phone_number,
            'birth_date'      => $request->birth_date,
            'password'        => Hash::make($request->password),
            'is_age_verified' => true,
            'role'            => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Pendaftaran berhasil!');
    }

    // --- LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // --- FORGOT PASSWORD (Tampilan Dasar) ---
    public function showForgotPassword()
    {
        return view('marketplace.auth.forgot-password');
    }
}