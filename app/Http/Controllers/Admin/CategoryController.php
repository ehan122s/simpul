<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori kegiatan baru berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori kegiatan berhasil diubah!');
    }

    public function destroy(Category $category)
    {
        // Cek jika kategori masih dipakai di tabel kegiatan
        if ($category->activities()->exists()) {
            return redirect()->route('admin.categories.index')->with('error', 'Kategori gagal dihapus karena masih digunakan dalam laporan kegiatan penyuluh.');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori kegiatan berhasil dihapus.');
    }
}