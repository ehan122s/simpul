<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <div>
                <h2 class="text-2xl font-bold text-green-900">
                    Dashboard Penyuluh
                </h2>
                <p class="text-green-700 text-sm mt-1">
                    Selamat datang kembali, {{ auth()->user()->name }} 👋
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-green-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Welcome -->
            <div class="bg-gradient-to-r from-green-800 to-green-600 rounded-2xl shadow-lg text-white p-8 mb-8">

                <div class="flex flex-col md:flex-row justify-between items-center">

                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            Halo, {{ auth()->user()->name }} 👋
                        </h1>

                        <p class="text-green-100">
                            Semoga harimu menyenangkan.
                            Kelola laporan kegiatan dengan mudah melalui dashboard ini.
                        </p>
                    </div>

                    <div class="mt-6 md:mt-0">
                        <div class="bg-white/20 rounded-xl px-6 py-4 text-center">
                            <p class="text-sm text-green-100">
                                Total Laporan
                            </p>

                            <h2 class="text-4xl font-bold">
                                {{ $totalActivities }}
                            </h2>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="bg-white rounded-2xl shadow-md p-6 border-l-8 border-green-700">

                    <p class="text-gray-500 text-sm">
                        Total Laporan
                    </p>

                    <h2 class="text-4xl font-bold text-green-800 mt-2">
                        {{ $totalActivities }}
                    </h2>

                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 border-l-8 border-green-500">

                    <p class="text-gray-500 text-sm">
                        Kegiatan Terbaru
                    </p>

                    <h2 class="text-4xl font-bold text-green-700 mt-2">
                        {{ $recentActivities->count() }}
                    </h2>

                </div>

            </div>

            <!-- Tabel -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                <div class="bg-green-800 px-6 py-4">

                    <h3 class="text-white text-xl font-semibold">
                        5 Kegiatan Terakhir
                    </h3>

                </div>

                @if($recentActivities->isEmpty())

                    <div class="py-16 text-center">

                        <div class="text-6xl mb-4">
                            📋
                        </div>

                        <p class="text-gray-500 text-lg">
                            Belum ada kegiatan yang dilaporkan.
                        </p>

                    </div>

                @else

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-green-100">

                                <tr>

                                    <th class="px-6 py-4 text-left text-green-900 font-semibold">
                                        Tanggal
                                    </th>

                                    <th class="px-6 py-4 text-left text-green-900 font-semibold">
                                        Judul Kegiatan
                                    </th>

                                    <th class="px-6 py-4 text-left text-green-900 font-semibold">
                                        Kategori
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($recentActivities as $activity)

                                <tr class="hover:bg-green-50 transition">

                                    <td class="px-6 py-4 border-b">
                                        {{ $activity->activity_date }}
                                    </td>

                                    <td class="px-6 py-4 border-b font-medium">
                                        {{ $activity->title }}
                                    </td>

                                    <td class="px-6 py-4 border-b">

                                        <span class="px-3 py-1 rounded-full text-sm bg-green-700 text-white">

                                            {{ $activity->category->name ?? '-' }}

                                        </span>

                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @endif

            </div>

        </div>
    </div>

</x-app-layout>