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
    protected $fileUploadService;
    protected $reportService;

    public function __construct(FileUploadService $fileUploadService, ReportGeneratorService $reportService)
    {
        $this->fileUploadService = $fileUploadService;
        $this->reportService = $reportService;
    }

    public function index()
    {
        $activities = Activity::where('user_id', auth()->id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('penyuluh.activities.index', compact('activities'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('penyuluh.activities.create', compact('categories'));
    }

    public function store(StoreActivityRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Simpan data utama kegiatan
        $activity = Activity::create($validated);

        // Upload Foto Kegiatan jika ada
        if ($request->hasFile('photos')) {
            $this->fileUploadService->uploadPhotos($activity, $request->file('photos'));
        }

        // Upload Dokumen Lampiran jika ada
        if ($request->hasFile('documents')) {
            $this->fileUploadService->uploadDocuments($activity, $request->file('documents'));
        }

        return redirect()->route('penyuluh.activities.index')
            ->with('success', 'Laporan kegiatan berhasil disimpan dan dikirim!');
    }

    public function show(Activity $activity)
    {
        // Pastikan penyuluh hanya bisa melihat kegiatannya sendiri
        if ($activity->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $activity->load(['category', 'photos', 'documents']);
        return view('penyuluh.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        if ($activity->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $categories = Category::all();
        return view('penyuluh.activities.edit', compact('activity', 'categories'));
    }

    public function update(StoreActivityRequest $request, Activity $activity)
    {
        if ($activity->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $validated = $request->validated();
        $activity->update($validated);

        if ($request->hasFile('photos')) {
            $this->fileUploadService->uploadPhotos($activity, $request->file('photos'));
        }

        if ($request->hasFile('documents')) {
            $this->fileUploadService->uploadDocuments($activity, $request->file('documents'));
        }

        return redirect()->route('penyuluh.activities.index')
            ->with('success', 'Laporan kegiatan berhasil diperbarui!');
    }

    public function destroy(Activity $activity)
    {
        if ($activity->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        // Hapus file fisik via service sebelum hapus data di DB
        $this->fileUploadService->deleteActivityFiles($activity);
        $activity->delete();

        return redirect()->route('penyuluh.activities.index')
            ->with('success', 'Laporan kegiatan berhasil dihapus.');
    }

    // Fitur Cetak PDF Laporan Kegiatan
    public function downloadPdf(Activity $activity)
    {
        if ($activity->user_id !== auth()->id()) {
            abort(403);
        }
        return $this->reportService->generateSingleActivityPdf($activity);
    }
}