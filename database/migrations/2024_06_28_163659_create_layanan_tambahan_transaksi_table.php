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
        Schema::create('layanan_tambahan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_tambahan_id')->constrained('layanan_tambahan', 'id');
            $table->foreignUuid('transaksi_id')->constrained('transaksi', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_tambahan_transaksi');
    }
};
