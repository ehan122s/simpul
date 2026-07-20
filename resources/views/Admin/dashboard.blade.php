<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Penyuluh') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-green-100 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-green-200">
                <div class="p-6 text-green-900">
                    <h3 class="text-2xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h3>
                    <p class="mt-2 text-lg">Total Laporan Kegiatan Anda: <span class="font-bold text-green-700">{{ $totalActivities }}</span> laporan.</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">5 Kegiatan Terakhir</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-700 text-sm">
                                    <th class="border-b py-3 px-4">Tanggal</th>
                                    <th class="border-b py-3 px-4">Judul Kegiatan</th>
                                    <th class="border-b py-3 px-4">Kategori</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="border-b py-3 px-4">{{ $activity->activity_date }}</td>
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
                    
                    @if($recentActivities->isEmpty())
                        <div class="text-center py-6">
                            <p class="text-gray-500 italic">Belum ada kegiatan yang dilaporkan.</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>