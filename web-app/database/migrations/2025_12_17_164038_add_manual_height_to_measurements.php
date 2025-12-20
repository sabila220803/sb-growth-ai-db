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
            // Menambahkan kolom manual_height setelah kolom weight
            // Tipe float agar bisa desimal (cth: 100.5 cm)
            // Nullable artinya boleh kosong (karena saat pertama AI ngukur, ini belum diisi)
            $table->float('manual_height')->nullable()->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('measurements', function (Blueprint $table) {
            // Hapus kolom jika migrasi di-rollback
            $table->dropColumn('manual_height');
        });
    }
};