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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nota_layanan')->unique();
            $table->string('nota_pelanggan')->unique();
            $table->dateTime('waktu');
            $table->double('total_biaya_layanan');
            $table->double('total_biaya_prioritas');
            $table->double('total_bayar_akhir');
            $table->string('jenis_pembayaran');
            $table->double('bayar')->nullable();
            $table->double('kembalian')->nullable();
            $table->foreignId('pelanggan_id')->constrained('pelanggan', 'id');
            $table->foreignId('pegawai_laundry_id')->constrained('pegawai_laundry', 'id');
            $table->foreignId('gamis_id')->constrained('detail_gamis', 'id');
            $table->foreignId('cabang_id')->constrained('cabang', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
