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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->integer('total_pakaian');
            $table->string('jenis_satuan');
            $table->double('harga_akhir');
            $table->double('total_harga_akhir');
            $table->double('total_harga_prioritas');
            $table->foreignUuid('transaksi_id')->constrained('transaksi', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
