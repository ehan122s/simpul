<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Laporan Kegiatan') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('penyuluh.activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600 focus:border-green-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Kegiatan <span class="text-red-500">*</span></label>
                            <select name="category_id" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                            <input type="date" name="activity_date" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Titik Lokasi di Peta</label>
                        <p class="text-xs text-gray-500 mb-2">Geser pin (marker) ke lokasi kegiatan Anda.</p>
                        <div id="map" class="h-64 w-full rounded-lg border border-gray-300 mb-3 z-0"></div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Latitude</label>
                                <input type="text" id="latitude" name="latitude" readonly class="w-full rounded-lg border-gray-300 border p-2 bg-gray-50 text-gray-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Longitude</label>
                                <input type="text" id="longitude" name="longitude" readonly class="w-full rounded-lg border-gray-300 border p-2 bg-gray-50 text-gray-500">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto (Bisa pilih banyak)</label>
                            <input type="file" name="photos[]" multiple accept="image/jpeg, image/png, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen (PDF, Word, Excel)</label>
                            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 space-x-3">
                        <a href="{{ route('penyuluh.activities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition">Batal</a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-md">
                            Simpan Kegiatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fokuskan Peta langsung ke pusat Kabupaten Garut
                var defaultLat = -7.2279; 
                var defaultLng = 107.9087;
                var map = L.map('map').setView([defaultLat, defaultLng], 11); // Angka 11 agar zoom-nya pas di Garut
                            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

            function defaultStyle() { return { color: "#16a34a", weight: 1.5, fillColor: "#22c55e", fillOpacity: 0.15 }; }
            function activeStyle() { return { color: "#dc2626", weight: 3.5, fillColor: "#ef4444", fillOpacity: 0.35 }; }

            var geojsonLayer;
            var selectedLayer = null;

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });

            // LOAD DATA BANDUNG & GARUT SAJA
            const geojsonUrls = [
                '/geojson/garut.geojson' 
            ];

            Promise.all(geojsonUrls.map(url => fetch(url).then(res => res.ok ? res.json() : null).catch(() => null)))
            .then(results => {
                let combinedFeatures = [];
                results.forEach(data => {
                    if (data && data.features) {
                        combinedFeatures = combinedFeatures.concat(data.features);
                    }
                });

                if (combinedFeatures.length === 0) return;

                let combinedGeoJSON = { type: "FeatureCollection", features: combinedFeatures };

                geojsonLayer = L.geoJSON(combinedGeoJSON, {
                    style: defaultStyle,
                    onEachFeature: function (feature, layer) {
                        layer.on('click', function (e) {
                            if (selectedLayer) { geojsonLayer.resetStyle(selectedLayer); }
                            layer.setStyle(activeStyle());
                            selectedLayer = layer;
                            marker.setLatLng(e.latlng);
                            document.getElementById('latitude').value = e.latlng.lat;
                            document.getElementById('longitude').value = e.latlng.lng;

                            var namaKec = feature.properties.KECAMATAN || feature.properties.kecamatan || "Terpilih";
                            var areaInMeters = turf.area(feature); 
                            var areaInHa = (areaInMeters / 10000).toLocaleString('id-ID', { maximumFractionDigits: 2 });
                            var areaInKm2 = (areaInMeters / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 2 });

                            var popupText = `
                                <div style="font-family: sans-serif; min-width: 160px;">
                                    <h4 style="margin:0 0 6px 0; color:#dc2626; font-size:14px; font-weight:bold; border-b:1px solid #fee2e2; padding-bottom:4px;">
                                        📍 Kec. ${namaKec}
                                    </h4>
                                    <p style="margin: 2px 0; font-size: 12px; color: #374151;">
                                        <b>Luas Area:</b><br>
                                        <span style="color:#dc2626; font-weight:bold; font-size:13px;">± ${areaInHa} Ha</span><br>
                                        <span style="color:#6b7280;">(${areaInKm2} km²)</span>
                                    </p>
                                </div>
                            `;
                            layer.bindPopup(popupText).openPopup();
                        });
                    }
                }).addTo(map);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            });
        });
    </script>
</x-app-layout>