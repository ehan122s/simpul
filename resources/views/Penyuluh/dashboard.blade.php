<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>SIMPUL - Sistem Manajemen Penyuluhan Lapangan</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind Config untuk Warna Kustom -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#166534', // Hijau Tua
                        secondary: '#22C55E', // Hijau
                        background: '#F8FAF7', // BG
                        surface: '#FFFFFF', // Card
                        accent: '#DCFCE7', // Accent
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Menghilangkan scrollbar pada input type number jika ada */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        body { background-color: #F8FAF7; }
        
        /* Custom scrollbar untuk tabel horizontal */
        .hide-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .hide-scroll::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }
    </style>

    <script>
        // Logika Aplikasi menggunakan Alpine.js
        function appData() {
            return {
                role: 'penyuluh',
                currentView: 'dashboard', // dashboard, materi, form, riwayat
                mobileMenuOpen: false,
                userName: 'Budi Santoso',
                
                // Form State
                step: 1,
                gpsStatus: '',
                photos: [],
                formData: {
                    tanggal: new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }),
                    kategori: '',
                    lokasi: '',
                    judul: '',
                    deskripsi: ''
                },

                // Data Dummy Riwayat Laporan
                riwayatLaporan: [
                    { id: 1, tanggal: '23 Jul 2026', judul: 'Pendampingan KTH Mekar Sari', kategori: 'KTH', status: 'Selesai' },
                    { id: 2, tanggal: '20 Jul 2026', judul: 'Sosialisasi Pencegahan Karhutla', kategori: 'Perlindungan Hutan', status: 'Selesai' },
                    { id: 3, tanggal: '15 Jul 2026', judul: 'Monitoring Bibit Mangrove', kategori: 'RHL', status: 'Draft' },
                    { id: 4, tanggal: '10 Jul 2026', judul: 'Penyuluhan Manfaat Hutan Desa', kategori: 'Pemberdayaan Masyarakat', status: 'Selesai' },
                ],

                // Data Dummy Materi
                materiList: [
                    { id: 1, judul: 'Modul Pembibitan Kopi', format: 'PDF', kategori: 'Pemberdayaan Masyarakat', size: '2.4 MB' },
                    { id: 2, judul: 'Formulir Evaluasi KTH', format: 'Word', kategori: 'KTH', size: '1.1 MB' },
                    { id: 3, judul: 'Panduan Mencegah Karhutla', format: 'PDF', kategori: 'Perlindungan Hutan', size: '4.5 MB' },
                    { id: 4, judul: 'Video Cara Cangkok Tanaman', format: 'Video', kategori: 'RHL', size: '15.2 MB' },
                    { id: 5, judul: 'Data Luas Hutan Konservasi', format: 'Excel', kategori: 'Konservasi Hutan', size: '3.0 MB' },
                ],

                logout() {
                    if (confirm('Apakah Anda yakin ingin keluar dari aplikasi SIMPUL?')) {
                        // Di Laravel, ini akan melakukan submit form POST ke route('logout')
                        alert('Anda telah berhasil logout.');
                    }
                },

                navigate(view) {
                    this.currentView = view;
                    this.mobileMenuOpen = false;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                // --- FUNGSI WIZARD FORM ---
                getGPS() {
                    this.gpsStatus = 'Mencari lokasi...';
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.formData.lokasi = `${position.coords.latitude}, ${position.coords.longitude}`;
                                this.gpsStatus = 'Lokasi berhasil didapatkan!';
                                setTimeout(() => this.gpsStatus = '', 3000);
                            },
                            (error) => {
                                this.gpsStatus = 'Gagal mendapatkan lokasi. Pastikan GPS aktif.';
                            },
                            { enableHighAccuracy: true }
                        );
                    } else {
                        this.gpsStatus = 'Browser tidak mendukung GPS.';
                    }
                },

                validateStep() {
                    if(this.step === 1 && (!this.formData.kategori || !this.formData.lokasi)) {
                        alert("Mohon isi Kategori dan Lokasi (Ambil GPS).");
                        return;
                    }
                    if(this.step === 2 && (!this.formData.judul || !this.formData.deskripsi)) {
                        alert("Mohon isi Judul dan Deskripsi kegiatan.");
                        return;
                    }
                    if(this.step === 3 && this.photos.length === 0) {
                        const confirmLanjut = confirm("Anda belum menambahkan foto. Lanjut tanpa foto?");
                        if(!confirmLanjut) return;
                    }
                    this.step++;
                },

                handleFileUpload(event) {
                    const files = event.target.files;
                    if (files) {
                        for (let i = 0; i < files.length; i++) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.photos.push(e.target.result);
                            };
                            reader.readAsDataURL(files[i]);
                        }
                    }
                },

                removePhoto(index) {
                    this.photos.splice(index, 1);
                },

                submitForm() {
                    alert("Laporan berhasil dikirim ke server!");
                    this.step = 1;
                    this.photos = [];
                    this.formData = {
                        tanggal: new Date().toLocaleString('id-ID', { dateStyle: 'full', timeStyle: 'short' }),
                        kategori: '',
                        lokasi: '',
                        judul: '',
                        deskripsi: ''
                    };
                    this.navigate('riwayat');
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body x-data="appData()" class="text-gray-800 antialiased font-sans flex h-screen overflow-hidden bg-green-50/50">

    <!-- ============================== -->
    <!-- DESKTOP SIDEBAR -->
    <!-- ============================== -->
    <aside class="hidden md:flex flex-col w-72 bg-white h-full shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-20 transition-all border-r border-gray-100">
        <div class="p-6 flex items-center justify-center border-b border-gray-100">
            <div class="bg-primary p-2 rounded-xl mr-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <h1 class="text-2xl font-bold text-primary tracking-wider">SIMPUL</h1>
        </div>
        <nav class="flex-1 overflow-y-auto py-6">
            <template x-if="role === 'penyuluh'">
                <ul class="space-y-3 px-4">
                    <li><button @click="navigate('dashboard')" :class="currentView === 'dashboard' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg> Dashboard</button></li>
                    
                    <li><button @click="navigate('form')" :class="currentView === 'form' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Buat Laporan</button></li>
                    
                    <li><button @click="navigate('riwayat')" :class="currentView === 'riwayat' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Laporan Saya</button></li>
                    
                    <li><button @click="navigate('materi')" :class="currentView === 'materi' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> Materi Edukasi</button></li>
                    
                    <li><button @click="navigate('profil')" :class="currentView === 'profil' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Profil</button></li>
                </ul>
            </template>
            
            <template x-if="role === 'admin'">
                <ul class="space-y-3 px-4">
                    <li><button @click="navigate('dashboard')" :class="currentView === 'dashboard' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg> Dashboard</button></li>
                    
                    <li><button @click="navigate('data-penyuluh')" :class="currentView === 'data-penyuluh' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Data Penyuluh</button></li>
                    
                    <li><button @click="navigate('kelola-materi')" :class="currentView === 'kelola-materi' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg> Kelola Materi</button></li>
                    
                    <li><button @click="navigate('kelola-kategori')" :class="currentView === 'kelola-kategori' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Kelola Kategori</button></li>
                    
                    <li><button @click="navigate('semua-laporan')" :class="currentView === 'semua-laporan' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg> Semua Laporan</button></li>
                    
                    <li><button @click="navigate('statistik')" :class="currentView === 'statistik' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Statistik</button></li>
                    
                    <li><button @click="navigate('manajemen-user')" :class="currentView === 'manajemen-user' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Manajemen User</button></li>
                    
                    <li><button @click="navigate('profil')" :class="currentView === 'profil' ? 'bg-green-50 text-primary border-r-4 border-primary' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 border-r-4 border-transparent'" class="w-full text-left px-5 py-3.5 rounded-l-xl font-semibold transition flex items-center"><svg class="w-5 h-5 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Profil</button></li>
                </ul>
            </template>
        </nav>
        <div class="p-5 border-t border-gray-100">
            <button @click="logout()" class="w-full bg-red-50 hover:bg-red-100 text-red-600 py-3 px-4 rounded-xl font-bold text-sm transition border border-red-100 flex justify-center items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </button>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        
        <!-- ============================== -->
        <!-- MOBILE NAVBAR -->
        <!-- ============================== -->
        <header class="md:hidden bg-primary text-white p-4 shadow-md flex justify-between items-center z-30 relative">
            <div class="flex items-center">
                <div class="bg-white/20 p-1.5 rounded-lg mr-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>
                <h1 class="text-xl font-bold tracking-wider">SIMPUL</h1>
            </div>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 focus:outline-none focus:bg-green-800 rounded-lg transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" style="display: none;" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </header>

        <!-- MOBILE MENU OVERLAY -->
        <div x-show="mobileMenuOpen" x-transition.opacity class="md:hidden absolute inset-0 bg-black bg-opacity-50 z-20" style="display: none;"></div>
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform -translate-y-full"
             x-transition:enter-end="transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="transform translate-y-0"
             x-transition:leave-end="transform -translate-y-full"
             class="md:hidden absolute top-[72px] left-0 w-full bg-white shadow-xl z-20 border-b border-gray-100 rounded-b-2xl overflow-y-auto max-h-[calc(100vh-72px)]" 
             style="display: none;">
            <ul class="flex flex-col py-2 px-4 space-y-2 pb-6">
                <template x-if="role === 'penyuluh'">
                    <div>
                        <li><button @click="navigate('dashboard')" :class="currentView === 'dashboard' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg> Dashboard</button></li>
                        <li><button @click="navigate('form')" :class="currentView === 'form' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Buat Laporan</button></li>
                        <li><button @click="navigate('riwayat')" :class="currentView === 'riwayat' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Laporan Saya</button></li>
                        <li><button @click="navigate('materi')" :class="currentView === 'materi' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg> Materi Edukasi</button></li>
                        <li><button @click="navigate('profil')" :class="currentView === 'profil' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Profil</button></li>
                    </div>
                </template>
                
                <template x-if="role === 'admin'">
                    <div>
                        <li><button @click="navigate('dashboard')" :class="currentView === 'dashboard' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg> Dashboard</button></li>
                        <li><button @click="navigate('data-penyuluh')" :class="currentView === 'data-penyuluh' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg> Data Penyuluh</button></li>
                        <li><button @click="navigate('kelola-materi')" :class="currentView === 'kelola-materi' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg> Kelola Materi</button></li>
                        <li><button @click="navigate('kelola-kategori')" :class="currentView === 'kelola-kategori' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> Kelola Kategori</button></li>
                        <li><button @click="navigate('semua-laporan')" :class="currentView === 'semua-laporan' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg> Semua Laporan</button></li>
                        <li><button @click="navigate('statistik')" :class="currentView === 'statistik' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> Statistik</button></li>
                        <li><button @click="navigate('manajemen-user')" :class="currentView === 'manajemen-user' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg> Manajemen User</button></li>
                        <li><button @click="navigate('profil')" :class="currentView === 'profil' ? 'bg-green-50 text-primary' : 'text-gray-600'" class="w-full text-left px-5 py-4 text-lg font-semibold rounded-xl flex items-center transition"><svg class="w-6 h-6 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> Profil</button></li>
                    </div>
                </template>
                
                <li class="pt-4 mt-2 border-t border-gray-100">
                    <button @click="logout()" class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-5 py-4 text-lg font-bold rounded-xl flex items-center justify-center transition border border-red-100">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg> Logout
                    </button>
                </li>
            </ul>
        </div>

        <!-- TOP BAR DESKTOP -->
        <header class="hidden md:flex bg-white shadow-[0_4px_24px_rgba(0,0,0,0.02)] p-5 justify-between items-center z-10 border-b border-gray-100">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold capitalize" 
                    :class="role === 'admin' && currentView === 'dashboard' ? 'text-green-900' : 'text-gray-800'"
                    x-text="currentView === 'dashboard' ? (role === 'admin' ? 'Dashboard Admin' : 'Dashboard Penyuluh') : 
                            currentView === 'form' ? 'Buat Laporan Baru' : 
                            currentView === 'riwayat' ? 'Riwayat Laporan' : 
                            'Materi Edukasi'">
                </h2>
                <p x-show="currentView === 'dashboard' && role === 'penyuluh'" class="text-gray-500 text-sm mt-1">Kelola dan pantau seluruh kegiatan penyuluhan Anda.</p>
                <!-- Meniru desain header Admin dari Blade Anda -->
                <p x-show="currentView === 'dashboard' && role === 'admin'" class="text-green-700 mt-1">Selamat datang, <span class="font-semibold" x-text="userName"></span> 👋</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Badge Administrator (Tampil hanya untuk Admin) -->
                <div x-show="role === 'admin'" class="hidden md:block">
                    <span class="bg-green-700 text-white px-5 py-2 rounded-full shadow font-medium">
                        Administrator
                    </span>
                </div>
                
                <div class="flex items-center space-x-4 bg-gray-50 py-2 px-4 rounded-full border border-gray-200">
                    <span class="font-semibold text-gray-700" x-text="userName"></span>
                    <img :src="'https://ui-avatars.com/api/?name='+userName+'&background=166534&color=fff'" alt="Profile" class="w-9 h-9 rounded-full shadow-sm">
                </div>
            </div>
        </header>

        <!-- AREA KONTEN BERGULIR -->
        <div class="flex-1 overflow-y-auto p-4 md:p-8 relative w-full max-w-7xl mx-auto">
            
            <!-- ============================== -->
            <!-- VIEW: DASHBOARD PENYULUH -->
            <!-- ============================== -->
            <div x-show="currentView === 'dashboard' && role === 'penyuluh'" x-transition.opacity.duration.300ms style="display: none;">
                
                <!-- Welcome Banner (Adaptasi dari desain Anda) -->
                <div class="bg-gradient-to-r from-green-800 to-green-600 rounded-3xl shadow-lg text-white p-6 md:p-10 mb-8 relative overflow-hidden">
                    <!-- Hiasan Background -->
                    <svg class="absolute right-0 top-0 opacity-10 h-full transform translate-x-1/4" viewBox="0 0 100 100" fill="currentColor">
                        <path d="M50 0L100 50L50 100L0 50L50 0Z" />
                    </svg>

                    <div class="flex flex-col md:flex-row justify-between items-center relative z-10">
                        <div class="text-center md:text-left mb-6 md:mb-0">
                            <h1 class="text-3xl md:text-4xl font-bold mb-3">Halo, <span x-text="userName"></span> 👋</h1>
                            <p class="text-green-100 text-lg max-w-lg leading-relaxed">
                                Selamat bertugas hari ini! Kelola laporan lapangan, cek materi penyuluhan terbaru, dan pantau riwayat kegiatan dengan mudah.
                            </p>
                        </div>
                        <div class="w-full md:w-auto">
                            <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-8 py-5 text-center border border-white/20 shadow-inner">
                                <p class="text-sm text-green-100 font-medium mb-1">Total Laporan Selesai</p>
                                <h2 class="text-5xl font-extrabold" x-text="riwayatLaporan.filter(r => r.status === 'Selesai').length"></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grid Menu Besar / Shortcut -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
                    <!-- Shortcut 1: Isi Laporan -->
                    <button @click="navigate('form')" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-green-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-primary group-hover:text-white transition-colors">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-primary group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-gray-800">Isi Laporan</span>
                        <span class="text-sm text-gray-500 mt-1 hidden md:block">Laporan Kegiatan Baru</span>
                    </button>

                    <!-- Shortcut 2: Laporan Saya -->
                    <button @click="navigate('riwayat')" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-blue-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-colors">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-gray-800">Laporan Saya</span>
                        <span class="text-sm text-gray-500 mt-1 hidden md:block">Riwayat Kegiatan</span>
                    </button>

                    <!-- Shortcut 3: Materi -->
                    <button @click="navigate('materi')" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-orange-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-orange-500 transition-colors">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-orange-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-gray-800">Materi</span>
                        <span class="text-sm text-gray-500 mt-1 hidden md:block">Buku & Modul</span>
                    </button>
                    
                    <!-- Shortcut 4: Profil -->
                    <button class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center hover:-translate-y-1 hover:shadow-md transition-all group">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-purple-50 rounded-full flex items-center justify-center mb-4 group-hover:bg-purple-600 transition-colors">
                            <svg class="w-8 h-8 md:w-10 md:h-10 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <span class="font-bold text-lg text-gray-800">Profil</span>
                        <span class="text-sm text-gray-500 mt-1 hidden md:block">Pengaturan Akun</span>
                    </button>
                </div>

                <!-- 5 Kegiatan Terakhir -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                        <h3 class="text-xl font-bold text-gray-800">5 Kegiatan Terakhir Anda</h3>
                        <button @click="navigate('riwayat')" class="text-secondary font-semibold hover:text-green-700 transition">Lihat Semua &rarr;</button>
                    </div>
                    
                    <!-- Tampilan Desktop (Tabel) -->
                    <div class="hidden md:block overflow-x-auto hide-scroll">
                        <table class="min-w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-sm">
                                    <th class="px-6 py-4 border-b font-semibold">Tanggal</th>
                                    <th class="px-6 py-4 border-b font-semibold">Judul Kegiatan</th>
                                    <th class="px-6 py-4 border-b font-semibold">Kategori</th>
                                    <th class="px-6 py-4 border-b font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="item in riwayatLaporan.slice(0, 3)" :key="item.id">
                                    <tr class="hover:bg-green-50/50 transition border-b border-gray-50">
                                        <td class="px-6 py-4 text-gray-600 font-medium" x-text="item.tanggal"></td>
                                        <td class="px-6 py-4 text-gray-800 font-bold" x-text="item.judul"></td>
                                        <td class="px-6 py-4">
                                            <span class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded-full text-xs font-bold" x-text="item.kategori"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span :class="item.status === 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="px-3 py-1.5 rounded-full text-xs font-bold" x-text="item.status"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Tampilan Mobile (Card List yang jauh lebih bersahabat di HP) -->
                    <div class="md:hidden divide-y divide-gray-100">
                        <template x-for="item in riwayatLaporan.slice(0, 3)" :key="item.id">
                            <div class="p-5 active:bg-gray-50 transition">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-semibold text-gray-500" x-text="item.tanggal"></span>
                                    <span :class="item.status === 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide" x-text="item.status"></span>
                                </div>
                                <h4 class="font-bold text-lg text-gray-800 mb-2 leading-tight" x-text="item.judul"></h4>
                                <span class="inline-block bg-gray-100 text-gray-600 px-3 py-1 rounded-md text-xs font-medium" x-text="item.kategori"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- ============================== -->
            <!-- VIEW: DASHBOARD ADMIN -->
            <!-- ============================== -->
            <div x-show="currentView === 'dashboard' && role === 'admin'" x-transition.opacity.duration.300ms style="display: none;">
                
                <!-- Banner Admin (Persis seperti desain Blade Anda) -->
                <div class="bg-gradient-to-r from-green-900 via-green-700 to-green-600 rounded-3xl shadow-xl p-8 text-white mb-8">
                    <div class="flex flex-col lg:flex-row justify-between items-center">
                        <div>
                            <h1 class="text-4xl font-bold">
                                Halo, <span x-text="userName"></span> 👋
                            </h1>
                            <p class="mt-3 text-green-100 text-lg max-w-2xl">
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
                                    1,893
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik (Persis seperti desain Blade Anda) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Penyuluh -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-[8px] border-green-700">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">
                                    Total Penyuluh
                                </p>
                                <h2 class="text-4xl font-bold text-green-800 mt-2">
                                    142
                                </h2>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full text-2xl">
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
                                    1,893
                                </h2>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full text-2xl">
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
                                    12
                                </h2>
                            </div>
                            <div class="bg-green-100 p-4 rounded-full text-2xl">
                                📂
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aktivitas (Persis seperti desain Blade Anda) -->
                <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-6">
                    <div class="bg-green-800 text-white px-8 py-5">
                        <h3 class="text-2xl font-bold">
                            5 Aktivitas Terbaru
                        </h3>
                        <p class="text-green-100 text-sm mt-1">
                            Aktivitas terbaru dari seluruh penyuluh
                        </p>
                    </div>
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto hide-scroll">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-green-100">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-800">Penyuluh</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-800">Judul Kegiatan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-800">Kategori</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-800">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-green-50 transition duration-200 border-b border-gray-100">
                                    <td class="px-6 py-4 font-medium text-gray-800">Agus Setiawan</td>
                                    <td class="px-6 py-4 text-gray-700">Penanaman Pohon Jati</td>
                                    <td class="px-6 py-4">
                                        <!-- Menggunakan badge bg-green-700 persis seperti yang Anda buat -->
                                        <span class="bg-green-700 text-white text-sm px-3 py-1 rounded-full">RHL</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">23 Jul 2026</td>
                                </tr>
                                <tr class="hover:bg-green-50 transition duration-200 border-b border-gray-100">
                                    <td class="px-6 py-4 font-medium text-gray-800">Siti Aminah</td>
                                    <td class="px-6 py-4 text-gray-700">Sosialisasi Kebakaran Hutan</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-green-700 text-white text-sm px-3 py-1 rounded-full">Perlindungan Hutan</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">22 Jul 2026</td>
                                </tr>
                                <tr class="hover:bg-green-50 transition duration-200 border-b border-gray-100">
                                    <td class="px-6 py-4 font-medium text-gray-800">Budi Santoso</td>
                                    <td class="px-6 py-4 text-gray-700">Pendampingan KTH Mekar Sari</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-green-700 text-white text-sm px-3 py-1 rounded-full">KTH</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">21 Jul 2026</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (Dipertahankan agar desain Blade Anda tidak rusak saat dibuka di HP) -->
                    <div class="md:hidden divide-y divide-gray-100 p-2">
                        <!-- Item 1 -->
                        <div class="p-4 bg-white rounded-2xl mb-3 border border-gray-100">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-semibold text-gray-500">23 Jul 2026</span>
                                <span class="bg-green-700 text-white px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">RHL</span>
                            </div>
                            <h4 class="font-bold text-lg text-gray-800 mb-1 leading-tight">Penanaman Pohon Jati</h4>
                            <p class="text-sm text-gray-600 font-medium">Oleh: Agus Setiawan</p>
                        </div>
                        
                        <!-- Item 2 -->
                        <div class="p-4 bg-white rounded-2xl mb-3 border border-gray-100">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-semibold text-gray-500">22 Jul 2026</span>
                                <span class="bg-green-700 text-white px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide">Perlindungan Hutan</span>
                            </div>
                            <h4 class="font-bold text-lg text-gray-800 mb-1 leading-tight">Sosialisasi Kebakaran Hutan</h4>
                            <p class="text-sm text-gray-600 font-medium">Oleh: Siti Aminah</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================== -->
            <!-- VIEW: RIWAYAT LAPORAN SAYA -->
            <!-- ============================== -->
            <div x-show="currentView === 'riwayat'" x-transition.opacity.duration.300ms style="display: none;">
                <div class="md:hidden mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Laporan Saya</h2>
                    <p class="text-gray-500">Semua riwayat kegiatan Anda.</p>
                </div>

                <!-- Filter & Search Bar (Mobile First Design) -->
                <div class="bg-white p-4 md:p-6 rounded-3xl shadow-sm border border-gray-100 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" placeholder="Cari judul laporan..." class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary focus:bg-white transition font-medium text-gray-700">
                        </div>
                        <select class="w-full md:w-64 px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary font-medium text-gray-700 appearance-none">
                            <option value="">Semua Kategori</option>
                            <option value="rhl">RHL</option>
                            <option value="kth">KTH</option>
                            <option value="perlindungan">Perlindungan Hutan</option>
                        </select>
                    </div>
                </div>

                <!-- List Laporan (Card View for Mobile, Table for Desktop) -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    <!-- Desktop Table -->
                    <div class="hidden md:block overflow-x-auto hide-scroll">
                        <table class="min-w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-green-50 text-green-900 text-sm">
                                    <th class="px-6 py-5 border-b font-bold">Tanggal</th>
                                    <th class="px-6 py-5 border-b font-bold">Judul Laporan</th>
                                    <th class="px-6 py-5 border-b font-bold">Kategori</th>
                                    <th class="px-6 py-5 border-b font-bold">Status</th>
                                    <th class="px-6 py-5 border-b font-bold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="item in riwayatLaporan" :key="item.id">
                                    <tr class="hover:bg-gray-50 transition border-b border-gray-100">
                                        <td class="px-6 py-5 text-gray-600 font-medium" x-text="item.tanggal"></td>
                                        <td class="px-6 py-5 text-gray-800 font-bold text-lg" x-text="item.judul"></td>
                                        <td class="px-6 py-5">
                                            <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold" x-text="item.kategori"></span>
                                        </td>
                                        <td class="px-6 py-5">
                                            <span :class="item.status === 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'" class="px-4 py-2 rounded-xl text-sm font-bold" x-text="item.status"></span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <button class="bg-white border border-gray-200 text-gray-700 hover:text-primary hover:border-primary p-2 rounded-xl shadow-sm transition mr-2" title="Lihat PDF">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                            <button class="bg-white border border-gray-200 text-gray-700 hover:text-blue-600 hover:border-blue-600 p-2 rounded-xl shadow-sm transition" title="Download Word">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Cards (Laporan Saya) - Sangat Nyaman di HP -->
                    <div class="md:hidden divide-y divide-gray-100 p-2">
                        <template x-for="item in riwayatLaporan" :key="item.id">
                            <div class="p-4 bg-white rounded-2xl mb-3 shadow-[0_2px_10px_rgba(0,0,0,0.03)] border border-gray-100">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-semibold text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span x-text="item.tanggal"></span>
                                    </span>
                                    <span :class="item.status === 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide" x-text="item.status"></span>
                                </div>
                                <h4 class="font-bold text-xl text-gray-800 mb-3 leading-tight" x-text="item.judul"></h4>
                                <div class="mb-4">
                                    <span class="inline-block bg-gray-50 border border-gray-200 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold" x-text="item.kategori"></span>
                                </div>
                                
                                <div class="flex space-x-3 pt-3 border-t border-gray-100">
                                    <button class="flex-1 bg-red-50 text-red-600 font-bold py-2.5 rounded-xl text-sm border border-red-100 flex justify-center items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> PDF
                                    </button>
                                    <button class="flex-1 bg-blue-50 text-blue-600 font-bold py-2.5 rounded-xl text-sm border border-blue-100 flex justify-center items-center">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg> Word
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>
            </div>

            <!-- ============================== -->
            <!-- VIEW: MATERI PENYULUHAN -->
            <!-- ============================== -->
            <div x-show="currentView === 'materi'" x-transition.opacity.duration.300ms style="display: none;">
                <div class="md:hidden mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Materi Edukasi</h2>
                    <p class="text-gray-500">Kumpulan modul dan panduan.</p>
                </div>

                <!-- Filter & Search -->
                <div class="bg-white p-4 md:p-6 rounded-3xl shadow-sm border border-gray-100 mb-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <input type="text" placeholder="Cari nama modul / buku..." class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary focus:bg-white transition font-medium text-gray-700">
                        </div>
                        <select class="w-full md:w-64 px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary font-medium text-gray-700 appearance-none">
                            <option value="">Semua Kategori</option>
                            <option value="rhl">RHL</option>
                            <option value="kth">KTH</option>
                        </select>
                    </div>
                </div>

                <!-- Grid Materi (Responsive Card Layout) -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
                    <template x-for="item in materiList" :key="item.id">
                        <div class="bg-white p-5 rounded-3xl shadow-sm border border-gray-100 flex items-start gap-4 hover:shadow-md transition">
                            
                            <!-- Ikon berdasarkan Format Dokumen -->
                            <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex items-center justify-center text-white" 
                                 :class="item.format === 'PDF' ? 'bg-red-500' : (item.format === 'Word' ? 'bg-blue-600' : (item.format === 'Excel' ? 'bg-green-600' : 'bg-purple-500'))">
                                <span class="font-bold text-lg" x-text="item.format"></span>
                            </div>

                            <div class="flex-1">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 block" x-text="item.kategori"></span>
                                <h3 class="text-lg font-bold text-gray-800 leading-snug mb-1" x-text="item.judul"></h3>
                                <p class="text-sm text-gray-500 font-medium mb-3">Ukuran: <span x-text="item.size"></span></p>
                                
                                <button class="w-full md:w-auto bg-green-50 text-green-700 hover:bg-primary hover:text-white font-bold py-2 px-5 rounded-xl transition text-sm flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh File
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ============================== -->
            <!-- VIEW: WIZARD FORM LAPORAN -->
            <!-- ============================== -->
            <div x-show="currentView === 'form'" x-transition.opacity.duration.300ms class="max-w-3xl mx-auto pb-20 md:pb-0" style="display: none;">
                
                <div class="md:hidden mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Buat Laporan</h2>
                    <p class="text-gray-500">Laporkan kegiatan lapangan Anda.</p>
                </div>

                <!-- Progress Bar Modern -->
                <div class="mb-10 relative">
                    <div class="absolute top-1/2 left-0 w-full h-1.5 bg-gray-200 rounded-full -z-10 transform -translate-y-1/2"></div>
                    <div class="absolute top-1/2 left-0 h-1.5 bg-primary rounded-full -z-10 transform -translate-y-1/2 transition-all duration-500" :style="`width: ${((step - 1) / 3) * 100}%`"></div>
                    
                    <div class="flex justify-between items-center">
                        <template x-for="i in 4">
                            <div class="flex flex-col items-center">
                                <div :class="step >= i ? 'bg-primary text-white border-primary shadow-lg scale-110' : 'bg-white text-gray-400 border-gray-200'" class="w-12 h-12 rounded-full border-[3px] flex items-center justify-center font-bold text-lg transition-all duration-300 z-10">
                                    <span x-text="i" x-show="step <= i"></span>
                                    <svg x-show="step > i" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="mt-3 text-xs font-bold md:text-sm text-center max-w-[70px]" 
                                     :class="step >= i ? 'text-gray-800' : 'text-gray-400'"
                                     x-text="['Data Dasar', 'Detail', 'Dokumentasi', 'Selesai'][i-1]"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Container Form -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-10">
                    
                    <!-- STEP 1: Data Dasar -->
                    <div x-show="step === 1" x-transition.opacity.duration.300ms>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-50 pb-4">Info Dasar Kegiatan</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Waktu Kegiatan</label>
                                <input type="text" x-model="formData.tanggal" readonly class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-gray-600 font-medium focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Kategori Kegiatan <span class="text-red-500">*</span></label>
                                <select x-model="formData.kategori" class="w-full border border-gray-300 rounded-2xl px-5 py-4 text-gray-800 focus:ring-4 focus:ring-green-100 focus:border-primary bg-white text-lg font-medium appearance-none">
                                    <option value="">-- Sentuh untuk Pilih Kategori --</option>
                                    <option value="RHL">Rehabilitasi Hutan dan Lahan (RHL)</option>
                                    <option value="Konservasi Hutan">Konservasi Hutan</option>
                                    <option value="Perlindungan Hutan">Perlindungan Hutan</option>
                                    <option value="Pemberdayaan Masyarakat">Pemberdayaan Masyarakat</option>
                                    <option value="Kelompok Tani Hutan (KTH)">Kelompok Tani Hutan (KTH)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Lokasi Koordinat GPS <span class="text-red-500">*</span></label>
                                <div class="flex flex-col md:flex-row gap-3">
                                    <input type="text" x-model="formData.lokasi" placeholder="Belum ada lokasi..." readonly class="flex-1 border border-gray-300 bg-gray-50 rounded-2xl px-5 py-4 text-gray-800 text-lg font-medium focus:outline-none">
                                    <button type="button" @click="getGPS()" class="bg-blue-600 text-white font-bold px-6 py-4 rounded-2xl shadow-md hover:bg-blue-700 transition flex items-center justify-center active:scale-95">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Ambil Lokasi
                                    </button>
                                </div>
                                <p x-show="gpsStatus" x-text="gpsStatus" class="text-sm mt-3 font-bold" :class="gpsStatus.includes('Gagal') ? 'text-red-500' : 'text-primary'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Detail Kegiatan -->
                    <div x-show="step === 2" style="display: none;" x-transition.opacity.duration.300ms>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-50 pb-4">Penjelasan Kegiatan</h3>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Judul Laporan <span class="text-red-500">*</span></label>
                                <input type="text" x-model="formData.judul" placeholder="Contoh: Pendampingan Tanam Kopi..." class="w-full border border-gray-300 rounded-2xl px-5 py-4 text-gray-800 focus:ring-4 focus:ring-green-100 focus:border-primary text-lg font-medium">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Uraian / Deskripsi <span class="text-red-500">*</span></label>
                                <textarea x-model="formData.deskripsi" rows="6" placeholder="Tuliskan siapa saja yang hadir, masalah, dan hasil penyuluhan..." class="w-full border border-gray-300 rounded-2xl px-5 py-4 text-gray-800 focus:ring-4 focus:ring-green-100 focus:border-primary text-lg font-medium resize-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: Dokumentasi -->
                    <div x-show="step === 3" style="display: none;" x-transition.opacity.duration.300ms>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-50 pb-4">Upload Foto Kegiatan</h3>
                        
                        <div class="space-y-6">
                            <!-- Area Upload Kamera -->
                            <div class="border-2 border-dashed border-primary bg-green-50 rounded-3xl p-10 text-center relative hover:bg-green-100 transition cursor-pointer group" onclick="document.getElementById('fileInput').click()">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm group-hover:scale-110 transition-transform">
                                    <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <p class="text-xl font-bold text-primary mb-1">Ketuk di sini untuk Buka Kamera</p>
                                <p class="text-sm text-gray-600 font-medium">Anda dapat memilih lebih dari satu foto.</p>
                                <!-- File Input -->
                                <input type="file" id="fileInput" multiple accept="image/*" class="hidden" @change="handleFileUpload($event)">
                            </div>

                            <!-- Preview Grid -->
                            <div x-show="photos.length > 0" class="mt-8 bg-gray-50 p-6 rounded-3xl border border-gray-200">
                                <p class="font-bold text-gray-700 mb-4 text-lg">Foto Terpilih (<span x-text="photos.length"></span>):</p>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <template x-for="(photo, index) in photos" :key="index">
                                        <div class="relative group rounded-2xl overflow-hidden shadow-sm border border-gray-200 aspect-square">
                                            <img :src="photo" class="w-full h-full object-cover">
                                            <button @click="removePhoto(index)" class="absolute top-2 right-2 bg-red-600 text-white p-2.5 rounded-full shadow-lg hover:bg-red-700 transition transform scale-95 group-hover:scale-100 active:scale-90">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: Pratinjau Akhir -->
                    <div x-show="step === 4" style="display: none;" x-transition.opacity.duration.300ms>
                        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-gray-50 pb-4">Periksa Kembali Laporan</h3>
                        
                        <div class="bg-gray-50 rounded-3xl p-6 md:p-8 border border-gray-200 mb-8 space-y-5">
                            <div>
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Judul Kegiatan</p>
                                <p class="font-bold text-gray-900 text-2xl leading-tight" x-text="formData.judul || '-'"></p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-gray-200">
                                <div>
                                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Kategori</p>
                                    <p class="font-bold text-primary text-lg" x-text="formData.kategori || '-'"></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Waktu</p>
                                    <p class="font-bold text-gray-800 text-lg" x-text="formData.tanggal"></p>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Lokasi</p>
                                <p class="font-bold text-gray-800 text-lg" x-text="formData.lokasi || '-'"></p>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-2">Uraian Kegiatan</p>
                                <p class="text-gray-700 whitespace-pre-line text-lg font-medium leading-relaxed" x-text="formData.deskripsi || '-'"></p>
                            </div>
                            <div class="pt-4 border-t border-gray-200" x-show="photos.length > 0">
                                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-3">Dokumentasi (<span x-text="photos.length"></span>)</p>
                                <div class="flex gap-3 overflow-x-auto pb-2 hide-scroll">
                                    <template x-for="photo in photos">
                                        <img :src="photo" class="w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover border-2 border-white shadow-sm flex-shrink-0">
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Utama -->
                        <div class="space-y-4">
                            <button @click="submitForm()" class="w-full bg-primary hover:bg-green-800 text-white font-bold text-xl py-5 rounded-2xl shadow-lg transition transform hover:-translate-y-1 active:scale-95 flex justify-center items-center">
                                <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Kirim Laporan Sekarang
                            </button>
                        </div>
                    </div>

                    <!-- Navigasi Bawah Form -->
                    <div class="mt-10 flex justify-between pt-6 border-t border-gray-100">
                        <button x-show="step > 1" @click="step--" class="px-8 py-4 font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-2xl transition active:scale-95">
                            KEMBALI
                        </button>
                        <div x-show="step === 1"></div>
                        
                        <button x-show="step < 4" @click="validateStep()" class="px-10 py-4 font-bold text-white bg-primary hover:bg-green-800 rounded-2xl shadow-md transition ml-auto active:scale-95 flex items-center">
                            LANJUT <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </main>
</body>
</html>