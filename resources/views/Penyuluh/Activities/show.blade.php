<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Kegiatan') }}
        </h2>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6 border-b pb-4">
                    <h2 class="text-2xl font-bold text-gray-800">{{ $activity->title }}</h2>
                    <div class="space-x-2">
                        <a href="{{ route('penyuluh.activities.downloadPdf', $activity->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded transition">Unduh PDF</a>
                        <a href="{{ route('penyuluh.activities.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-2 px-4 rounded transition">Kembali</a>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase">Kategori</h3>
                                <p class="text-gray-900 font-medium">{{ $activity->category->name ?? '-' }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase">Tanggal</h3>
                                <p class="text-gray-900 font-medium">{{ $activity->activity_date }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase mb-1">Deskripsi</h3>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-gray-700 text-sm whitespace-pre-wrap">
                                {{ $activity->description }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Lokasi Peta</h3>
                        <div id="map" class="h-64 w-full rounded-lg border border-gray-300 z-0"></div>
                        <p class="text-xs text-gray-500 mt-2">Lat: {{ $activity->latitude }} | Lng: {{ $activity->longitude }}</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Dokumentasi Foto</h3>
                    @if($activity->photos->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($activity->photos as $photo)
                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Foto Kegiatan" class="w-full h-32 object-cover rounded-lg border border-gray-200 shadow-sm">
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm italic">Tidak ada foto yang dilampirkan.</p>
                    @endif
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Dokumen Lampiran</h3>
                    @if($activity->documents->count() > 0)
                        <ul class="space-y-2">
                            @foreach($activity->documents as $doc)
                                <li class="flex items-center space-x-3 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm font-medium">
                                        Unduh Dokumen #{{ $loop->iteration }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500 text-sm italic">Tidak ada dokumen yang dilampirkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var lat = {{ $activity->latitude ?? '-6.200000' }};
            var lng = {{ $activity->longitude ?? '106.816666' }};

            var map = L.map('map', { dragging: false, scrollWheelZoom: false, zoomControl: true }).setView([lat, lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(map);
            L.marker([lat, lng]).addTo(map);
        });
    </script>
</x-app-layout>