<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kawan Tuang - Premium Beverage Store')</title>

    <!-- Google Fonts: Plus Jakarta Sans (UI) & Playfair Display (Heading) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    animation: {
                        'float-slow': 'float 6s ease-in-out infinite',
                        'float-fast': 'float 4s ease-in-out infinite',
                        'marquee-left': 'marquee-left 40s linear infinite',
                        'marquee-right': 'marquee-right 40s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-15px)'
                            },
                        },
                        'marquee-left': {
                            '0%': {
                                transform: 'translateX(0)'
                            },
                            '100%': {
                                transform: 'translateX(-50%)'
                            },
                        },
                        'marquee-right': {
                            '0%': {
                                transform: 'translateX(-50%)'
                            },
                            '100%': {
                                transform: 'translateX(0)'
                            },
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-[#F8F9FA] text-slate-800 dark:bg-[#0B0F19] dark:text-slate-200 font-sans transition-colors duration-500 selection:bg-amber-500 selection:text-white overflow-x-hidden pb-24 md:pb-0">

    <!-- 21+ Verification Banner -->
    <div class="bg-amber-100 text-amber-900 dark:bg-amber-500/10 dark:text-amber-400 px-4 py-2 text-[11px] sm:text-xs text-center font-medium tracking-wide">
        <i class="fa-solid fa-triangle-exclamation me-1"></i> Halo Sobat KT! Pastikan usia kamu sudah 21+ ya sebelum menjelajah. Nikmati minuman secara bertanggung jawab.
    </div>

    <!-- HEADER NAVBAR -->
    <header class="sticky top-0 z-40 bg-white/70 dark:bg-[#0B0F19]/70 backdrop-blur-xl border-b border-slate-200/50 dark:border-slate-800/50 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">

            <!-- Brand Logo KT -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif font-bold text-slate-950 text-xl shadow-lg shadow-amber-500/30 group-hover:rotate-12 transition-transform duration-300">
                    KT
                </div>
                <span class="text-2xl font-serif font-bold tracking-tight text-slate-900 dark:text-white">
                    Kawan<span class="text-amber-500 dark:text-amber-400 italic">Tuang</span>
                </span>
            </a>

            <!-- Search Bar -->
            <form action="{{ route('catalog.index') }}" method="GET" class="hidden md:flex flex-1 max-w-md relative group">
                <input type="text" name="search" placeholder="Mau cari teman minum apa malam ini? (Contoh: Whiskey, Wine)"
                    class="w-full bg-slate-100/50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-full py-2.5 pl-12 pr-4 outline-none border border-transparent focus:border-amber-500/50 focus:bg-white dark:focus:bg-slate-900 transition-all duration-300">
                <button type="submit" class="absolute left-4 top-3 text-slate-400 group-focus-within:text-amber-500 transition-colors">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>

            <!-- Actions -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Theme Toggle -->
                <button id="theme-toggle" class="p-2.5 rounded-full text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-slate-800 transition-all" title="Ubah Tema">
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-amber-400 text-lg hidden"></i>
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-lg"></i>
                </button>

                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="relative p-2.5 text-slate-400 hover:text-amber-500 transition-colors" title="Keranjang">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                    <span class="absolute top-1 right-0 bg-amber-500 text-slate-950 font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center shadow-sm shadow-amber-500/50">
                        {{ session('cart_count', 0) }}
                    </span>
                </a>

                <!-- Auth Button (Login / Profile) -->
                @auth
                <a href="{{ route('profile.index') }}" class="hidden md:flex items-center gap-2 bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 font-semibold px-5 py-2.5 rounded-full text-sm hover:bg-amber-500 hover:text-slate-950 transition-all">
                    <i class="fa-regular fa-user"></i> {{ Auth::user()->full_name }}
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden md:flex items-center gap-2 bg-slate-900 hover:bg-amber-500 dark:bg-white dark:hover:bg-amber-400 text-white hover:text-slate-900 dark:text-slate-900 font-semibold px-6 py-2.5 rounded-full text-sm transition-all duration-300 shadow-md">
                    Masuk
                </a>
                @endauth
            </div>

        </div>
    </header>

    <!-- MAIN CONTENT INJECTION -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white dark:bg-[#060910] border-t border-slate-200/50 dark:border-slate-800/50 mt-4 pt-8 md:pt-16 pb-6 md:pb-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 md:gap-12 mb-6 md:mb-12">

                <!-- Brand Info -->
                <div class="sm:col-span-2 md:col-span-1 border-b sm:border-0 border-slate-100 dark:border-slate-800/60 pb-5 sm:pb-0">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-3 md:mb-6">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-amber-600 to-amber-300 flex items-center justify-center font-serif font-bold text-slate-950 text-sm shadow-md shadow-amber-500/20">
                            KT
                        </div>
                        <span class="text-xl font-serif font-bold tracking-tight text-slate-900 dark:text-white">Kawan<span class="text-amber-500 italic">Tuang</span></span>
                    </a>
                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        Penyedia minuman premium, fine wine, dan craft beer terpercaya. Selalu siap nemenin setiap momen berharga kamu.
                    </p>
                </div>

                <!-- Wrapper Grid 2 Kolom khusus Mobile -->
                <div class="grid grid-cols-2 sm:contents gap-4">
                    <!-- Katalog Produk -->
                    <div>
                        <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white mb-2 md:mb-5 uppercase tracking-wider">Katalog</h4>
                        <ul class="space-y-2 md:space-y-3 text-xs md:text-sm text-slate-500 dark:text-slate-400">
                            <li><a href="{{ route('catalog.index') }}" class="hover:text-amber-500 transition-colors">Whiskey & Malt</a></li>
                            <li><a href="{{ route('catalog.index') }}" class="hover:text-amber-500 transition-colors">Wine & Champagne</a></li>
                            <li><a href="{{ route('catalog.index') }}" class="hover:text-amber-500 transition-colors">Promo Spesial</a></li>
                        </ul>
                    </div>

                    <!-- Bantuan -->
                    <div>
                        <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white mb-2 md:mb-5 uppercase tracking-wider">Bantuan</h4>
                        <ul class="space-y-2 md:space-y-3 text-xs md:text-sm text-slate-500 dark:text-slate-400">
                            <li><a href="#" class="hover:text-amber-500 transition-colors">Cara Pesan</a></li>
                            <li><a href="#" class="hover:text-amber-500 transition-colors">Pengiriman</a></li>
                            <li><a href="#" class="hover:text-amber-500 transition-colors">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Pembayaran Aman -->
                <div class="border-t sm:border-0 border-slate-100 dark:border-slate-800/60 pt-4 sm:pt-0">
                    <h4 class="text-xs md:text-sm font-bold text-slate-900 dark:text-white mb-2 md:mb-4 uppercase tracking-wider">Pembayaran Aman</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 leading-relaxed">
                        Transaksi terenkripsi aman didukung penuh dengan Payment Gateway.
                    </p>

                    <!-- Group Badges Metode Pembayaran -->
                    <div class="flex flex-wrap gap-1.5">
                        <!-- Credit / Debit Cards -->
                        <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded px-2 py-1 text-[10px] font-bold text-indigo-600 dark:text-indigo-400" title="Visa, MasterCard, JCB, Amex">
                            VISA / MC
                        </span>

                        <!-- QRIS & E-Wallets -->
                        <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded px-2 py-1 text-[10px] font-bold text-red-600 dark:text-red-400" title="QRIS, GoPay, ShopeePay, Dana, OVO">
                            QRIS & E-Wallet
                        </span>

                        <!-- Virtual Accounts / Bank Transfer -->
                        <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded px-2 py-1 text-[10px] font-bold text-blue-600 dark:text-blue-400" title="BCA, Mandiri, BNI, BRI, Permata, ATM Bersama">
                            Virtual Account
                        </span>

                        <!-- Convenience Store -->
                        <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded px-2 py-1 text-[10px] font-bold text-amber-600 dark:text-amber-400" title="Indomaret, Alfamart">
                            Gerai Retail
                        </span>

                        <!-- PayLater / Cardless Credit -->
                        <span class="bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded px-2 py-1 text-[10px] font-bold text-emerald-600 dark:text-emerald-400" title="Akulaku, Kredivo">
                            PayLater
                        </span>
                    </div>
                </div>

            </div>

            <!-- Copyright Bottom -->
            <div class="border-t border-slate-200/50 dark:border-slate-800/50 pt-4 md:pt-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-[11px] md:text-xs font-medium text-slate-500 dark:text-slate-400 text-center sm:text-left">
                <p>&copy; 2026 Kawan Tuang (KT). All rights reserved.</p>
                <p class="flex items-center gap-1.5">Drink Responsibly <i class="fa-solid fa-wine-glass text-amber-500"></i></p>
            </div>

        </div>
    </footer>

    <!-- MOBILE BOTTOM NAVBAR -->
    <nav class="fixed bottom-0 left-0 right-0 z-50 bg-white/90 dark:bg-[#0B0F19]/90 backdrop-blur-xl border-t border-slate-100 dark:border-slate-800/80 md:hidden transition-colors">
        <div class="flex items-center justify-around px-2 py-2.5">

            <!-- Beranda -->
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('home') ? 'text-amber-500 dark:text-amber-400 font-bold' : 'text-slate-400 hover:text-amber-500' }}">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px] font-medium">Beranda</span>
            </a>

            <!-- Katalog -->
            <a href="{{ route('catalog.index') }}" class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('catalog.*') ? 'text-amber-500 dark:text-amber-400 font-bold' : 'text-slate-400 hover:text-amber-500' }}">
                <i class="fa-solid fa-wine-glass-empty text-lg"></i>
                <span class="text-[10px] font-medium">Katalog</span>
            </a>

            <!-- Pesanan -->
            <a href="{{ route('orders.index') }}" class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('orders.*') ? 'text-amber-500 dark:text-amber-400 font-bold' : 'text-slate-400 hover:text-amber-500' }}">
                <i class="fa-solid fa-receipt text-lg"></i>
                <span class="text-[10px] font-medium">Pesanan</span>
            </a>

            <!-- Akun / Profil -->
            <a href="{{ route('profile.index') }}" class="flex flex-col items-center gap-1 transition-colors {{ request()->routeIs('profile.*') || request()->routeIs('login') || request()->routeIs('register') ? 'text-amber-500 dark:text-amber-400 font-bold' : 'text-slate-400 hover:text-amber-500' }}">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[10px] font-medium">Akun</span>
            </a>

        </div>
    </nav>
    <!-- FLOATING WHATSAPP CUSTOMER SERVICE (HIDDEN ON MOBILE CART / FLEX ON DESKTOP) -->
    <a href="https://wa.me/6289681676100?text=Halo%20Kawan%20Tuang,%20saya%20ingin%20tanya%20produk%20dan%20layanan"
        target="_blank"
        rel="noopener noreferrer"
        class="{{ request()->routeIs('cart.*') ? 'hidden md:flex' : 'flex' }} fixed bottom-20 md:bottom-8 right-4 sm:right-6 z-30 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white items-center justify-center shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:scale-110 group"
        title="Hubungi CS Kawan Tuang">

        <!-- Efek Ring Pulsing Gelombang di Belakang Tombol -->
        <span class="absolute -inset-1 rounded-full bg-emerald-500/40 animate-ping pointer-events-none"></span>

        <!-- Icon WhatsApp -->
        <i class="fa-brands fa-whatsapp text-2xl sm:text-3xl relative z-10"></i>
    </a>

    <!-- JAVASCRIPT GLOBAL LOGIC -->
    <script>
        // Theme Toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            lightIcon.classList.remove('hidden');
            darkIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            lightIcon.classList.toggle('hidden');
            darkIcon.classList.toggle('hidden');
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>