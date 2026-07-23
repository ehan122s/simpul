<x-app-layout>
    <div class="min-h-screen bg-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-6">
            <aside class="bg-white shadow-sm border border-gray-200 rounded-2xl p-6 hidden md:block">
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-900">Admin Panel</h2>
                    <p class="text-sm text-gray-500 mt-1">Kelola sistem SIMPUL dari sini.</p>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">Kelola User</a>
                    <a href="{{ route('admin.categories.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">Kelola Kategori</a>
                    <a href="{{ route('admin.monitor.index') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.monitor.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">Monitor Kegiatan</a>
                    <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">Profil</a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full rounded-2xl px-4 py-3 text-left text-sm font-medium text-red-600 hover:bg-red-50">Logout</button>
                    </form>
                </nav>
            </aside>

            <main class="py-8 px-4 sm:px-6 lg:px-8">
                <div class="space-y-6">
                    <div>
                        <h1 class="text-3xl font-semibold text-gray-900">Dashboard Monitoring</h1>
                        <p class="text-sm text-gray-500 mt-2">Ringkasan aktivitas dan statistik utama sistem SIMPUL.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-200">
                            <p class="text-sm text-gray-500">Total User</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-200">
                            <p class="text-sm text-gray-500">Total Penyuluh</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $totalPenyuluh }}</p>
                        </div>
                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-200">
                            <p class="text-sm text-gray-500">Total Materi</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $totalMateri }}</p>
                        </div>
                        <div class="rounded-3xl bg-white p-5 shadow-sm border border-gray-200">
                            <p class="text-sm text-gray-500">Total Kategori</p>
                            <p class="mt-3 text-3xl font-semibold text-gray-900">{{ $totalCategories }}</p>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Quick Action</h2>
                                <p class="text-sm text-gray-500">Akses cepat ke manajemen utama.</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('admin.users.create') }}" class="rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Tambah User</a>
                                <a href="{{ route('admin.categories.create') }}" class="rounded-2xl bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">Tambah Kategori</a>
                                <a href="{{ route('admin.monitor.index') }}" class="rounded-2xl bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-900">Monitor Kegiatan</a>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 xl:grid-cols-2">
                        <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200 h-[420px]">
                            <div class="mb-4 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Laporan per Bulan</h3>
                            </div>
                            <div class="h-[320px]"><canvas id="laporanBulananChart"></canvas></div>
                        </div>
                        <div class="rounded-3xl bg-white p-6 shadow-sm border border-gray-200 grid gap-6">
                            <div class="h-[200px] rounded-3xl bg-white p-4 border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan Berdasarkan Kategori</h3>
                                <div class="h-[150px]"><canvas id="kategoriLaporanChart"></canvas></div>
                            </div>
                            <div class="h-[200px] rounded-3xl bg-white p-4 border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Penyuluh Paling Aktif</h3>
                                <div class="h-[150px]"><canvas id="penyuluhAktifChart"></canvas></div>
                            </div>
                            <div class="h-[200px] rounded-3xl bg-white p-4 border border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Materi per Bulan</h3>
                                <div class="h-[150px]"><canvas id="uploadMateriChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white shadow-sm border border-gray-200 overflow-hidden">
                        <div class="flex flex-col gap-3 border-b border-gray-200 bg-gray-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Kegiatan Terbaru</h3>
                                <p class="text-sm text-gray-500">Daftar 5 aktivitas terakhir dari penyuluh.</p>
                            </div>
                            <a href="{{ route('admin.monitor.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left divide-y divide-gray-200">
                                <thead class="bg-white text-gray-600 text-sm uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4">Penyuluh</th>
                                        <th class="px-6 py-4">Judul Kegiatan</th>
                                        <th class="px-6 py-4">Kategori</th>
                                        <th class="px-6 py-4">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @forelse($latestActivities as $activity)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $activity->user->name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $activity->title }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $activity->category->name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-700">{{ $activity->activity_date?->format('d M Y') ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">Tidak ada kegiatan terbaru.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const laporanCtx = document.getElementById('laporanBulananChart');
            if (laporanCtx) {
                new Chart(laporanCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['bulan']) !!},
                        datasets: [{
                            label: 'Jumlah Laporan',
                            data: {!! json_encode($chartData['laporan_per_bulan']) !!},
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.15)',
                            fill: true,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }

            const kategoriCtx = document.getElementById('kategoriLaporanChart');
            if (kategoriCtx) {
                new Chart(kategoriCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($chartData['kategori_labels']) !!},
                        datasets: [{
                            data: {!! json_encode($chartData['kategori_values']) !!},
                            backgroundColor: ['#3b82f6', '#6366f1', '#10b981', '#f59e0b', '#8b5cf6']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }

            const penyuluhCtx = document.getElementById('penyuluhAktifChart');
            if (penyuluhCtx) {
                new Chart(penyuluhCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($chartData['penyuluh_labels']) !!},
                        datasets: [{
                            label: 'Aktivitas Penyuluh',
                            data: {!! json_encode($chartData['penyuluh_values']) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.75)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }

            const materiCtx = document.getElementById('uploadMateriChart');
            if (materiCtx) {
                new Chart(materiCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chartData['bulan']) !!},
                        datasets: [{
                            label: 'Upload Materi',
                            data: {!! json_encode($chartData['laporan_per_bulan']) !!},
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            fill: true,
                            tension: 0.35
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
