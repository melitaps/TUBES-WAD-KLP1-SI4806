<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'nama_kategori' => 'Menu Utama',
        ]);

        Kategori::create([
            'nama_kategori' => 'Menu Tambahan',
        ]);

        Kategori::create([
            'nama_kategori' => 'Menu Minuman',
        ]);
    }
}
