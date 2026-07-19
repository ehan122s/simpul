<?php

namespace App\Http\Controllers\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use App\Http\Requests\StoreActivityRequest;
use App\Services\FileUploadService;
use App\Services\ReportGeneratorService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        // Menampilkan data kegiatan milik penyuluh yang login, urut terbaru
        $activities = Activity::where('user_id', auth()->id())
                        ->orderBy('activity_date', 'desc')
                        ->paginate(10);
                        
        return view('penyuluh.activities.index', compact('activities'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('penyuluh.activities.create', compact('categories'));
    }

    // Menggunakan Form Request & FileUploadService (Dependency Injection)
    public function store(StoreActivityRequest $request, FileUploadService $fileService)
    {
        // 1. Simpan data teks ke tabel activities
        $activity = auth()->user()->activities()->create($request->validated());

        // 2. Simpan file menggunakan Service
        if ($request->hasFile('photos')) {
            $fileService->uploadPhotos($activity->id, $request->file('photos'));
        }

        if ($request->hasFile('documents')) {
            $fileService->uploadDocuments($activity->id, $request->file('documents'));
        }

        // 3. Redirect dengan notifikasi sukses
        return redirect()->route('penyuluh.activities.index')
                         ->with('success', 'Kegiatan berhasil disimpan beserta dokumen/fotonos.');
    }

    public function show(Activity $activity)
    {
        // Proteksi: Pastikan penyuluh hanya bisa melihat kegiatannya sendiri
        if ($activity->user_id !== auth()->id()) {
            abort(403, 'Anda tidak diizinkan mengakses dokumen ini.');
        }

        $activity->load(['category', 'photos', 'documents']); // Eager loading relasi
        
        return view('penyuluh.activities.show', compact('activity'));
    }

    // Fungsi untuk memanggil layanan generate laporan
    public function generateReport(Activity $activity, Request $request, ReportGeneratorService $reportService)
    {
        if ($activity->user_id !== auth()->id()) abort(403);

        $format = $request->query('format', 'pdf'); // default PDF

        if ($format === 'word') {
            return $reportService->generateWord($activity);
        }

        return $reportService->generatePDF($activity);
    }
}
