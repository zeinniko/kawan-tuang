<div x-data="{
    // Bind data ke Livewire Filament secara langsung (2-way binding)
    lat: $wire.entangle('data.latitude'),
    lng: $wire.entangle('data.longitude'),
    
    searchQuery: '',
    suggestions: [],
    isLoading: false,
    showDropdown: false,
    map: null,
    marker: null,

    initMap() {
        // Koordinat default (Palembang jika kosong)
        let initialLat = this.lat ? parseFloat(this.lat) : -2.9909;
        let initialLng = this.lng ? parseFloat(this.lng) : 104.7565;

        // Set nilai awal ke entangle Livewire jika masih kosong
        if (!this.lat || !this.lng) {
            this.setCoordinates(initialLat, initialLng);
        }

        this.map = L.map(this.$refs.map).setView([initialLat, initialLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(this.map);

        this.marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(this.map);

        // 1. Event Marker Digeser -> Update Input Form
        this.marker.on('dragend', (e) => {
            let pos = e.target.getLatLng();
            this.setCoordinates(pos.lat, pos.lng);
        });

        // 2. Event Peta Diklik -> Update Marker & Input Form
        this.map.on('click', (e) => {
            this.marker.setLatLng(e.latlng);
            this.setCoordinates(e.latlng.lat, e.latlng.lng);
        });

        // Sync otomatis saat Livewire/Entangle memperbarui nilai lat/lng
        this.$watch('lat', value => this.syncMapFromInput());
        this.$watch('lng', value => this.syncMapFromInput());

        setTimeout(() => { this.map.invalidateSize(); }, 300);
    },

    // Set nilai ke entangle Livewire
    setCoordinates(latitude, longitude) {
        this.lat = parseFloat(latitude).toFixed(8);
        this.lng = parseFloat(longitude).toFixed(8);
    },

    // 3. Update Peta saat User Ketik Manual di Input Field
    syncMapFromInput() {
        let parsedLat = parseFloat(this.lat);
        let parsedLng = parseFloat(this.lng);

        if (!isNaN(parsedLat) && !isNaN(parsedLng) && this.map && this.marker) {
            let currentMarkerPos = this.marker.getLatLng();
            // Hanya geser map jika selisih koordinat signifikan (mencegah loop infinite)
            if (Math.abs(currentMarkerPos.lat - parsedLat) > 0.00001 || Math.abs(currentMarkerPos.lng - parsedLng) > 0.00001) {
                this.marker.setLatLng([parsedLat, parsedLng]);
                this.map.setView([parsedLat, parsedLng], this.map.getZoom());
            }
        }
    },

    // Autocomplete Search via Nominatim
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

    // Pilih lokasi dari dropdown rekomendasi
    selectLocation(item) {
        let newLat = parseFloat(item.lat);
        let newLng = parseFloat(item.lon);

        this.searchQuery = item.display_name;
        this.showDropdown = false;

        this.marker.setLatLng([newLat, newLng]);
        this.map.setView([newLat, newLng], 15);
        this.setCoordinates(newLat, newLng);
    }
}"
    x-init="initMap()"
    wire:ignore
    class="w-full space-y-4">

    <!-- Load CDN Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Search Input dengan Autocomplete -->
    <div class="relative w-full" @click.outside="showDropdown = false">
        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Cari Lokasi / Alamat</label>
        <div class="relative flex items-center">
            <input
                type="text"
                x-model="searchQuery"
                @input.debounce.400ms="fetchSuggestions()"
                @focus="if(suggestions.length > 0) showDropdown = true"
                placeholder="Ketik lokasi ..."
                class="w-full text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 pr-10 outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-gray-100">
            <div x-show="isLoading" class="absolute right-3 top-2.5">
                <svg class="animate-spin h-4 w-4 text-amber-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- Dropdown Rekomendasi -->
        <div
            x-show="showDropdown"
            x-transition
            class="absolute z-50 left-0 right-0 mt-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
            <template x-for="item in suggestions" :key="item.place_id">
                <div
                    @click="selectLocation(item)"
                    class="p-2.5 hover:bg-amber-50 dark:hover:bg-gray-800 cursor-pointer transition-colors text-xs text-gray-800 dark:text-gray-200 flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span x-text="item.display_name"></span>
                </div>
            </template>
        </div>
    </div>

    <!-- Container Peta Leaflet -->
    <div x-ref="map" class="w-full h-72 rounded-xl border border-gray-300 dark:border-gray-700 z-0"></div>

    <!-- Input Field Manual (Latitude & Longitude) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                Latitude <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                x-model="lat"
                placeholder="-6.26071870"
                class="w-full text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-gray-100">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                Longitude <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                x-model="lng"
                placeholder="106.81060080"
                class="w-full text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-gray-100">
        </div>
    </div>
    <p class="text-[11px] text-gray-500">Anda dapat memilih lokasi lewat peta/pencarian, atau memasukkan angka koordinat secara manual di kolom di atas.</p>
</div>