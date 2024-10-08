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
        Schema::create('detail_layanan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('harga_jenis_layanan_id')->constrained('harga_jenis_layanan', 'id');
            $table->foreignId('detail_transaksi_id')->constrained('detail_transaksi', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_layanan_transaksi');
    }
};
