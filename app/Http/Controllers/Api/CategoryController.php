<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $category = Category::all();
        return CategoryResource::collection($category);
    }

    /**
     * store
     */
    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();
        try{
        $category = Category::create($request->validated());

        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data' => new CategoryResource($category)
        ],200);
        }catch(\Exception $e){
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    /**
     * show by id
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        return new CategoryResource($category);
    }

    /**
     * update
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        DB::beginTransaction();
        try{
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        DB::commit();
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil di Update',
            'data' => new CategoryResource($category),
        ],200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * delete
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try{
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ],200);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'terjadi kesalahan dalam penghapusan data',
                'error' => $e->getMessage()
            ],500);
        }
    }
}
