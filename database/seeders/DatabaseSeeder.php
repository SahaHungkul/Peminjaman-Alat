<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // admin
        User::create([
            'name' =>'Admin Utama',
            'email' => 'admin@app.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // petugas
        User::create([
            'name' =>'Petugas Lab',
            'email' => 'petugas@app.com',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        //
        User::create([
            'name' =>'Siswa 1',
            'email' => 'siswa@app.com',
            'password' => bcrypt('password'),
            'role' => 'peminjam'
        ]);
    }
}
