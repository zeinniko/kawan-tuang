@extends('welcome')

@section('title', 'Our Store & Official Brands - Kawan Tuang')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #store-map {
        height: 420px;
        width: 100%;
        border-radius: 1.5rem;
        z-index: 1;
    }

    .leaflet-popup-content-wrapper {
        background: #0f172a !important;
        color: #f8fafc !important;
        border-radius: 1rem !important;
        border: 1px solid rgba(245, 158, 11, 0.3) !important;
        padding: 4px !important;
    }

    .leaflet-popup-tip {
        background: #0f172a !important;
    }

    .brand-stack-card:hover .stack-bg-1 {
        transform: rotate(-4deg) scale(0.98);
    }

    .brand-stack-card:hover .stack-bg-2 {
        transform: rotate(4deg) scale(0.98);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

    <!-- BREADCRUMB & PAGE HEADER -->
    <div>
        <nav class="flex text-xs text-slate-500 dark:text-slate-400 gap-2 mb-2">
            <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
            <span>/</span>
            <span class="text-slate-900 dark:text-slate-200 font-medium">Our Store & Brands</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-store text-lg"></i>
                    </span>
                    Cabang Toko & Brand Resmi
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Temukan titik toko fisik Kawan Tuang terdekat dan eksplorasi koleksi brand original kami.</p>
            </div>
        </div>
    </div>

    <!-- SECTION 1: INTERACTIVE FULL MAP & CABANG TOKO LIST -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-map-location-dot text-amber-500"></i> Peta Lokasi Cabang
            </h2>
            <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20 px-3 py-1 rounded-full">
                {{ count($stores) }} Cabang Resmi
            </span>
        </div>

        <!-- Peta Leaflet Container -->
        <div id="store-map" class="border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden rounded-3xl"></div>

        <!-- Cards Cabang Toko Horizontal Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
            @forelse($stores as $store)
            @php
            $sName = $store->name ?? 'Kawan Tuang Store';
            $sAddress = $store->address ?? '-';
            $sOpen = $store->open_time ? \Carbon\Carbon::parse($store->open_time)->format('H:i') : '';
            $sClose = $store->close_time ? \Carbon\Carbon::parse($store->close_time)->format('H:i') : '';
            $sHours = $sOpen . ' - ' . $sClose . ' WIB';
            $sLat = $store->latitude ?? '-2.9909';
            $sLng = $store->longitude ?? '104.7565';
            @endphp
            <div onclick="focusStoreMap({{ $sLat }}, {{ $sLng }}, '{{ addslashes($sName) }}')"
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-amber-500/60 rounded-3xl p-5 transition-all cursor-pointer group shadow-sm flex flex-col justify-between">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider border border-amber-500/20">
                            Kode: {{ $store->store_code ?? 'KT' }}
                        </span>
                        <i class="fa-solid fa-crosshairs text-slate-400 group-hover:text-amber-500 transition-colors text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-base group-hover:text-amber-500 transition-colors">
                        {{ $sName }}
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2">
                        <i class="fa-solid fa-location-dot text-amber-500 mr-1"></i> {{ $sAddress }}
                    </p>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                    <span><i class="fa-regular fa-clock text-amber-500 mr-1"></i> {{ $sHours }}</span>
                    <span class="font-bold text-amber-600 dark:text-amber-400 group-hover:underline">Lihat di Peta &rarr;</span>
                </div>
            </div>
            @empty
            <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                Belum ada data toko cabang yang tersedia.
            </div>
            @endforelse
        </div>
    </div>

    <hr class="border-slate-200/80 dark:border-slate-800/80">

    <!-- SECTION 2: EXPLORE BRANDS (ALPINE JS COMPONENT ONLY FOR BRAND SECTION) -->
    <div class="space-y-6"
        x-data="{
          searchQuery: '',
          selectedCountry: '',
          brands: {{ json_encode($brands) }},
          
          get filteredBrands() {
              return this.brands.filter(b => {
                  const matchName = b.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                  const matchCountry = this.selectedCountry === '' || b.country_origin === this.selectedCountry;
                  return matchName && matchCountry;
              });
          }
       }">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-award text-amber-500"></i> Eksplorasi Brand
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Filter brand internasional favoritmu berdasarkan negara produsennya.</p>
            </div>

            <!-- SEARCH & FILTER TOOLBAR -->
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <!-- Input Search Brand -->
                <div class="relative w-full sm:w-64">
                    <input type="text" x-model="searchQuery" placeholder="Cari nama brand..."
                        class="w-full bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 rounded-2xl py-3 pl-10 pr-4 border border-slate-200 dark:border-slate-800 outline-none focus:border-amber-500 transition-all shadow-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                </div>

                <!-- Dropdown Filter Negara -->
                <div class="relative w-full sm:w-48">
                    <select x-model="selectedCountry"
                        class="w-full bg-white dark:bg-slate-900 text-xs font-semibold text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-3 outline-none focus:border-amber-500 transition-colors cursor-pointer appearance-none truncate pr-8 shadow-sm">
                        <option value="">Semua Negara Origin</option>
                        @foreach($countries as $country)
                        <option value="{{ $country }}">{{ $country }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3.5 top-3.5 text-slate-400 text-xs pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- BRAND GRID CARDS WITH STACK EFFECT -->
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            <template x-for="brand in filteredBrands" :key="brand.id">
                <a :href="'/catalog?brand=' + (brand.slug || brand.name.toLowerCase().replace(/\s+/g, '-'))"
                    class="brand-stack-card group relative bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-4 transition-all hover:border-amber-500/60 shadow-sm flex flex-col items-center text-center">

                    <!-- Stack Background Decorative Cards -->
                    <div class="stack-bg-1 absolute inset-2 bg-slate-100 dark:bg-slate-800 rounded-2xl -z-10 transition-transform duration-300"></div>
                    <div class="stack-bg-2 absolute inset-2 bg-amber-500/10 rounded-2xl -z-20 transition-transform duration-300"></div>

                    <!-- Logo Container -->
                    <div class="w-full aspect-square bg-slate-50 dark:bg-slate-950 rounded-2xl p-4 flex items-center justify-center overflow-hidden mb-3 border border-slate-100 dark:border-slate-800/80">
                        <img :src="brand.logo_url || 'https://images.unsplash.com/photo-1527281400683-1aae777175f8?auto=format&fit=crop&w=300&q=80'"
                            :alt="brand.name"
                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                    </div>

                    <!-- Brand Title -->
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-amber-500 transition-colors line-clamp-1" x-text="brand.name"></h3>

                    <!-- Country Origin Badge -->
                    <div class="mt-1 flex items-center justify-center gap-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                        <i class="fa-solid fa-earth-americas text-amber-500 text-[10px]"></i>
                        <span x-text="brand.country_origin || 'International'"></span>
                    </div>

                </a>
            </template>

            <!-- Empty State Search -->
            <div x-show="filteredBrands.length === 0" class="col-span-full py-12 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-6">
                <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3 text-xl">
                    <i class="fa-solid fa-wine-glass"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white">Brand Tidak Ditemukan</h3>
                <p class="text-xs text-slate-500 mt-1 mb-4">Coba ubah kata kunci pencarian atau reset filter negara asal.</p>
                <button type="button" @click="searchQuery = ''; selectedCountry = '';" class="bg-amber-500 text-slate-950 font-bold px-4 py-2 rounded-xl text-xs cursor-pointer">
                    Reset Filter Brand
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let storeMap, markers = [];

    document.addEventListener('DOMContentLoaded', function() {
        const storeData = @json($stores);

        let defaultLat = -2.9909;
        let defaultLng = 104.7565;

        if (storeData.length > 0 && storeData[0].latitude) {
            defaultLat = parseFloat(storeData[0].latitude);
            defaultLng = parseFloat(storeData[0].longitude);
        }

        const mapElement = document.getElementById('store-map');
        if (!mapElement) return;

        storeMap = L.map('store-map').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(storeMap);

        const customIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -34]
        });

        storeData.forEach(store => {
            const lat = parseFloat(store.latitude || defaultLat);
            const lng = parseFloat(store.longitude || defaultLng);
            const name = store.name || 'Cabang Toko';
            const address = store.address || '-';
            const hours = (store.open_time ? store.open_time.substring(0, 5) : '') + ' - ' + (store.close_time ? store.close_time.substring(0, 5) : '') + ' WIB';

            const marker = L.marker([lat, lng], {
                icon: customIcon
            }).addTo(storeMap);

            const popupContent = `
          <div class="p-2 space-y-1">
            <span class="text-[9px] font-bold text-amber-400 uppercase tracking-wider">KT Official Store</span>
            <h4 class="font-bold text-xs text-white">${name}</h4>
            <p class="text-[11px] text-slate-300 leading-tight">${address}</p>
            <p class="text-[10px] text-amber-500 font-semibold pt-1"><i class="fa-regular fa-clock"></i> ${hours}</p>
          </div>
        `;

            marker.on('mouseover', function() {
                this.openPopup();
            });
            marker.bindPopup(popupContent);

            markers.push({
                lat,
                lng,
                marker
            });
        });

        setTimeout(() => {
            if (storeMap) storeMap.invalidateSize();
        }, 400);
    });

    function focusStoreMap(lat, lng, name) {
        if (storeMap) {
            storeMap.setView([lat, lng], 16, {
                animate: true
            });
            const found = markers.find(m => Math.abs(m.lat - lat) < 0.0001 && Math.abs(m.lng - lng) < 0.0001);
            if (found) {
                found.marker.openPopup();
            }
        }
    }
</script>
@endpush