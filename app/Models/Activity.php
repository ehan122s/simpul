<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'activity_date',
        'location',
        'latitude',
        'longitude',
        'village',
        'district',
        'regency',
        'farmer_group_name',
        'participant_count',
        'material',
        'objective',
        'result',
        'obstacle',
        'follow_up',
        'notes',
    ];

    protected $casts = [
        'activity_date' => 'date', // Mengubah string tanggal menjadi instance Carbon
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relasi (BelongsTo): Kegiatan ini milik siapa (User/Penyuluh)?
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi (BelongsTo): Kegiatan ini masuk kategori apa?
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi (HasMany): Kegiatan ini memiliki banyak dokumen lampiran
    public function documents(): HasMany
    {
        return $this->hasMany(ActivityDocument::class);
    }

    // Relasi (HasMany): Kegiatan ini memiliki banyak foto dokumentasi
    public function photos(): HasMany
    {
        return $this->hasMany(ActivityPhoto::class);
    }
}
