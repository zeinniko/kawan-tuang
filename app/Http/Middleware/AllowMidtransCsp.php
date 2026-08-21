<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowMidtransCsp
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya modifikasi/izinkan skrip dan frame Midtrans tanpa mematikan CSS/Font
        $midtransScript = " 'unsafe-inline' 'unsafe-eval' https://*.midtrans.com https://snap-assets.sandbox.midtrans.com https://api.sandbox.midtrans.com https://pay.google.com https://gwk.gopayapi.com https://www.googletagmanager.com";
        $midtransFrame  = " 'self' https://*.midtrans.com https://app.sandbox.midtrans.com https://app.midtrans.com";

        // Ambil header CSP yang sudah dikirim oleh server jika ada
        $existingCsp = $response->headers->get('Content-Security-Policy');

        if ($existingCsp) {
            // Sisipkan izin Midtrans ke dalam header yang sudah ada
            $updatedCsp = preg_replace('/(script-src[^;]*)/i', '$1' . $midtransScript, $existingCsp);
            $updatedCsp = preg_replace('/(frame-src[^;]*)/i', '$1' . $midtransFrame, $updatedCsp);
            
            // Jika frame-src belum ada di CSP bawaan server, tambahkan
            if (!str_contains($updatedCsp, 'frame-src')) {
                $updatedCsp .= " frame-src" . $midtransFrame . ";";
            }

            $response->headers->set('Content-Security-Policy', $updatedCsp);
        } else {
            // Jika server belum punya CSP sama sekali
            $response->headers->set('Content-Security-Policy', "script-src 'self'" . $midtransScript . "; frame-src" . $midtransFrame . ";");
        }

        return $response;
    }
}