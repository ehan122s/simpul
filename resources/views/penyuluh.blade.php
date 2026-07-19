<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPUL - Penyuluh')</title>
    <!-- Tailwind CSS (Di Laravel asli akan menggunakan Vite: @vite(['resources/css/app.css', 'resources/js/app.js'])) -->
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
    <style>
        /* Konfigurasi warna kustom Tailwind */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        simpul: { 600: '#388E3C', 700: '#2E7D32', 800: '#1B5E20' }
                    }
                }
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (Desktop) -->
        <aside class="w-64 bg-simpul-700 text-white hidden md:flex flex-col shadow-lg">
            <div class="p-6 flex items-center justify-center border-b border-simpul-600">
                <span class="text-2xl font-bold tracking-wider">SIMPUL</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('penyuluh.dashboard') }}" class="flex items-center px-4 py-3 bg-simpul-800 rounded-lg transition">
                    <!-- Icon Home (Heroicons) -->
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('penyuluh.activities.index') }}" class="flex items-center px-4 py-3 hover:bg-simpul-600 rounded-lg transition">
                    <!-- Icon Document -->
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Kegiatan Saya
                </a>
            </nav>
            <div class="p-4 border-t border-simpul-600">
                <!-- Tombol Logout Dummy -->
                <button class="w-full text-left px-4 py-2 hover:bg-simpul-600 rounded-lg flex items-center transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header/Navbar Mobile -->
            <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center md:justify-end">
                <div class="md:hidden text-simpul-700 font-bold text-xl">SIMPUL</div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-600">Halo, Penyuluh!</span>
                    <div class="w-10 h-10 rounded-full bg-simpul-600 text-white flex items-center justify-center font-bold">P</div>
                </div>
            </header>

            <!-- Pesan Sukses (Flash Message) -->
            @if(session('success'))
            <div class="m-6 mb-0 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
            @endif

            <!-- Content Area -->
            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>
