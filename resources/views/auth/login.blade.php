<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIMPUL</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex">
        
        <div class="hidden lg:flex lg:w-1/2 bg-green-600 justify-center items-center flex-col p-12 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white"></div>
                <div class="absolute bottom-10 -right-10 w-72 h-72 rounded-full bg-white"></div>
            </div>

            <div class="relative z-10 text-center">
                <h1 class="text-5xl font-extrabold text-white mb-4 tracking-tight">SIMPUL</h1>
                <p class="text-green-100 text-lg max-w-md mx-auto leading-relaxed">
                    Sistem Informasi Manajemen Penyuluh Pertanian. 
                    Kelola data, pantau kegiatan, dan tingkatkan potensi pertanian daerah.
                </p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-gray-50">
            <div class="w-full max-w-md">
                
                <div class="lg:hidden text-center mb-10">
                    <h1 class="text-4xl font-extrabold text-green-600">SIMPUL</h1>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-bold text-gray-900">Selamat Datang 👋</h2>
                    <p class="text-gray-500 mt-2 text-sm">Silakan masukkan email dan kata sandi Anda untuk masuk ke dashboard.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 bg-white">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:text-green-500 font-medium">Lupa sandi?</a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-3 bg-white">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">Ingat Saya</label>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                            Masuk ke Dashboard
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

</body>
</html>