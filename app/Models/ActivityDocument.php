<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'file_name',
        'file_path',
        'file_extension',
        'file_size',
    ];

    // Relasi: Dokumen ini milik kegiatan yang mana?
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}