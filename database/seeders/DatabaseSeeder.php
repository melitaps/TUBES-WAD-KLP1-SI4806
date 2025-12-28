<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WilayahSeeder::class,
            // CustomerSeeder::class, // Kalau lo bikin seeder customer nanti
        ]);
    }
}