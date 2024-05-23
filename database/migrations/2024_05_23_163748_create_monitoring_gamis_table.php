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
        Schema::create('monitoring_gamis', function (Blueprint $table) {
            $table->id();
            $table->double('upah');
            $table->string('status');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->foreignId('detail_gamis_id')->constrained('detail_gamis', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring_gamis');
    }
};
