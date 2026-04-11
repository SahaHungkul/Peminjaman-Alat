<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('tools')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori'
            ]);

            Category::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            ActivityLog::record('Tambah Kategori', 'Menambah kategori baru: ' . $request->nama_kategori);

            DB::commit();

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal store kategori: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $category->id
            ]);

            $oldName = $category->nama_kategori;

            $category->update([
                'nama_kategori' => $request->nama_kategori
            ]);

            ActivityLog::record('Update Kategori', "Mengubah kategori '$oldName' menjadi '" . $request->nama_kategori . "'");

            DB::commit();

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal update kategori: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        DB::beginTransaction();

        try {
            if ($category->tools()->count() > 0) {
                return back()->withErrors(['error', 'Kategori tidak bisa dihapus karena masih memiliki data alat. Hapus atau pindahkan alatnya terlebih dahulu.']);
            }

            $nama = $category->nama_kategori;

            $category->delete();

            ActivityLog::record('Hapus Kategori', 'Menghapus kategori: ' . $nama);

            DB::commit();

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal destroy kategori: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }
}
