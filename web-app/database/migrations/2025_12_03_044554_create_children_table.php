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
        Schema::create('children', function (Blueprint $table) {
            $table->id(); // ID Unik
            
            // Kolom ini menghubungkan anak dengan orang tua (User)
            // Jika User dihapus, data anak ikut terhapus (cascade)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name'); // Nama Anak
            $table->enum('gender', ['male', 'female']); // Jenis Kelamin
            $table->date('date_of_birth'); // Tanggal Lahir
            
            $table->timestamps(); // Mencatat kapan dibuat & diupdate
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};