<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();

        return view('master.index', compact('categories', 'brands'));
    }

    // --- LOGIKA KATEGORI ---
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:mj_master_categories,name|max:100',
        ]);

        Category::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required|max:100|unique:mj_master_categories,name,' . $id,
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Cek apakah kategori masih dipakai di produk
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Kategori ini masih digunakan oleh beberapa produk.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }

    // --- LOGIKA MERK (BRAND) ---
    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:mj_master_brands,name|max:100',
        ]);

        Brand::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Merk baru berhasil ditambahkan.');
    }

    public function updateBrand(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $request->validate([
            'name' => 'required|max:100|unique:mj_master_brands,name,' . $id,
        ]);

        $brand->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->back()->with('success', 'Data merk berhasil diperbarui.');
    }

    public function destroyBrand($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->products()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Merk ini masih digunakan oleh beberapa produk.');
        }

        $brand->delete();
        return redirect()->back()->with('success', 'Merk berhasil dihapus.');
    }
}