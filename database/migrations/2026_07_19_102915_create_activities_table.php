<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users dan categories
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            
            // Data Utama Kegiatan
            $table->string('title');
            $table->date('activity_date');
            
            // Lokasi & Wilayah
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable(); // Sistem Koordinat Maps
            $table->decimal('longitude', 11, 8)->nullable(); // Sistem Koordinat Maps
            $table->string('village');
            $table->string('district');
            $table->string('regency');
            
            // Detail Penyuluhan
            $table->string('farmer_group_name');
            $table->integer('participant_count');
            
            // Isi Laporan (Text panjang)
            $table->text('material');     // Materi
            $table->text('objective');    // Tujuan
            $table->text('result');       // Hasil
            $table->text('obstacle')->nullable();    // Kendala
            $table->text('follow_up')->nullable();   // Tindak lanjut
            $table->text('notes')->nullable();       // Catatan tambahan
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
