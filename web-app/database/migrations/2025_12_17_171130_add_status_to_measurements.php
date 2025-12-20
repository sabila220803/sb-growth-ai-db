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
        Schema::table('measurements', function (Blueprint $table) {
            // Cek dulu apakah kolomnya sudah ada (biar gak error kalau dijalankan ulang)
            if (!Schema::hasColumn('measurements', 'status_height')) {
                // Tambah kolom status tinggi (Normal/Stunting) setelah berat
                $table->string('status_height')->nullable()->after('weight');
            }
            
            if (!Schema::hasColumn('measurements', 'status_weight')) {
                // Tambah kolom status gizi (Normal/Gizi Buruk) setelah status tinggi
                $table->string('status_weight')->nullable()->after('status_height');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Hapus kolom jika di-rollback
            $table->dropColumn(['status_height', 'status_weight']);
        });
    }
};