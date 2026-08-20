@extends('welcome')

@section('title', ($address->is_edit ? 'Edit Alamat' : 'Tambah Alamat') . ' - Kawan Tuang')

@push('styles')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <style>
    #map { height: 280px; width: 100%; border-radius: 1rem; z-index: 1; }
  </style>
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
  
  <!-- Header Bar -->
  <div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
      <a href="{{ route('profile.addresses.index') }}" class="w-10 h-10 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-500 hover:text-amber-500 transition-colors shadow-sm">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <div>
        <h1 class="text-xl sm:text-2xl font-serif font-bold text-slate-900 dark:text-white">
          {{ $address->is_edit ? 'Edit Alamat' : 'Tambah Alamat Baru' }}
        </h1>
        <p class="text-xs text-slate-500">Lengkapi data penerima dan titik lokasi pengiriman</p>
      </div>
    </div>
  </div>

  <!-- Form Section -->
  <form action="{{ $address->is_edit ? route('profile.addresses.update', $address->id) : route('profile.addresses.store') }}" 
        method="POST" class="space-y-5">
    @csrf
    @if($address->is_edit)
      @method('PUT')
    @endif

    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-6 space-y-4 shadow-sm">
      
      <!-- Label Alamat -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Label Alamat</label>
        <input type="text" name="label" value="{{ old('label', $address->label) }}" placeholder="Rumah, Kantor, Kos, dll." required 
               class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('label') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">
        @error('label')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
      </div>

      <!-- Nama & No HP -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Penerima</label>
          <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" placeholder="Budi Santoso" required 
                 class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('recipient_name') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">
          @error('recipient_name')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. HP Penerima</label>
          <input type="tel" name="recipient_phone" value="{{ old('recipient_phone', $address->recipient_phone) }}" placeholder="081234567890" required 
                 class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('recipient_phone') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">
          @error('recipient_phone')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
        </div>
      </div>

      <!-- MAP PICKER PINPOINT -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
            Pinpoint Titik Lokasi <span class="text-rose-500">*</span>
          </label>
          <button type="button" onclick="getCurrentLocation()" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1">
            <i class="fa-solid fa-crosshairs"></i> Gunakan Lokasi Saat Ini
          </button>
        </div>

        <div id="map" class="border border-slate-200 dark:border-slate-700 shadow-inner"></div>

        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $address->latitude ?? '-6.2088') }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $address->longitude ?? '106.8456') }}">
        
        <p class="text-[11px] text-slate-400 mt-1.5 flex items-center gap-1">
          <i class="fa-solid fa-circle-info"></i> Geser penanda peta ke posisi persis lokasi rumah/bangunan Anda.
        </p>
      </div>

      <!-- Alamat Lengkap -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap & Patokan</label>
        <textarea name="full_address" id="full_address" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kecamatan, Patokan Pagar..." required 
                  class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('full_address') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">{{ old('full_address', $address->full_address) }}</textarea>
        @error('full_address')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
      </div>

      <!-- Checkbox Set Primary -->
      <div class="flex items-center gap-2 pt-2">
        <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }} class="rounded border-slate-300 text-amber-500 focus:ring-amber-500">
        <label for="is_primary" class="text-xs text-slate-600 dark:text-slate-400 font-medium">Jadikan sebagai alamat utama</label>
      </div>

    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
      <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 text-center">
        {{ $address->is_edit ? 'Simpan Perubahan' : 'Tambah Alamat' }}
      </button>

      @if($address->is_edit)
        <button type="button" onclick="document.getElementById('delete-address-form').submit();" class="px-5 bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-500/20 font-bold py-3.5 rounded-2xl text-xs transition-all flex items-center gap-2">
          <i class="fa-solid fa-trash-can"></i> Hapus
        </button>
      @endif
    </div>

  </form>

  @if($address->is_edit)
    <form id="delete-address-form" action="{{ route('profile.addresses.destroy', $address->id) }}" method="POST" class="hidden" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?');">
      @csrf
      @method('DELETE')
    </form>
  @endif

</div>

<!-- MODAL POPUP ERROR -->
@if(session('error') || $errors->any())
<div id="error-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
  <div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 text-center shadow-2xl animate-in fade-in zoom-in duration-200">
    <div class="w-12 h-12 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-3 text-2xl">
      <i class="fa-solid fa-circle-xmark"></i>
    </div>
    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Terjadi Kesalahan</h3>
    <p class="text-xs text-slate-500 mb-4">{{ session('error') ?? 'Mohon periksa kembali form isian Anda.' }}</p>

    <button type="button" onclick="document.getElementById('error-modal').remove()" class="w-full bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white font-bold py-3 rounded-2xl text-xs transition-colors">
      Tutup & Perbaiki
    </button>
  </div>
</div>
@endif

@push('scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    let map, marker;

    let initialLat = parseFloat(document.getElementById('latitude').value) || -6.2088;
    let initialLng = parseFloat(document.getElementById('longitude').value) || 106.8456;

    document.addEventListener('DOMContentLoaded', function () {
      map = L.map('map').setView([initialLat, initialLng], 15);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      const customIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
        iconSize: [36, 36],
        iconAnchor: [18, 36],
      });

      marker = L.marker([initialLat, initialLng], {
        draggable: true,
        icon: customIcon
      }).addTo(map);

      marker.on('dragend', function (e) {
        let position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
        reverseGeocode(position.lat, position.lng);
      });

      map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
      });
    });

    function updateCoordinates(lat, lng) {
      document.getElementById('latitude').value = lat.toFixed(8);
      document.getElementById('longitude').value = lng.toFixed(8);
    }

    function reverseGeocode(lat, lng) {
      fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
        .then(response => response.json())
        .then(data => {
          if (data && data.display_name) {
            const currentAddressField = document.getElementById('full_address');
            if (!currentAddressField.value.trim()) {
              currentAddressField.value = data.display_name;
            }
          }
        })
        .catch(err => console.log(err));
    }

    function getCurrentLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          map.setView([lat, lng], 16);
          marker.setLatLng([lat, lng]);

          updateCoordinates(lat, lng);
          reverseGeocode(lat, lng);
        }, function () {
          alert('Gagal mengambil lokasi. Pastikan izin lokasi/GPS browser telah diaktifkan.');
        });
      } else {
        alert('Browser Anda tidak mendukung layanan lokasi GPS.');
      }
    }
  </script>
@endpush
@endsection