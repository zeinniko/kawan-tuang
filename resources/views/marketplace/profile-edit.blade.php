@extends('welcome')

@section('title', 'Edit Profil - Kawan Tuang')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

  <!-- Header Bar -->
  <div class="flex items-center gap-3">
    <a href="{{ route('profile.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-amber-500 transition-colors shadow-sm">
      <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
      <h1 class="text-xl sm:text-2xl font-serif font-bold text-slate-900 dark:text-white">Edit Data Profil</h1>
      <p class="text-xs text-slate-500">Perbarui informasi diri dan foto profil Anda</p>
    </div>
  </div>

  <!-- Session Errors -->
  @if($errors->any())
    <div class="p-4 bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 rounded-2xl text-xs font-semibold border border-rose-200 dark:border-rose-500/20">
      <ul class="list-disc pl-5 space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Form Update Profile -->
  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-6">
    @csrf
    @method('PUT')

    <!-- Avatar Upload Section -->
    <div class="flex flex-col items-center sm:flex-row sm:items-center gap-5 pb-6 border-b border-slate-100 dark:border-slate-800">
      <div class="relative group">
        <div class="w-24 h-24 rounded-3xl overflow-hidden border-2 border-amber-500/30 bg-amber-500/10 flex items-center justify-center shadow-lg shadow-amber-500/10">
          @if($user->avatar)
            <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->full_name }}" class="w-full h-full object-cover">
          @else
            <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=f59e0b&color=fff&bold=true" alt="{{ $user->full_name }}" class="w-full h-full object-cover">
          @endif
        </div>
        
        <label for="avatar-input" class="absolute -bottom-2 -right-2 w-9 h-9 rounded-full bg-amber-500 hover:bg-amber-400 text-slate-950 flex items-center justify-center cursor-pointer shadow-md transition-transform group-hover:scale-110">
          <i class="fa-solid fa-camera text-sm"></i>
          <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden" onchange="previewImage(event)">
        </label>
      </div>

      <div class="text-center sm:text-left space-y-1">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Foto Profil</h3>
        <p class="text-xs text-slate-500">Format: JPG, PNG, atau WEBP. Maksimal 2MB.</p>
        <button type="button" onclick="document.getElementById('avatar-input').click()" class="inline-block mt-1 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
          Pilih Foto Baru
        </button>
      </div>
    </div>

    <!-- Field Inputs -->
    <div class="space-y-4">
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
        <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm rounded-2xl py-3.5 px-4 outline-none border border-slate-200 dark:border-slate-700 focus:border-amber-500 transition-all text-slate-900 dark:text-white">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm rounded-2xl py-3.5 px-4 outline-none border border-slate-200 dark:border-slate-700 focus:border-amber-500 transition-all text-slate-900 dark:text-white">
      </div>

      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. Handphone / WhatsApp</label>
        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm rounded-2xl py-3.5 px-4 outline-none border border-slate-200 dark:border-slate-700 focus:border-amber-500 transition-all text-slate-900 dark:text-white">
      </div>
    </div>

    <!-- Submit Button -->
    <div class="pt-2">
      <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20">
        Simpan Perubahan
      </button>
    </div>

  </form>

</div>

@push('scripts')
<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
      const output = document.getElementById('avatar-preview');
      output.src = reader.result;
    }
    if (event.target.files[0]) {
      reader.readAsDataURL(event.target.files[0]);
    }
  }
</script>
@endpush
@endsection