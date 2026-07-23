<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Laporan Kegiatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold">Laporan Saya</h3>
                        <a href="{{ route('penyuluh.activities.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            + Tambah Laporan
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="border-b py-3 px-4">Tanggal</th>
                                    <th class="border-b py-3 px-4">Judul</th>
                                    <th class="border-b py-3 px-4">Kategori</th>
                                    <th class="border-b py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="border-b py-3 px-4">{{ $activity->activity_date }}</td>
                                    <td class="border-b py-3 px-4">{{ $activity->title }}</td>
                                    <td class="border-b py-3 px-4">{{ $activity->category->name ?? '-' }}</td>
                                    <td class="border-b py-3 px-4 text-center space-x-2">
                                        <span class="text-gray-500 text-sm">Dalam Pengembangan</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada laporan kegiatan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>