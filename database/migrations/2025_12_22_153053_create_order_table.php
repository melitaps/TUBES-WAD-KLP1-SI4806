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
        Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->string('no_order')->unique()->nullable(); 
        $table->string('nama_pemesan');
        $table->string('no_hp', 15);
        $table->text('alamat');
        $table->enum('metode_pembayaran', ['qris', 'cash']);
        $table->text('catatan_tambahan')->nullable(); 
        $table->decimal('total_harga', 10, 2);
        $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
        $table->timestamp('finished_at')->nullable(); 
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};