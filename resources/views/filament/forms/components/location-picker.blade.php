@once
    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places"></script>
    @endpush
@endonce

<div x-data="{
    // Bind data ke Livewire Filament (Latitude, Longitude, & Alamat)
    lat: $wire.entangle('data.latitude'),
    lng: $wire.entangle('data.longitude'),
    address: $wire.entangle('data.address'),

    map: null,
    marker: null,
    autocomplete: null,
    geocoder: null,
    isLocating: false,

    initMap() {
        if (typeof google === 'undefined') {
            console.error('Google Maps API Key belum terpasang atau gagal di-load.');
            return;
        }

        let initialLat = this.lat ? parseFloat(this.lat) : -2.9909;
        let initialLng = this.lng ? parseFloat(this.lng) : 104.7565;
        const initialPos = { lat: initialLat, lng: initialLng };

        // Set koordinat default jika kosong
        if (!this.lat || !this.lng) {
            this.setCoordinates(initialLat, initialLng);
        }

        // 1. Inisialisasi Map & Geocoder
        this.map = new google.maps.Map(this.$refs.mapContainer, {
            center: initialPos,
            zoom: 15,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });

        this.geocoder = new google.maps.Geocoder();

        // 2. Inisialisasi Marker
        this.marker = new google.maps.Marker({
            position: initialPos,
            map: this.map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            title: 'Geser ke lokasi toko'
        });

        // 3. Google Places Autocomplete
        this.autocomplete = new google.maps.places.Autocomplete(this.$refs.searchInput, {
            types: ['geocode', 'establishment'],
            componentRestrictions: { country: 'id' } // Batasi pencarian area Indonesia
        });

        // Event saat user memilih lokasi dari dropdown Autocomplete Google
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
                this.address = place.formatted_address;
            }
        });

        // 4. Event Marker Digeser (Drag)
        this.marker.addListener('dragend', (e) => {
            const newLat = e.latLng.lat();
            const newLng = e.latLng.lng();
            this.setCoordinates(newLat, newLng);
            this.reverseGeocode(newLat, newLng);
        });

        // 5. Event Peta Diklik
        this.map.addListener('click', (e) => {
            const newLat = e.latLng.lat();
            const newLng = e.latLng.lng();
            this.marker.setPosition(e.latLng);
            this.setCoordinates(newLat, newLng);
            this.reverseGeocode(newLat, newLng);
        });

        // Sync jika angka input manual diubah
        this.$watch('lat', () => this.syncMapFromInput());
        this.$watch('lng', () => this.syncMapFromInput());
    },

    setCoordinates(latitude, longitude) {
        this.lat = parseFloat(latitude).toFixed(8);
        this.lng = parseFloat(longitude).toFixed(8);
    },

    // Reverse Geocoding: Mengubah Lat/Lng menjadi Alamat Lengkap Teks
    reverseGeocode(latitude, longitude) {
        this.geocoder.geocode({ location: { lat: latitude, lng: longitude } }, (results, status) => {
            if (status === 'OK' && results[0]) {
                this.address = results[0].formatted_address;
                this.$refs.searchInput.value = results[0].formatted_address;
            }
        });
    },

    // Tombol Ambil Lokasi GPS User Saat Ini
    getCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Browser kamu tidak mendukung Geolocation GPS.');
            return;
        }

        this.isLocating = true;
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const newLat = pos.coords.latitude;
                const newLng = pos.coords.longitude;
                const latLng = new google.maps.LatLng(newLat, newLng);

                this.marker.setPosition(latLng);
                this.map.setCenter(latLng);
                this.map.setZoom(17);

                this.setCoordinates(newLat, newLng);
                this.reverseGeocode(newLat, newLng);
                this.isLocating = false;
            },
            (err) => {
                alert('Gagal mengambil lokasi: ' + err.message);
                this.isLocating = false;
            }
        );
    },

    syncMapFromInput() {
        let parsedLat = parseFloat(this.lat);
        let parsedLng = parseFloat(this.lng);

        if (!isNaN(parsedLat) && !isNaN(parsedLng) && this.map && this.marker) {
            let currentPos = this.marker.getPosition();
            if (Math.abs(currentPos.lat() - parsedLat) > 0.00001 || Math.abs(currentPos.lng() - parsedLng) > 0.00001) {
                const newPos = { lat: parsedLat, lng: parsedLng };
                this.marker.setPosition(newPos);
                this.map.setCenter(newPos);
            }
        }
    }
}"
x-init="initMap()"
wire:ignore
class="w-full space-y-4">

    <!-- Header & Search Box Autocomplete Google Maps -->
    <div class="space-y-1">
        <div class="flex items-center justify-between">
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Cari Lokasi / Patokan Toko (Google Maps)</label>
            <button 
                type="button" 
                @click="getCurrentLocation()"
                class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline">
                <svg x-show="!isLocating" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span x-text="isLocating ? 'Mendeteksi GPS...' : 'Gunakan Lokasi Saya Sekarang'"></span>
            </button>
        </div>
        <input
            x-ref="searchInput"
            type="text"
            placeholder="Ketik nama jalan, gedung, atau toko sekitar..."
            class="w-full text-sm bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-gray-100 shadow-sm">
    </div>

    <!-- Map Container -->
    <div x-ref="mapContainer" class="w-full h-80 rounded-xl border border-gray-300 dark:border-gray-700 shadow-inner z-0"></div>

    <!-- Manual Lat/Lng Fields -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                Latitude <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                x-model="lat"
                class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-gray-100 font-mono">
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                Longitude <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                x-model="lng"
                class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-amber-500 text-gray-900 dark:text-gray-100 font-mono">
        </div>
    </div>
</div>