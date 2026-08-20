@extends('welcome')

@section('title', 'Alamat Pengiriman - Kawan Tuang')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  
  <!-- Header Bar -->
  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
      <a href="{{ route('profile.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-amber-500 transition-colors shadow-sm">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <div>
        <h1 class="text-xl sm:text-2xl font-serif font-bold text-slate-900 dark:text-white">Alamat Pengiriman</h1>
        <p class="text-xs text-slate-500">Kelola lokasi tujuan pengiriman pesanan Anda</p>
      </div>
    </div>
    
    <a href="{{ route('profile.addresses.create') }}" class="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2.5 rounded-2xl text-xs flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
      <i class="fa-solid fa-plus"></i> <span class="hidden sm:inline">Tambah Alamat Baru</span>
    </a>
  </div>

  <!-- List Address Cards -->
  <div class="space-y-4">
    @forelse($addresses as $address)
      @php
        $name = data_get($address, 'recipient_name') ?? data_get($address, 'receiver_name') ?? '-';
        $phone = data_get($address, 'recipient_phone') ?? data_get($address, 'receiver_phone') ?? '-';
        $isPrimary = data_get($address, 'is_primary', false);
      @endphp
      
      <div onclick="openBottomSheet({{ json_encode($address) }})" 
           class="cursor-pointer bg-white dark:bg-slate-900 border {{ $isPrimary ? 'border-amber-500 shadow-md shadow-amber-500/5' : 'border-slate-200/80 dark:border-slate-800' }} rounded-3xl p-5 transition-all hover:border-amber-500/60 relative group">
        
        <div class="flex items-center justify-between gap-2 mb-2">
          <div class="flex items-center gap-2">
            <span class="px-3 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-[11px] font-bold rounded-full uppercase tracking-wider border border-slate-200/50 dark:border-slate-700/50">
              {{ data_get($address, 'label') }}
            </span>
            @if($isPrimary)
              <span class="px-3 py-0.5 bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30 text-[10px] font-bold rounded-full flex items-center gap-1">
                <i class="fa-solid fa-star text-[9px]"></i> Utama
              </span>
            @endif
          </div>
          
          <i class="fa-solid fa-ellipsis-vertical text-slate-400 group-hover:text-amber-500 transition-colors p-1 text-sm sm:hidden"></i>
        </div>

        <div class="space-y-1">
          <h4 class="font-bold text-slate-900 dark:text-white text-sm">
            {{ $name }} <span class="text-slate-400 font-normal">({{ $phone }})</span>
          </h4>
          <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
            {{ data_get($address, 'full_address') }}
          </p>
        </div>

        @if(data_get($address, 'latitude') && data_get($address, 'longitude'))
          <div class="mt-3 flex items-center gap-1.5 text-[11px] font-medium text-emerald-600 dark:text-emerald-400">
            <i class="fa-solid fa-location-dot"></i> Pinpoint Lokasi Sudah Diatur
          </div>
        @endif

        <!-- Desktop Action Hints -->
        <div class="hidden sm:flex items-center justify-end gap-3 mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 text-xs">
          @if(!$isPrimary)
            <form action="{{ route('profile.addresses.set-primary', data_get($address, 'id')) }}" method="POST" onclick="event.stopPropagation();">
              @csrf
              @method('PATCH')
              <button type="submit" class="font-bold text-amber-600 dark:text-amber-400 hover:underline">
                Jadikan Utama
              </button>
            </form>
          @endif
          <a href="{{ route('profile.addresses.edit', data_get($address, 'id')) }}" onclick="event.stopPropagation();" class="text-slate-500 hover:text-amber-500 font-semibold">
            Edit Alamat
          </a>
        </div>

      </div>
    @empty
      <div class="text-center py-12 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6">
        <div class="w-14 h-14 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3 text-2xl">
          <i class="fa-solid fa-map-location-dot"></i>
        </div>
        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Belum Ada Alamat Tersimpan</h3>
        <p class="text-xs text-slate-500 mt-1 mb-5">Tambahkan alamat pengiriman untuk mempermudah checkout minuman favoritmu.</p>
        <a href="{{ route('profile.addresses.create') }}" class="bg-amber-500 text-slate-950 font-bold px-5 py-2.5 rounded-2xl text-xs inline-flex items-center gap-2 shadow-lg shadow-amber-500/20">
          <i class="fa-solid fa-plus"></i> Tambah Alamat Sekarang
        </a>
      </div>
    @endforelse
  </div>

</div>

<!-- BOTTOM SHEET ACTION MODAL -->
<div id="bottom-sheet-backdrop" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0" onclick="closeBottomSheet()">
  <div id="bottom-sheet-content" class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white dark:bg-slate-900 rounded-t-3xl p-6 pb-28 sm:pb-6 max-h-[85vh] overflow-y-auto border-t border-slate-200 dark:border-slate-800 shadow-2xl transform translate-y-full transition-transform duration-300" onclick="event.stopPropagation();">
    
    <div class="w-12 h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full mx-auto mb-4 shrink-0"></div>

    <div class="mb-4">
      <h3 id="sheet-title" class="font-bold text-slate-900 dark:text-white text-base">Opsi Alamat</h3>
      <p id="sheet-subtitle" class="text-xs text-slate-500 truncate mt-0.5"></p>
    </div>

    <div class="space-y-2">
      <div id="sheet-primary-badge" class="hidden w-full p-3.5 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 font-bold text-xs flex items-center gap-3 border border-amber-500/20">
        <i class="fa-solid fa-circle-check text-base"></i> Alamat Utama Saat Ini
      </div>

      <form id="sheet-primary-form" method="POST" action="" class="w-full">
        @csrf
        @method('PATCH')
        <button type="submit" id="sheet-primary-btn" class="w-full text-left p-3.5 rounded-2xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 font-bold text-xs flex items-center gap-3 transition-colors">
          <i class="fa-solid fa-star text-base"></i> Jadikan Alamat Utama
        </button>
      </form>

      <a id="sheet-edit-btn" href="#" class="block w-full text-left p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs flex items-center gap-3 transition-colors">
        <i class="fa-solid fa-pen-to-square text-base text-slate-400"></i> Edit Alamat
      </a>

      <form id="sheet-delete-form" method="POST" action="" onsubmit="return confirm('Yakin ingin menghapus alamat ini?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full text-left p-3.5 rounded-2xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 font-bold text-xs flex items-center gap-3 transition-colors">
          <i class="fa-solid fa-trash-can text-base"></i> Hapus Alamat
        </button>
      </form>
    </div>

  </div>
</div>

<!-- MODAL POPUP NOTIFIKASI (SUCCESS / ERROR) -->
@if(session('success') || session('error'))
<div id="feedback-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
  <div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 text-center shadow-2xl animate-in fade-in zoom-in duration-200">
    @if(session('success'))
      <div class="w-12 h-12 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto mb-3 text-2xl">
        <i class="fa-solid fa-circle-check"></i>
      </div>
      <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Berhasil!</h3>
      <p class="text-xs text-slate-500 mb-5">{{ session('success') }}</p>
    @else
      <div class="w-12 h-12 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-3 text-2xl">
        <i class="fa-solid fa-circle-xmark"></i>
      </div>
      <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Gagal!</h3>
      <p class="text-xs text-slate-500 mb-5">{{ session('error') }}</p>
    @endif

    <button type="button" onclick="document.getElementById('feedback-modal').remove()" class="w-full bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white font-bold py-3 rounded-2xl text-xs transition-colors">
      Tutup
    </button>
  </div>
</div>
@endif

@push('scripts')
<script>
  function openBottomSheet(address) {
    const name = address.recipient_name || address.receiver_name || '-';
    
    document.getElementById('sheet-title').innerText = (address.label || 'Alamat') + ' (' + name + ')';
    document.getElementById('sheet-subtitle').innerText = address.full_address || '';

    document.getElementById('sheet-edit-btn').href = `/profile/addresses/${address.id}/edit`;

    const primaryForm = document.getElementById('sheet-primary-form');
    const primaryBadge = document.getElementById('sheet-primary-badge');

    if (address.is_primary) {
      primaryForm.classList.add('hidden');
      primaryBadge.classList.remove('hidden');
    } else {
      primaryForm.classList.remove('hidden');
      primaryBadge.classList.add('hidden');
      primaryForm.action = `/profile/addresses/${address.id}/set-primary`;
    }

    document.getElementById('sheet-delete-form').action = `/profile/addresses/${address.id}`;

    const backdrop = document.getElementById('bottom-sheet-backdrop');
    const content = document.getElementById('bottom-sheet-content');
    
    backdrop.classList.remove('hidden');
    setTimeout(() => {
      backdrop.classList.remove('opacity-0');
      content.classList.remove('translate-y-full');
    }, 10);
  }

  function closeBottomSheet() {
    const backdrop = document.getElementById('bottom-sheet-backdrop');
    const content = document.getElementById('bottom-sheet-content');

    content.classList.add('translate-y-full');
    backdrop.classList.add('opacity-0');

    setTimeout(() => {
      backdrop.classList.add('hidden');
    }, 300);
  }
</script>
@endpush
@endsection