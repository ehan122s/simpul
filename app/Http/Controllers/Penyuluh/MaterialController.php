<?php

namespace App\Http\Controllers\Penyuluh;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // Halaman Daftar Materi & Fitur Search
    public function index(Request $request)
    {
        $search = $request->input('search');

        $materials = Material::when($search, function ($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                             ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(12);

        return view('penyuluh.materi.index', compact('materials'));
    }

    // Halaman Form Tambah Materi Baru
    public function create()
    {
        return view('penyuluh.materi.create');
    }

    // Proses Simpan Materi ke Database & Storage
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_materi' => 'required|file|mimes:pdf,ppt,pptx,doc,docx,xls,xlsx|max:10240', // Maks 10MB
        ]);

        $file = $request->file('file_materi');
        $ekstensi = $file->getClientOriginalExtension();
        
        // Simpan file ke folder storage/app/public/materials
        $path = $file->store('materials', 'public');

        Material::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'file_path' => $path,
            'tipe_file' => strtolower($ekstensi),
        ]);

        return redirect()->route('penyuluh.materi.index')->with('success', 'Materi berhasil diunggah!');
    }

    // Proses Hapus Materi
    public function destroy(Material $materi)
    {
        // Hapus file fisik dari storage
        if (Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }
        
        // Hapus data dari database
        $materi->delete();

        return redirect()->route('penyuluh.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}