@extends('layouts.penyuluh')
@section('title', 'Dashboard - SIMPUL')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500">Ringkasan aktivitas penyuluhan Anda.</p>
</div>

<!-- Statistik Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Total Kegiatan</p>
            <h3 class="text-2xl font-bold text-gray-800">24</h3>
        </div>
    </div>
    <!-- Card lainnya (Dokumen, Foto, dll) diletakkan di sini dengan struktur yang sama -->
</div>

<!-- Tabel Kegiatan Terbaru -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h2 class="font-bold text-gray-800">Kegiatan Terbaru</h2>
        <a href="{{ route('penyuluh.activities.index') }}" class="text-simpul-600 text-sm hover:underline">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-sm">
                    <th class="px-6 py-3 font-medium">Tanggal</th>
                    <th class="px-6 py-3 font-medium">Judul Kegiatan</th>
                    <th class="px-6 py-3 font-medium">Desa</th>
                    <th class="px-6 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-100">
                <!-- Nanti diisi dengan @foreach dari database -->
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-gray-600">12 Okt 2024</td>
                    <td class="px-6 py-4 font-medium text-gray-800">Penyuluhan Tani Hutan Mandiri</td>
                    <td class="px-6 py-4 text-gray-600">Desa Sukamaju</td>
                    <td class="px-6 py-4">
                        <button class="text-simpul-600 hover:text-simpul-800">Detail</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
