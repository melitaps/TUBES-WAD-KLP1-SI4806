<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('no_hp');
            $table->text('alamat');


            $table->unsignedBigInteger('wilayah_id');

            $table->integer('total_pesanan')->default(0);
            $table->decimal('total_transaksi', 15, 2)->default(0);

            $table->timestamps();


            $table->foreign('wilayah_id')
                  ->references('id')
                  ->on('wilayah')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
