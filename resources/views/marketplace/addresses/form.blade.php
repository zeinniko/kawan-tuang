@extends('welcome')

@section('title', ($address->is_edit ? 'Edit Alamat' : 'Tambah Alamat Baru') . ' - Tipsy More')

@push('styles')
  <!-- Alpine JS CDN -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    /* Styling Dropdown Autocomplete Google Maps agar Match dengan UI Website */
    .pac-container {
      z-index: 9999 !important;
      border-radius: 1rem !important;
      margin-top: 6px !important;
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
      border: 1px solid rgba(226, 232, 240, 0.8) !important;
      font-family: inherit !important;
      padding: 6px 0 !important;
    }
    .pac-item {
      padding: 10px 14px !important;
      cursor: pointer !important;
      border-top: 1px solid #f1f5f9 !important;
      font-size: 13px !important;
    }
    .pac-item:first-child {
      border-top: none !important;
    }
    .pac-item-query {
      font-size: 13px !important;
      font-weight: 600 !important;
      color: #0f172a !important;
    }
    
    /* Custom Backdrop Glassmorphism untuk Semua Modal */
    .custom-modal-backdrop {
      background-color: rgba(2, 6, 23, 0.75) !important;
      backdrop-filter: blur(8px);
    }
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
          <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" placeholder="Nama Lengkap ..." required 
                 class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('recipient_name') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">
          @error('recipient_name')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
        </div>

        <div>
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. HP Penerima</label>
          <input type="tel" name="recipient_phone" value="{{ old('recipient_phone', $address->recipient_phone) }}" placeholder="08123xxxxx" required 
                 class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('recipient_phone') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">
          @error('recipient_phone')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
        </div>
      </div>

      <!-- GOOGLE MAPS PICKER PINPOINT & SEARCH (ALPINE COMPONENT) -->
      <div x-data="locationPickerComponent({
             initialLat: '{{ old('latitude', $address->latitude ?? '-2.9909') }}',
             initialLng: '{{ old('longitude', $address->longitude ?? '104.7565') }}'
           })" 
           x-init="initMap()" 
           class="space-y-3">
        
        <div class="flex items-center justify-between">
          <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
            Pinpoint Titik Lokasi Pengiriman <span class="text-rose-500">*</span>
          </label>
          <button type="button" @click="getCurrentLocation()" class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1 cursor-pointer">
            <i class="fa-solid fa-crosshairs" x-show="!isLocating"></i>
            <i class="fa-solid fa-spinner animate-spin" x-show="isLocating"></i>
            <span x-text="isLocating ? 'Mendeteksi...' : 'Gunakan GPS Saat Ini'"></span>
          </button>
        </div>

        <!-- Search Input Google Places Autocomplete -->
        <div class="relative w-full">
          <div class="relative flex items-center">
            <input 
              x-ref="searchInput"
              type="text" 
              placeholder="Cari nama jalan, gedung, atau patokan lokasi..." 
              class="w-full text-xs bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 rounded-2xl pl-10 pr-4 py-3 text-slate-900 dark:text-slate-100 outline-none focus:border-amber-500 transition-all shadow-sm"
            >
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
          </div>
        </div>

        <!-- Container Peta Google Maps -->
        <div x-ref="map" class="w-full h-64 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner z-10"></div>

        <!-- Hidden Input Koordinat untuk Submit Form Laravel -->
        <input type="hidden" name="latitude" id="latitude" x-model="lat">
        <input type="hidden" name="longitude" id="longitude" x-model="lng">

        <p class="text-[11px] text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-circle-info"></i> Geser pin penanda pada peta ke posisi persis lokasi bangunan Anda.
        </p>
      </div>

      <!-- Alamat Lengkap & Patokan -->
      <div>
        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Alamat Lengkap & Patokan</label>
        <textarea name="full_address" id="full_address" rows="3" placeholder="Nama Jalan, Nomor Rumah, RT/RW, Kecamatan, Patokan Pagar..." required 
                  class="w-full bg-slate-50 dark:bg-slate-800/50 text-sm text-slate-900 dark:text-slate-100 rounded-2xl py-3 px-4 outline-none border {{ $errors->has('full_address') ? 'border-rose-500' : 'border-slate-200 dark:border-slate-700' }} focus:border-amber-500 transition-all">{{ old('full_address', $address->full_address) }}</textarea>
        @error('full_address')<span class="text-[11px] text-rose-500 block mt-1">{{ $message }}</span>@enderror
      </div>

      <!-- Checkbox Set Primary -->
      <div class="flex items-center gap-2 pt-2">
        <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }} class="rounded border-slate-300 text-amber-500 focus:ring-amber-500 cursor-pointer">
        <label for="is_primary" class="text-xs text-slate-600 dark:text-slate-400 font-medium cursor-pointer">Jadikan sebagai alamat utama</label>
      </div>

    </div>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
      <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-amber-500/20 text-center cursor-pointer">
        {{ $address->is_edit ? 'Simpan Perubahan' : 'Tambah Alamat' }}
      </button>

      @if($address->is_edit)
        <button type="button" onclick="openDeleteModal()" class="px-5 bg-rose-500/10 hover:bg-rose-500 text-rose-600 hover:text-white border border-rose-500/20 font-bold py-3.5 rounded-2xl text-xs transition-all flex items-center gap-2 cursor-pointer">
          <i class="fa-solid fa-trash-can"></i> Hapus
        </button>
      @endif
    </div>

  </form>

  @if($address->is_edit)
    <!-- FORM DELETE (SUBMITTED VIA MODAL) -->
    <form id="delete-address-form" action="{{ route('profile.addresses.destroy', $address->id) }}" method="POST" class="hidden">
      @csrf
      @method('DELETE')
    </form>

    <!-- 1. CUSTOM CONFIRM DELETE MODAL -->
    <div id="delete-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 custom-modal-backdrop hidden transition-all duration-300">
      <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-sm w-full border border-slate-200 dark:border-slate-800 shadow-2xl text-center space-y-4">
        
        <div class="w-14 h-14 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center mx-auto text-2xl shadow-lg shadow-rose-500/10">
          <i class="fa-solid fa-trash-can"></i>
        </div>

        <div class="space-y-1">
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">Hapus Alamat Ini?</h3>
          <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Alamat yang dihapus tidak dapat dikembalikan. Apakah Anda yakin ingin melanjutkan?
          </p>
        </div>

        <div class="grid grid-cols-2 gap-3 pt-2">
          <button type="button" onclick="closeDeleteModal()" class="w-full bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold py-3 rounded-2xl text-xs transition-colors cursor-pointer">
            Batal
          </button>
          
          <button type="button" onclick="document.getElementById('delete-address-form').submit();" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 rounded-2xl text-xs transition-colors shadow-lg shadow-rose-500/20 cursor-pointer">
            Ya, Hapus
          </button>
        </div>

      </div>
    </div>
  @endif

  <!-- 2. CUSTOM GENERAL ALERT MODAL -->
  <div id="custom-alert-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 custom-modal-backdrop hidden transition-all duration-300">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 max-w-sm w-full border border-slate-200 dark:border-slate-800 shadow-2xl text-center space-y-4">
      
      <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-500 flex items-center justify-center mx-auto text-2xl shadow-lg shadow-amber-500/10">
        <i class="fa-solid fa-circle-exclamation"></i>
      </div>

      <div class="space-y-1">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Pemberitahuan</h3>
        <p id="custom-alert-modal-text" class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
          Pesan pemberitahuan.
        </p>
      </div>

      <button type="button" onclick="closeCustomAlertModal()" class="w-full bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white font-bold py-3 rounded-2xl text-xs transition-colors cursor-pointer">
        Mengerti
      </button>

    </div>
  </div>

  <!-- 3. MODAL POPUP ERROR VALIDASI FORM -->
  @if(session('error') || $errors->any())
  <div id="error-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 custom-modal-backdrop">
    <div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 text-center shadow-2xl space-y-4">
      <div class="w-14 h-14 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto text-2xl shadow-lg shadow-rose-500/10">
        <i class="fa-solid fa-circle-xmark"></i>
      </div>
      <div class="space-y-1">
        <h3 class="text-base font-bold text-slate-900 dark:text-white">Terjadi Kesalahan</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ session('error') ?? 'Mohon periksa kembali form isian Anda.' }}</p>
      </div>
      <button type="button" onclick="document.getElementById('error-modal').remove()" class="w-full bg-slate-900 dark:bg-slate-800 hover:bg-slate-800 text-white font-bold py-3 rounded-2xl text-xs transition-colors cursor-pointer">
        Tutup & Perbaiki
      </button>
    </div>
  </div>
  @endif

</div>

@push('scripts')
  <!-- Google Maps JavaScript API SDK -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places" async defer></script>

  <script>
    // Helper Modals
    function openCustomAlertModal(message) {
      const modal = document.getElementById('custom-alert-modal');
      const text = document.getElementById('custom-alert-modal-text');
      if (modal && text) {
        text.textContent = message;
        modal.classList.remove('hidden');
      }
    }

    function closeCustomAlertModal() {
      const modal = document.getElementById('custom-alert-modal');
      if (modal) modal.classList.add('hidden');
    }

    function openDeleteModal() {
      const modal = document.getElementById('delete-confirm-modal');
      if (modal) modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
      const modal = document.getElementById('delete-confirm-modal');
      if (modal) modal.classList.add('hidden');
    }

    // Alpine Component untuk Google Maps
    function locationPickerComponent(config) {
      return {
        lat: config.initialLat,
        lng: config.initialLng,
        map: null,
        marker: null,
        geocoder: null,
        autocomplete: null,
        isLocating: false,

        initMap() {
          let parsedLat = parseFloat(this.lat) || -2.9909;
          let parsedLng = parseFloat(this.lng) || 104.7565;
          const initialPos = { lat: parsedLat, lng: parsedLng };

          this.setCoordinates(parsedLat, parsedLng);

          // Pengecekan Google Maps SDK
          const checkGoogleLoaded = setInterval(() => {
            if (typeof google !== 'undefined' && google.maps) {
              clearInterval(checkGoogleLoaded);
              this.renderGoogleMap(initialPos);
            }
          }, 100);
        },

        renderGoogleMap(initialPos) {
          // 1. Inisialisasi Peta & Geocoder
          this.map = new google.maps.Map(this.$refs.map, {
            center: initialPos,
            zoom: 16,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
          });

          this.geocoder = new google.maps.Geocoder();

          // 2. Inisialisasi Marker Drag
          this.marker = new google.maps.Marker({
            position: initialPos,
            map: this.map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            title: 'Geser titik ke lokasi Anda'
          });

          // 3. Setup Autocomplete Google Places
          this.autocomplete = new google.maps.places.Autocomplete(this.$refs.searchInput, {
            types: ['geocode', 'establishment'],
            componentRestrictions: { country: 'id' } // Batasi pencarian Indonesia
          });

          // Event saat lokasi dipilih dari dropdown Autocomplete
          this.autocomplete.addListener('place_changed', () => {
            const place = this.autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            const newLat = place.geometry.location.lat();
            const newLng = place.geometry.location.lng();

            this.marker.setPosition(place.geometry.location);
            this.map.setCenter(place.geometry.location);
            this.map.setZoom(17);

            this.setCoordinates(newLat, newLng);
            
            if (place.formatted_address) {
              this.updateFullAddress(place.formatted_address);
            }
          });

          // Event Marker Digeser (Drag)
          this.marker.addListener('dragend', (e) => {
            const newLat = e.latLng.lat();
            const newLng = e.latLng.lng();
            this.setCoordinates(newLat, newLng);
            this.reverseGeocode(newLat, newLng);
          });

          // Event Peta Diklik
          this.map.addListener('click', (e) => {
            const newLat = e.latLng.lat();
            const newLng = e.latLng.lng();
            this.marker.setPosition(e.latLng);
            this.setCoordinates(newLat, newLng);
            this.reverseGeocode(newLat, newLng);
          });
        },

        setCoordinates(latitude, longitude) {
          this.lat = parseFloat(latitude).toFixed(8);
          this.lng = parseFloat(longitude).toFixed(8);
        },

        updateFullAddress(addressText) {
          const fullAddressArea = document.getElementById('full_address');
          if (fullAddressArea) {
            fullAddressArea.value = addressText;
          }
        },

        // Reverse Geocoding: Mengubah Lat & Lng menjadi alamat teks
        reverseGeocode(lat, lng) {
          if (!this.geocoder) return;

          this.geocoder.geocode({ location: { lat: parseFloat(lat), lng: parseFloat(lng) } }, (results, status) => {
            if (status === 'OK' && results[0]) {
              this.updateFullAddress(results[0].formatted_address);
              if (this.$refs.searchInput) {
                this.$refs.searchInput.value = results[0].formatted_address;
              }
            }
          });
        },

        // Tombol Ambil Lokasi GPS Pengguna
        getCurrentLocation() {
          if (!navigator.geolocation) {
            openCustomAlertModal('Browser Anda tidak mendukung layanan lokasi GPS.');
            return;
          }

          this.isLocating = true;
          navigator.geolocation.getCurrentPosition(
            (position) => {
              const lat = position.coords.latitude;
              const lng = position.coords.longitude;
              const latLng = new google.maps.LatLng(lat, lng);

              if (this.marker && this.map) {
                this.marker.setPosition(latLng);
                this.map.setCenter(latLng);
                this.map.setZoom(17);
              }

              this.setCoordinates(lat, lng);
              this.reverseGeocode(lat, lng);
              this.isLocating = false;
            },
            (error) => {
              this.isLocating = false;
              openCustomAlertModal('Gagal mengambil lokasi. Pastikan Anda mengizinkan akses GPS pada browser.');
            }
          );
        }
      }
    }
  </script>
@endpush
@endsection