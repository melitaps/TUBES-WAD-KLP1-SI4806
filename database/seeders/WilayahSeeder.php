<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WilayahSeeder extends Seeder
{ // <--- Pembuka Class
    public function run(): void
    { // <--- Pembuka Method run
        Schema::disableForeignKeyConstraints();
        DB::table('wilayah')->truncate();
        Schema::enableForeignKeyConstraints();

        $response = Http::get('https://raw.githubusercontent.com/farizdotid/DAFTAR-API-LOKAL-INDONESIA/master/data/location/id.json');

        if ($response->successful()) {
            $data = $response->json();
            $insertData = [];

            foreach ($data as $item) {
                if (is_array($item)) {
                    $insertData[] = [
                        'provinsi' => $item['province'] ?? '',
                        'kota_kabupaten' => $item['city'] ?? '',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            foreach (array_chunk($insertData, 100) as $chunk) {
                DB::table('wilayah')->insert($chunk);
            }
        }
    } // <--- Penutup Method run
} // <--- Penutup Class (PASTIKAN TIDAK ADA KURUNG KELEBIHAN DI SINI)