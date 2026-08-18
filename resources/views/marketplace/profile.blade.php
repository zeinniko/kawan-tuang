<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya & Verifikasi Usia - Kawan Tuang</title>

    <!-- Tailwind CSS & Config for Dark Mode -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body
    class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 font-sans pb-24 md:pb-12 transition-colors duration-300">

    <!-- 21+ AGE VERIFICATION MODAL / POP-UP (OVERLAY) -->
    <div id="age-verification-modal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md">
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl text-center space-y-5 animate-in fade-in zoom-in duration-300">

            <!-- Icon & Badge -->
            <div
                class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-500 flex items-center justify-center mx-auto text-3xl shadow-lg shadow-amber-500/10">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>

            <!-- Title & Description -->
            <div class="space-y-2">
                <span
                    class="px-3 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold text-[10px] rounded-full uppercase tracking-wider">Peringatan
                    Kepatuhan Hukum</span>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white">Apakah Anda Berusia 21+?</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                    Sesuai dengan peraturan perundang-undangan di Indonesia, penjualan minuman beralkohol hanya
                    diperuntukkan bagi konsumen yang telah berusia 21 tahun ke atas dan tidak sedang hamil.
                </p>
            </div>

            <!-- Age Input / Check Option -->
            <div
                class="p-3 bg-slate-100 dark:bg-slate-950 rounded-2xl border border-slate-200 dark:border-slate-800 text-left text-xs space-y-2">
                <label class="flex items-start gap-2.5 cursor-pointer">
                    <input type="checkbox" id="age-confirm-checkbox"
                        class="mt-0.5 rounded text-amber-500 focus:ring-amber-500">
                    <span class="text-slate-600 dark:text-slate-400 text-[11px]">
                        Saya mengonfirmasi bahwa saya lahir sebelum tanggal hari ini di tahun 2005 dan menyetujui Syarat
                        & Ketentuan.
                    </span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-2">
                <button id="btn-confirm-age" onclick="closeAgeModal()"
                    class="w-full bg-amber-500 hover:bg-amber-600 dark:bg-amber-400 dark:hover:bg-amber-500 text-slate-950 font-extrabold py-3.5 rounded-xl text-sm transition-all shadow-lg shadow-amber-500/20">
                    Ya, Saya Berusia 21+
                </button>
                <button onclick="rejectAgeModal()"
                    class="w-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold py-3 rounded-xl text-xs transition-colors">
                    Saya Belum Berusia 21 Tahun
                </button>
            </div>

        </div>
    </div>

    <!-- 21+ Age Banner Disclaimer -->
    <div
        class="bg-amber-100 border-b border-amber-200 text-amber-900 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-400 px-4 py-1.5 text-xs text-center font-medium">
        <i class="fa-solid fa-triangle-exclamation me-1"></i> Khusus Usia 21+. Nikmati Minuman Anda Secara Bertanggung
        Jawab.
    </div>

    <!-- MAIN NAVBAR -->
    <header
        class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">

            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div
                    class="w-9 h-9 rounded-xl bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center font-bold text-slate-950 text-lg shadow-lg shadow-amber-500/20">
                    TT
                </div>
                <span class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white">Kawan<span
                        class="text-amber-500 dark:text-amber-400">Tuang</span></span>
            </a>

            <!-- Actions -->
            <div class="flex items-center gap-3">
                <!-- Re-trigger Age Modal Test Button -->
                <button onclick="openAgeModal()"
                    class="text-xs text-slate-500 dark:text-slate-400 hover:text-amber-500 underline hidden sm:inline me-2">
                    Cek Verifikasi 21+
                </button>

                <!-- DARK / LIGHT MODE TOGGLE -->
                <button id="theme-toggle"
                    class="p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                    title="Ubah Mode">
                    <i id="theme-toggle-light-icon" class="fa-solid fa-sun text-amber-400 text-lg hidden"></i>
                    <i id="theme-toggle-dark-icon" class="fa-solid fa-moon text-slate-600 text-lg"></i>
                </button>

                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}"
                    class="relative p-2 text-slate-600 dark:text-slate-300 hover:text-amber-500 dark:hover:text-amber-400 transition-colors">
                    <i class="fa-solid fa-bag-shopping text-xl"></i>
                    <span
                        class="absolute top-1 right-1 bg-amber-500 text-slate-950 font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center">3</span>
                </a>
            </div>

        </div>
    </header>

    <!-- MAIN CONTAINER -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- PROFILE HEADER CARD -->
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6 transition-colors">

            <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                <!-- Avatar with Badge -->
                <div class="relative">
                    <div
                        class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-amber-500 to-amber-300 flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-amber-500/20">
                        AW
                    </div>
                    <span
                        class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs shadow"
                        title="Akun Terverifikasi 21+">
                        <i class="fa-solid fa-shield-check"></i>
                    </span>
                </div>

                <!-- User Info -->
                <div class="space-y-1">
                    <div class="flex items-center gap-2 justify-center sm:justify-start">
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-white">Alex Wijaya</h1>
                        <span
                            class="px-2.5 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 text-[10px] font-bold rounded-full uppercase">Gold
                            Member</span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">alex.wijaya@example.com • +62 812-3456-7890
                    </p>

                    <!-- Verification Status Tag -->
                    <div
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg text-xs font-semibold mt-1">
                        <i class="fa-solid fa-circle-check"></i> Verifikasi KTP 21+ Disetujui
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div
                class="flex items-center gap-4 border-t sm:border-t-0 sm:border-l border-slate-200 dark:border-slate-800 pt-4 sm:pt-0 sm:pl-6 w-full sm:w-auto justify-around">
                <div class="text-center">
                    <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">12</span>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Pesanan</span>
                </div>
                <div class="text-center">
                    <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">4</span>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Favorit</span>
                </div>
                <div class="text-center">
                    <span class="text-lg font-black text-amber-600 dark:text-amber-400 block">1.250</span>
                    <span class="text-[10px] text-slate-400 uppercase font-semibold">Poin TT</span>
                </div>
            </div>

        </div>

        <!-- AGE & ID VERIFICATION BANNER -->
        <div
            class="bg-gradient-to-r from-amber-500/10 via-amber-500/5 to-transparent border border-amber-500/20 rounded-2xl p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-lg flex-shrink-0">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white">Status Identitas Hukum (KTP 21+)</h3>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Terverifikasi pada 12 Jan 2026. Bebas
                        belanja produk alcohol tanpa hambatan.</p>
                </div>
            </div>
            <button
                class="px-3 py-1.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 hover:border-amber-400 transition-colors flex-shrink-0">
                Lihat Detail
            </button>
        </div>

        <!-- MENU LIST SECTION -->
        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm divide-y divide-slate-100 dark:divide-slate-800 transition-colors">

            <!-- Option 1: Riwayat Transaksi -->
            <a href="{{ route('orders.index') }}"
                class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-receipt text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Riwayat Transaksi & Tracking</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Cek status pesanan aktif dan histori
                            pembelian</p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
            </a>

            <!-- Option 2: Produk Favorit / Wishlist -->
            <a href="#"
                class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-heart text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Koleksi Favorit (Wishlist)</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Daftar beer, wine, & whiskey yang disimpan
                        </p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
            </a>

            <!-- Option 3: Daftar Alamat Pengiriman -->
            <a href="#"
                class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-location-dot text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Alamat Pengiriman (2 Alamat)</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Atur lokasi tujuan pengiriman Gojek/Grab
                        </p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
            </a>

            <!-- Option 4: Metode Pembayaran Tersimpan -->
            <a href="#"
                class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-wallet text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Metode Pembayaran</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">QRIS, E-Wallet, & Kartu Kredit terhubung
                        </p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
            </a>

            <!-- Option 5: Pengaturan Keamanan & Privasi -->
            <a href="#"
                class="flex items-center justify-between p-4 sm:p-5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3.5">
                    <div
                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-amber-500 flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-base"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Keamanan & Kata Sandi</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Ubah kata sandi dan autentikasi 2 langkah
                        </p>
                    </div>
                </div>
                <i class="fa-solid fa-chevron-right text-xs text-slate-400"></i>
            </a>

        </div>

        <!-- LOGOUT BUTTON -->
        <button
            class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/20 font-bold py-3.5 rounded-2xl text-xs transition-colors flex items-center justify-center gap-2">
            <i class="fa-solid fa-right-from-bracket"></i> Keluar dari Akun
        </button>

    </main>

    <!-- MOBILE BOTTOM NAVBAR -->
    <nav
        class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-slate-200 dark:border-slate-800 md:hidden px-4 py-2 transition-colors">
        <div class="grid grid-cols-4 gap-1 max-w-md mx-auto text-center">
            <!-- Beranda -->
            <a href="{{ route('home') }}" 
                class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-amber-500">
                <i class="fa-solid fa-house text-lg"></i>
                <span class="text-[10px] font-medium">Beranda</span>
            </a>
            <!-- Katalog -->
            <a href="{{ route('catalog.index') }}"
                class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-amber-500">
                <i class="fa-solid fa-compass text-lg"></i>
                <span class="text-[10px] font-medium">Katalog</span>
            </a>
            <!-- Pesanan -->
            <a href="{{ route('orders.index') }}"
                class="flex flex-col items-center gap-1 text-slate-500 dark:text-slate-400 hover:text-amber-500">
                <i class="fa-solid fa-receipt text-lg"></i>
                <span class="text-[10px] font-medium">Pesanan</span>
            </a>
            <!-- Akun -->
            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center gap-1 text-amber-600 dark:text-slate-400">
                <i class="fa-regular fa-user text-lg"></i>
                <span class="text-[10px] font-medium">Akun</span>
            </a>
        </div>
    </nav>

    <!-- JAVASCRIPT: THEME TOGGLE & AGE VERIFICATION MODAL -->
    <script>
        // 1. Dark/Light Mode Switcher
        const themeToggleBtn = document.getElementById('theme-toggle');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            lightIcon.classList.remove('hidden');
            darkIcon.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
        }

        themeToggleBtn.addEventListener('click', function () {
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

        // 2. Age Verification Modal Logic
        const ageModal = document.getElementById('age-verification-modal');

        function openAgeModal() {
            ageModal.classList.remove('hidden');
        }

        function closeAgeModal() {
            const checkbox = document.getElementById('age-confirm-checkbox');
            if (!checkbox.checked) {
                alert('Silakan centang persetujuan batas usia terlebih dahulu.');
                return;
            }
            ageModal.classList.add('hidden');
            localStorage.setItem('age-verified', 'true');
        }

        function rejectAgeModal() {
            alert('Maaf, Anda harus berusia 21 tahun ke atas untuk mengakses layanan ini.');
            window.location.href = 'https://www.google.com';
        }

        // Auto-check modal state on load
        window.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('age-verified') === 'true') {
                ageModal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>