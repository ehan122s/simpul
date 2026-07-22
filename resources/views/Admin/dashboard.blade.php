<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-blue-100 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-blue-200">
                <div class="p-6 text-blue-900">
                    <h3 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h3>
                    <p class="mt-1 text-md">Anda login sebagai Administrator Utama SIMPUL.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-gray-500 text-sm font-semibold uppercase">Total Penyuluh</h4>
                    <span class="text-3xl font-bold text-gray-800">{{ $totalPenyuluh }}</span>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-gray-500 text-sm font-semibold uppercase">Total Kategori</h4>
                    <span class="text-3xl font-bold text-gray-800">{{ $totalCategories }}</span>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h4 class="text-gray-500 text-sm font-semibold uppercase">Total Laporan</h4>
                    <span class="text-3xl font-bold text-green-600">{{ $totalActivities }}</span>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">5 Laporan Kegiatan Terbaru (Nasional)</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-700 text-sm">
                                    <th class="border-b py-3 px-4">Tanggal</th>
                                    <th class="border-b py-3 px-4">Nama Penyuluh</th>
                                    <th class="border-b py-3 px-4">Judul Kegiatan</th>
                                    <th class="border-b py-3 px-4">Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestActivities as $activity)
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="border-b py-3 px-4">{{ $activity->activity_date }}</td>
                                    <td class="border-b py-3 px-4 font-semibold text-blue-600">{{ $activity->user->name ?? 'Tidak Diketahui' }}</td>
                                    <td class="border-b py-3 px-4">{{ $activity->title }}</td>
                                    <td class="border-b py-3 px-4">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">
                                            {{ $activity->category->name ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($latestActivities->isEmpty())
                        <div class="text-center py-6">
                            <p class="text-gray-500 italic">Belum ada kegiatan yang dilaporkan oleh penyuluh.</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>