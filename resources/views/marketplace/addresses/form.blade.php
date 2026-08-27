@extends('welcome')

@section('title', ($address->is_edit ? 'Edit Alamat' : 'Tambah Alamat Baru') . ' - Kawan Tuang')

@push('styles')
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <!-- Alpine JS CDN -->
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    .leaflet-container { z-index: 10 !important; }
    
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

      <!-- MAP PICKER PINPOINT & SEARCH (ALPINE COMPONENT) -->
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
            <i class="fa-solid fa-crosshairs"></i> Gunakan GPS Saat Ini
          </button>
        </div>

        <!-- Search Input dengan Autocomplete Nominatim -->
        <div class="relative w-full" @click.outside="showDropdown = false">
          <div class="relative flex items-center">
            <input 
              type="text" 
              x-model="searchQuery" 
              @input.debounce.400ms="fetchSuggestions()"
              @focus="if(suggestions.length > 0) showDropdown = true"
              placeholder="Cari nama jalan, gedung, atau daerah..." 
              class="w-full text-xs bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 rounded-2xl pl-10 pr-10 py-3 text-slate-900 dark:text-slate-100 outline-none focus:border-amber-500 transition-all"
            >
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 text-slate-400 text-xs"></i>
            
            <div x-show="isLoading" class="absolute right-3.5">
              <i class="fa-solid fa-spinner animate-spin text-amber-500 text-xs"></i>
            </div>
          </div>

          <!-- Dropdown Rekomendasi Lokasi -->
          <div 
            x-show="showDropdown" 
            x-transition
            class="absolute z-50 left-0 right-0 mt-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl max-h-56 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800"
          >
            <template x-for="item in suggestions" :key="item.place_id">
              <div 
                @click="selectLocation(item)" 
                class="p-3 hover:bg-amber-50 dark:hover:bg-slate-800/60 cursor-pointer transition-colors text-xs text-slate-800 dark:text-slate-200 flex items-start gap-2.5"
              >
                <i class="fa-solid fa-location-dot text-amber-500 mt-0.5 shrink-0"></i>
                <span x-text="item.display_name"></span>
              </div>
            </template>
          </div>
        </div>

        <!-- Container Peta Leaflet -->
        <div x-ref="map" class="w-full h-64 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-inner z-10"></div>

        <!-- Hidden Input Koordinat untuk Submit Form Laravel -->
        <input type="hidden" name="latitude" id="latitude" x-model="lat">
        <input type="hidden" name="longitude" id="longitude" x-model="lng">

        <p class="text-[11px] text-slate-400 flex items-center gap-1">
          <i class="fa-solid fa-circle-info"></i> Geser penanda peta ke posisi persis lokasi bangunan Anda.
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

  <!-- 2. CUSTOM GENERAL ALERT MODAL (Menggantikan alert biasa/GPS error) -->
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
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    // Fungsi Menampilkan Custom Modal Alert
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

    function locationPickerComponent(config) {
      return {
        lat: config.initialLat,
        lng: config.initialLng,
        searchQuery: '',
        suggestions: [],
        isLoading: false,
        showDropdown: false,
        map: null,
        marker: null,

        initMap() {
          let parsedLat = parseFloat(this.lat) || -2.9909;
          let parsedLng = parseFloat(this.lng) || 104.7565;

          this.lat = parsedLat.toFixed(8);
          this.lng = parsedLng.toFixed(8);

          this.map = L.map(this.$refs.map).setView([parsedLat, parsedLng], 15);

          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
          }).addTo(this.map);

          const customIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
            iconSize: [36, 36],
            iconAnchor: [18, 36],
          });

          this.marker = L.marker([parsedLat, parsedLng], {
            draggable: true,
            icon: customIcon
          }).addTo(this.map);

          // Event marker digeser
          this.marker.on('dragend', (e) => {
            let pos = e.target.getLatLng();
            this.setCoordinates(pos.lat, pos.lng);
            this.reverseGeocode(pos.lat, pos.lng);
          });

          // Event peta diklik
          this.map.on('click', (e) => {
            this.marker.setLatLng(e.latlng);
            this.setCoordinates(e.latlng.lat, e.latlng.lng);
            this.reverseGeocode(e.latlng.lat, e.latlng.lng);
          });

          setTimeout(() => { this.map.invalidateSize(); }, 300);
        },

        setCoordinates(latitude, longitude) {
          this.lat = parseFloat(latitude).toFixed(8);
          this.lng = parseFloat(longitude).toFixed(8);
        },

        fetchSuggestions() {
          if (this.searchQuery.trim().length < 3) {
            this.suggestions = [];
            this.showDropdown = false;
            return;
          }

          this.isLoading = true;
          let url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=5&addressdetails=1`;

          fetch(url)
            .then(res => res.json())
            .then(data => {
              this.suggestions = data || [];
              this.showDropdown = this.suggestions.length > 0;
              this.isLoading = false;
            })
            .catch(err => {
              console.error(err);
              this.isLoading = false;
            });
        },

        selectLocation(item) {
          let newLat = parseFloat(item.lat);
          let newLng = parseFloat(item.lon);

          this.searchQuery = item.display_name;
          this.showDropdown = false;

          this.marker.setLatLng([newLat, newLng]);
          this.map.setView([newLat, newLng], 16);
          this.setCoordinates(newLat, newLng);
          
          // Auto-fill textarea alamat
          const fullAddressArea = document.getElementById('full_address');
          if (fullAddressArea) {
            fullAddressArea.value = item.display_name;
          }
        },

        reverseGeocode(lat, lng) {
          fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
              if (data && data.display_name) {
                const fullAddressArea = document.getElementById('full_address');
                if (fullAddressArea && !fullAddressArea.value.trim()) {
                  fullAddressArea.value = data.display_name;
                }
              }
            })
            .catch(err => console.error(err));
        },

        getCurrentLocation() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
              const lat = position.coords.latitude;
              const lng = position.coords.longitude;

              this.map.setView([lat, lng], 16);
              this.marker.setLatLng([lat, lng]);

              this.setCoordinates(lat, lng);
              this.reverseGeocode(lat, lng);
            }, () => {
              openCustomAlertModal('Gagal mengambil lokasi. Pastikan izin akses GPS pada browser telah diaktifkan.');
            });
          } else {
            openCustomAlertModal('Browser Anda tidak mendukung layanan lokasi GPS.');
          }
        }
      }
    }
  </script>
@endpush
@endsection