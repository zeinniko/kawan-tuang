@extends('welcome')

@section('title', 'Buat Kata Sandi Baru - Tipsy More')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 text-center">Buat Kata Sandi Baru</h1>
        
        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Input Kata Sandi Baru -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required class="w-full bg-slate-50 dark:bg-slate-800 text-sm p-3 pr-10 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 outline-none focus:border-amber-500 transition-all">
                    <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-1')" class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <i id="eye-icon-1" class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Input Konfirmasi Kata Sandi Baru -->
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full bg-slate-50 dark:bg-slate-800 text-sm p-3 pr-10 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-100 outline-none focus:border-amber-500 transition-all">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-2')" class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                        <i id="eye-icon-2" class="fa-regular fa-eye text-sm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 mt-2">
                Simpan Kata Sandi Baru
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endpush