<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    // Kolom-kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'child_id',      // <--- INI PENTING! (Menghubungkan ke tabel anak)
        'age_months',    // Umur saat diukur
        'height',        // Tinggi badan
        'weight',        // Berat badan
        'status_height', // Status Stunting (Normal/Pendek)
        'status_weight', // Status Gizi (Normal/Kurang)
        'photo_path',    // Lokasi file foto
    ];

    // Relasi ke Tabel Anak (Setiap pengukuran milik satu anak)
    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}