<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@simpul.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Buat Akun Penyuluh
        User::create([
            'name' => 'Budi Penyuluh',
            'email' => 'budi@simpul.com',
            'password' => Hash::make('password123'),
            'role' => 'penyuluh',
        ]);

        // 3. Buat beberapa kategori dummy
        Category::create(['name' => 'Sosialisasi Kebakaran Hutan', 'description' => 'Penyuluhan pencegahan karhutla']);
        Category::create(['name' => 'Pembinaan Kelompok Tani', 'description' => 'Edukasi tanam pohon']);
        Category::create(['name' => 'Patroli Kawasan', 'description' => 'Pengecekan patok batas hutan']);
    }
}