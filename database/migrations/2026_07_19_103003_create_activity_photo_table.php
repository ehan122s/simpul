<?php

// File: database/migrations/xxxx_xx_xx_xxxxxx_create_activity_photos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_photos', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel activities
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            
            $table->string('file_name');
            $table->string('file_path');
            $table->integer('file_size'); // Disimpan dalam format KB
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_photos');
    }
    };