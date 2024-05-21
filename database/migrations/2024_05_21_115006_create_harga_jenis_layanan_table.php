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
        Schema::create('harga_jenis_layanan', function (Blueprint $table) {
            $table->id();
            $table->double('harga');
            $table->string('jenis_satuan');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanan', 'id');
            $table->foreignId('jenis_pakaian_id')->constrained('jenis_pakaian', 'id');
            $table->foreignId('cabang_id')->constrained('cabang', 'id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_jenis_layanan');
    }
};
