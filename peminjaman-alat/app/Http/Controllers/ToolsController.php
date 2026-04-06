<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tools = Tools::with('category')->latest()->paginate(10);
        return view('admin.tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.tools.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validasi
        // $request->validate([
        //     'nama_alat' => 'required|string|max:255',
        //     'category_id' => 'required|exist:categories,id',
        //     'stok' => 'required|integer|min:0',
        //     'gambar' => 'nullable|images|mimes:jpeg,png,jpg|max:2048', //gambar dengan max size sebesar 2mb
        //     'deskripsi' => 'nullable|string'
        // ]);
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'deskripsi' => 'nullable|string'
        ]);

        // handle untuk gambar
        $gambarPath = null;
        if($request->hasFile('gambar')){
            $gambarPath = $request->file('gambar')->store('tools','public');
        }

        // simpan ke DB
        Tools::create([
            'nama_alat' => $request->nama_alat,
            'category_id' => $request->category_id,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath
        ]);

        // catatan Log.
        ActivityLog::record('Tambah Alat', 'Menambahkan Alat Baru: ' . $request->nama_alat);
        return redirect()->route('tools.index')->with('success','Alat Berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(tools $tools)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(tools $tools)
    {
        $categories = Tools::all();
        return view('tools.edit',compact( 'tools','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, tools $tools)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'category_id' => 'required|exist:categories,id',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|images|mimes:jpeg,png,jpg|max:2048', //gambar dengan max size sebesar 2mb
            'deskripsi' => 'nullable|string'
        ]);

        $data = $request->except('gambar');

        // handle untuk gambar
        if($request->hasFile('gambar')){
            if($tools->gambar && Storage::disk('public')->exists($tools->gambar)){
                Storage::disk('public')->delete($tools->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('tools','public');
        }

        $tools->update($data);

        // catatan Log.
        ActivityLog::record('Update Alat', 'Memperbarui Data Alat: ' . $tools->nama_alat);
        return redirect()->route('tools.index')->with('success','Alat Berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tools $tool)
    {
        // if($tools->gambar && Storage::disk('public')->exists($tools->gambar)){
        //     Storage::disk('public')->delete($tools->gambar);
        // }

        // $namaAlat = $tools->nama_alat;
        // $tools->delete();

        // ActivityLog::record('Hapus Alat', "Mengahpus Alat: " . $namaAlat  );
        // return redirect()->route('tools.index')->with('success','Alat Berhasil dihapus.');

         // Hapus file gambar dari storage jika ada
        if ($tool->gambar && Storage::disk('public')->exists($tool->gambar)) {
            Storage::disk('public')->delete($tool->gambar);
        }

        $namaAlat = $tool->nama_alat;
        $tool->delete();

        ActivityLog::record('Hapus Alat', 'Menghapus alat: ' . $namaAlat);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus.');
    }
}
