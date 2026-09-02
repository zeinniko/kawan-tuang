<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\WelcomeRegisterMail; // <-- TAMBAHAN: Import Mailable
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // <-- TAMBAHAN: Import Facade Mail
use Illuminate\Support\Facades\Log;  // <-- TAMBAHAN: Import Facade Log
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

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

        // --- SKEMA PENGIRIMAN EMAIL SELAMAT DATANG ---
        try {
            Mail::to($user->email)->send(new WelcomeRegisterMail($user));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email welcome registrasi: ' . $e->getMessage());
        }

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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Alamat email ini tidak terdaftar di sistem kami.',
        ]);

        // Menggunakan alias PasswordBroker
        $status = PasswordBroker::sendResetLink($request->only('email'));

        if ($status === PasswordBroker::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan pemulihan sandi telah dikirim ke email Anda!');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('marketplace.auth.reset-password-form', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // 2. Eksekusi update password di database
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Kata sandi berhasil diperbarui! Silakan masuk.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}