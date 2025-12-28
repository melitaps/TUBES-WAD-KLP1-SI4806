<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Wilayah;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil satu ID wilayah yang baru saja di-seed dari API
        $wilayah = Wilayah::first();

        if ($wilayah) {
            Customer::create([
                'nama' => 'Yanto',
                'no_hp' => '0813828367464',
                'alamat' => 'Sukabirus',
                'wilayah_id' => $wilayah->id,
                'total_pesanan' => 1,
                'total_transaksi' => 15000
            ]);
        }
    }
}