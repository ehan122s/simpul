<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Materi Penyuluhan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Pusat Materi</h3>
                        <p class="text-sm text-gray-500">Unduh panduan, modul, dan presentasi (PDF/PPT).</p>
                    </div>
                    
                    <form action="{{ route('penyuluh.materi.index') }}" method="GET" class="w-full md:w-1/3">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..." 
                                class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-green-600 focus:border-green-600 text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($materials as $materi)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition duration-300">
                        <div class="flex items-start justify-between mb-4">
                            @if($materi->tipe_file === 'pdf')
                                <div class="p-3 bg-red-100 text-red-600 rounded-lg">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
                                </div>
                            @else
                                <div class="p-3 bg-orange-100 text-orange-600 rounded-lg">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v8h8V6H6z" clip-rule="evenodd"></path></svg>
                                </div>
                            @endif
                            <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ strtoupper($materi->tipe_file) }}</span>
                        </div>
                        
                        <h4 class="font-bold text-gray-800 text-sm mb-1 line-clamp-2">{{ $materi->judul }}</h4>
                        <p class="text-xs text-gray-500 mb-4">{{ $materi->created_at->format('d M Y') }}</p>
                        
                        <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank" class="block w-full text-center bg-green-50 hover:bg-green-100 text-green-700 font-semibold py-2 rounded-lg text-sm transition">
                            Buka Materi
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8 bg-white rounded-xl border border-gray-100">
                        <p class="text-gray-500">Materi tidak ditemukan.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $materials->links() }}
            </div>

        </div>
    </div>
</x-app-layout>