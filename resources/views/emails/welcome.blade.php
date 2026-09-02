<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang di {{ config('app.name') }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f172a; color: #f8fafc; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #1e293b; border-radius: 16px; overflow: hidden; border: 1px solid #334155; }
        .header { background-color: #f59e0b; padding: 24px; text-align: center; }
        .header h1 { color: #0f172a; margin: 0; font-size: 24px; font-weight: 800; }
        .content { padding: 32px 24px; font-size: 14px; line-height: 1.6; color: #cbd5e1; }
        .content h2 { color: #ffffff; font-size: 18px; margin-top: 0; }
        .btn { display: inline-block; background-color: #f59e0b; color: #0f172a; font-weight: bold; text-decoration: none; padding: 12px 24px; border-radius: 12px; margin-top: 20px; }
        .footer { background-color: #0f172a; padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #334155; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="content">
            <h2>Halo, {{ $user->full_name }}! 👋</h2>
            <p>Terima kasih telah mendaftar di <strong>{{ config('app.name') }}</strong>. Akun Anda telah berhasil dibuat.</p>
            <p>Nikmati kemudahan menjelajahi katalog produk 100% original kami dan temukan berbagai penawaran menarik khusus untuk Anda.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('catalog.index') }}" class="btn">Mulai Jelajahi Katalog</a>
            </div>
            
            <p style="margin-top: 30px; font-size: 12px; color: #94a3b8;">
                *Pastikan Anda telah melakukan verifikasi usia 21+ untuk dapat menikmati fitur lengkap dan mengumpulkan Poin Tipsy.
            </p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>