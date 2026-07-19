@extends('layouts.penyuluh')
@section('title', 'Tambah Kegiatan - SIMPUL')

@push('styles')
<!-- Leaflet CSS untuk Maps -->
<link rel="stylesheet" href="[https://unpkg.com/leaflet@1.9.4/dist/leaflet.css](https://unpkg.com/leaflet@1.9.4/dist/leaflet.css)" />
@endpush

@section('content')
<div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kegiatan Baru</h2>
    
    <form action="{{ route('penyuluh.activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Baris 1: Judul & Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan <span class="text-red-500">*</span></label>
                <input type="text" name="title" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-simpul-600 focus:border-simpul-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Kegiatan <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-simpul-600">
                    <option value="">-- Pilih Kategori --</option>
                    <!-- @foreach($categories as $cat) ... @endforeach -->
                    <option value="1">Sosialisasi</option>
                </select>
            </div>
        </div>

        <!-- Sistem Koordinat Maps -->
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

        <!-- Upload File & Foto (Multi-upload) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto (Bisa pilih banyak)</label>
                <input type="file" name="photos[]" multiple accept="image/jpeg, image/png, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-simpul-50 file:text-simpul-700 hover:file:bg-simpul-100 cursor-pointer">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen (PDF, Word, Excel)</label>
                <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-simpul-700 hover:bg-simpul-800 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-md">
                Simpan Kegiatan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="[https://unpkg.com/leaflet@1.9.4/dist/leaflet.js](https://unpkg.com/leaflet@1.9.4/dist/leaflet.js)"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Koordinat default (Misal: Pusat Indonesia / Jakarta)
        var defaultLat = -6.200000;
        var defaultLng = 106.816666;

        var map = L.map('map').setView([defaultLat, defaultLng], 13);
        
        // Menggunakan OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Marker yang bisa di-drag
        var marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(map);

        // Update input field ketika marker digeser
        marker.on('dragend', function(event) {
            var position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;
        });

        // Update marker ketika map diklik
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    });
</script>
@endpush
