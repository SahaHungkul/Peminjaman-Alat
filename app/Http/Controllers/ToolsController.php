<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Tools;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stok' => 'required|integer|min:0',
            // 'denda_per_hari' => 'required|',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string'
        ]);
        DB::beginTransaction();

        try {
            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('tools', 'public');
            }

            Tools::create([
                'nama_alat' => $request->nama_alat,
                'category_id' => $request->category_id,
                'stok' => $request->stok,
                'deskripsi' => $request->deskripsi,
                'gambar' => $gambarPath
            ]);

            ActivityLog::record('Tambah Alat', 'Menambahkan alat baru: ' . $request->nama_alat);

            DB::commit();

            return redirect()->route('tools.index')->with('success', 'Alat berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal store tools: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tools $tools)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tools $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tools $tool)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'nama_alat' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'stok' => 'required|integer|min:0',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'deskripsi' => 'nullable|string'
            ]);

            $data = $request->except('gambar');

            if ($request->hasFile('gambar')) {
                if ($tool->gambar && Storage::disk('public')->exists($tool->gambar)) {
                    Storage::disk('public')->delete($tool->gambar);
                }
                $data['gambar'] = $request->file('gambar')->store('tools', 'public');
            }

            $tool->update($data);

            ActivityLog::record('Update Alat', 'Memperbarui data alat: ' . $tool->nama_alat);

            DB::commit();

            return redirect()->route('tools.index')->with('success', 'Alat berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal update tools: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tools $tool)
    {
        DB::beginTransaction();

        try {
            if ($tool->gambar && Storage::disk('public')->exists($tool->gambar)) {
                Storage::disk('public')->delete($tool->gambar);
            }

            $namaAlat = $tool->nama_alat;
            $tool->delete();

            ActivityLog::record('Hapus Alat', 'Menghapus alat: ' . $namaAlat);

            DB::commit();

            return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus.');
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal destroy tools: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.')->withInput();
        }
    }
}
