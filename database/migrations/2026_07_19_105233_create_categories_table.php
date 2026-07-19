<?php

// File: app/Http/Requests/StoreActivityRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Hanya penyuluh yang boleh menambah kegiatan
        return auth()->user()->isPenyuluh();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'location' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'village' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'farmer_group_name' => 'required|string|max:255',
            'participant_count' => 'required|integer|min:1',
            'material' => 'required|string',
            'objective' => 'required|string',
            'result' => 'required|string',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB per foto
            'documents.*' => 'nullable|file|mimes:pdf,docx,xlsx,ppt|max:5120', // max 5MB per file
        ];
    }
}
