<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Wajib terautentikasi / terdaftar
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Akses ditolak. Anda harus login terlebih dahulu.'
            ], 401);
        }

        // 2. Wajib sudah terverifikasi email/phone/KYC (opsional sesuai aturan bisnis)
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Silakan verifikasi akun Anda terlebih dahulu untuk menggunakan fitur ini.'
            ], 403);
        }

        return $next($request);
    }
}