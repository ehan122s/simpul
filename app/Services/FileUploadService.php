<?php

// File: app/Services/FileUploadService.php

namespace App\Services;

use App\Models\ActivityDocument;
use App\Models\ActivityPhoto;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function uploadPhotos($activityId, $photos)
    {
        if (!$photos) return;

        foreach ($photos as $photo) {
            $path = $photo->store('public/photos');
            
            ActivityPhoto::create([
                'activity_id' => $activityId,
                'file_name' => $photo->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $photo->getSize() / 1024, // Simpan dalam KB
            ]);
        }
    }

    public function uploadDocuments($activityId, $documents)
    {
        if (!$documents) return;

        foreach ($documents as $doc) {
            $path = $doc->store('public/documents');
            
            ActivityDocument::create([
                'activity_id' => $activityId,
                'file_name' => $doc->getClientOriginalName(),
                'file_path' => $path,
                'file_extension' => $doc->getClientOriginalExtension(),
                'file_size' => $doc->getSize() / 1024, // Simpan dalam KB
            ]);
        }
    }
}
