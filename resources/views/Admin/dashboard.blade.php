<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-green-900">
                    Dashboard Admin
                </h2>
                <p class="text-green-700 mt-1">
                    Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span> 👋
                </p>
            </div>

            <div class="mt-4 md:mt-0">
                <span class="bg-green-700 text-white px-5 py-2 rounded-full shadow">
                    Administrator
                </span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-green-50 py-8">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Banner -->
            <div class="bg-gradient-to-r from-green-900 via-green-700 to-green-600 rounded-3xl shadow-xl p-8 text-white mb-8">

                <div class="flex flex-col lg:flex-row justify-between items-center">

                    <div>

                        <h1 class="text-4xl font-bold">
                            Halo, {{ auth()->user()->name }} 👋
                        </h1>

                        <p class="mt-3 text-green-100 text-lg">
                            Selamat datang di Sistem Monitoring Penyuluhan (SIMPUL).
                            Pantau seluruh aktivitas penyuluh dari satu dashboard.
                        </p>

                    </div>

                    <div class="mt-8 lg:mt-0">

                        <div class="bg-white/20 backdrop-blur-md rounded-2xl p-6 text-center">

                            <p class="text-green-100">
                                Total Laporan
                            </p>

                            <h2 class="text-5xl font-bold mt-2">
                                {{ $totalActivities }}
                            </h2>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <!-- Penyuluh -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-[8px] border-green-700">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Total Penyuluh
                            </p>

                            <h2 class="text-4xl font-bold text-green-800 mt-2">
                                {{ $totalPenyuluh }}
                            </h2>

                        </div>

                        <div class="bg-green-100 p-4 rounded-full">

                            👨‍🌾

                        </div>

                    </div>

                </div>

                <!-- Laporan -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-[8px] border-green-600">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Total Laporan
                            </p>

                            <h2 class="text-4xl font-bold text-green-700 mt-2">
                                {{ $totalActivities }}
                            </h2>

                        </div>

                        <div class="bg-green-100 p-4 rounded-full">

                            📄

                        </div>

                    </div>

                </div>

                <!-- Kategori -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border-l-[8px] border-green-500">

                    <div class="flex justify-between items-center">

                        <div>

                            <p class="text-gray-500 text-sm">
                                Total Kategori
                            </p>

                            <h2 class="text-4xl font-bold text-green-600 mt-2">
                                {{ $totalCategories }}
                            </h2>

                        </div>

                        <div class="bg-green-100 p-4 rounded-full">

                            📂

                        </div>

                    </div>

                </div>

            </div>

            <!-- Aktivitas -->
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

                <div class="bg-green-800 text-white px-8 py-5">

                    <h3 class="text-2xl font-bold">
                        5 Aktivitas Terbaru
                    </h3>

                    <p class="text-green-100 text-sm">
                        Aktivitas terbaru dari seluruh penyuluh
                    </p>

                </div>

                @if($latestActivities->isEmpty())

                    <div class="py-20 text-center">

                        <div class="text-7xl mb-5">
                            📋
                        </div>

                        <h3 class="text-xl font-semibold text-gray-700">
                            Belum ada aktivitas
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Aktivitas penyuluh akan muncul di sini.
                        </p>

                    </div>

                @else

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-green-100">

                                <tr>

                                    <th class="px-6 py-4 text-left">
                                        Penyuluh
                                    </th>

                                    <th class="px-6 py-4 text-left">
                                        Judul
                                    </th>

                                    <th class="px-6 py-4 text-left">
                                        Kategori
                                    </th>

                                    <th class="px-6 py-4 text-left">
                                        Tanggal
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($latestActivities as $activity)

                                <tr class="hover:bg-green-50 transition duration-200">

                                    <td class="px-6 py-4 border-b font-medium">

                                        {{ $activity->user->name }}

                                    </td>

                                    <td class="px-6 py-4 border-b">

                                        {{ $activity->title }}

                                    </td>

                                    <td class="px-6 py-4 border-b">

                                        <span class="bg-green-700 text-white text-sm px-3 py-1 rounded-full">

                                            {{ $activity->category->name }}

                                        </span>

                                    </td>

                                    <td class="px-6 py-4 border-b">

                                        {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}

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