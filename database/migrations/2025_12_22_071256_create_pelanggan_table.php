<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            
            // Data Pribadi Pelanggan
            $table->string('nama');
            $table->string('nomor_hp', 20)->unique();
            
            // Data Wilayah dari API Wilayah Indonesia
            $table->string('province_id');
            $table->string('province_name');
            $table->string('city_id');
            $table->string('city_name');
            $table->string('district_id')->nullable();
            $table->string('district_name')->nullable();
            $table->string('village_id')->nullable();
            $table->string('village_name')->nullable();
            
            // Alamat Detail (RT/RW, Nama Jalan, Patokan, dll)
            $table->text('alamat');
            
            $table->timestamps();

            // Index untuk mempercepat pencarian
            $table->index('nama');
            $table->index('nomor_hp');
            $table->index('province_id');
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};