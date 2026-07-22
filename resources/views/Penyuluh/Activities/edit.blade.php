<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan Kegiatan') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('penyuluh.activities.update', $activity->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Kegiatan <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $activity->title) }}" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600 focus:border-green-600">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Kegiatan <span class="text-red-500">*</span></label>
                            <select name="category_id" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $activity->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                        <input type="date" name="activity_date" value="{{ old('activity_date', $activity->activity_date) }}" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required class="w-full rounded-lg border-gray-300 border p-2.5 focus:ring-green-600">{{ old('description', $activity->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ubah Titik Lokasi di Peta (Opsional)</label>
                        <p class="text-xs text-gray-500 mb-2">Geser pin (marker) jika lokasi sebelumnya kurang akurat.</p>
                        <div id="map" class="h-64 w-full rounded-lg border border-gray-300 mb-3 z-0"></div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Latitude</label>
                                <input type="text" id="latitude" name="latitude" value="{{ $activity->latitude }}" readonly class="w-full rounded-lg border-gray-300 border p-2 bg-gray-50 text-gray-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Longitude</label>
                                <input type="text" id="longitude" name="longitude" value="{{ $activity->longitude }}" readonly class="w-full rounded-lg border-gray-300 border p-2 bg-gray-50 text-gray-500">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Foto Tambahan</label>
                            <input type="file" name="photos[]" multiple accept="image/jpeg, image/png, image/jpg" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen Tambahan</label>
                            <input type="file" name="documents[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 space-x-3">
                        <a href="{{ route('penyuluh.activities.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition">Batal</a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-md">
                            Perbarui Kegiatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var savedLat = {{ $activity->latitude ?? '-6.200000' }};
            var savedLng = {{ $activity->longitude ?? '106.816666' }};
            var map = L.map('map').setView([savedLat, savedLng], 14);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([savedLat, savedLng], {draggable: true}).addTo(map);

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            });
        });
    </script>
</x-app-layout>