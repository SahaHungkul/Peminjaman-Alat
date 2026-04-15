<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    use WithoutModelEvents;

    public function run(): void
    {
        Category::create([
            'nama_kategori' => 'Kunci'
        ]);
        Category::create([
            'nama_kategori' => 'Obeng'
        ]);
        Category::create([
            'nama_kategori' => 'Palu'
        ]);
    }
}
