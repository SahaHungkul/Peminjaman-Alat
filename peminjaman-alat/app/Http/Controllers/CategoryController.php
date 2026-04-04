<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories =Category::withCount('tools')->latest()->paginate(10);
        return view('admin.categories.index',compact('cataegories'));
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
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori'
        ]);
        Category::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        ActivityLog::record('Tambah Kategori', 'Menambah Kategori Baru.:' . $request->nama_kategori);
        return redirect()->route('categories.index')->with('success','Kategori Berhasil ditambahkan.');
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
        return view('admin.categories.edit', compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, category $category)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori' . $category->id
        ]);
        $oldName = $category->nama_kategori;
        Category::update([
            'nama_kategori' => $request->nama_kategori
        ]);

        ActivityLog::record('update Kategori', "Mengubah Kategori $oldName menjadi " . $request->nama_kategori);
        return redirect()->route('categories.index')->with('success','Kategori Berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(category $category)
    {
        if($category->tools() >0){
            return back()->withErrors(['error' => 'Kategori tidak bisa dihapus karena masih memiliki data alat. hapus atau pindahkan alatnya terlebih dahulu']);
        }
        $nama = $category->nama_kategori;
        $category->delete();

        ActivityLog::record('Hapus Kategori', 'Menghapus Kategori: ' . $nama);
        return redirect()->route('categories.index')->with('success','Kategori Berhasil dihapus.');

    }
}
