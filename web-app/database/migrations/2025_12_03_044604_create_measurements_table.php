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
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();

            // Menghubungkan hasil ukur ke data anak (Tabel children)
            // Jika data anak dihapus, semua riwayat pengukurannya ikut terhapus (cascade)
            $table->foreignId('child_id')->constrained()->onDelete('cascade');

            $table->integer('age_months');   // Umur saat diukur (bulan)
            $table->float('height');         // Tinggi Badan (cm)
            $table->float('weight');         // Berat Badan (kg)
            $table->string('status_height'); // Status Tinggi (Stunting/Normal)
            $table->string('status_weight'); // Status Berat (Gizi Buruk/Normal)
            $table->string('photo_path');    // Lokasi file foto bukti

            $table->timestamps(); // Mencatat tanggal pengukuran
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};