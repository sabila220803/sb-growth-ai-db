<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Child;       // PENTING: Panggil Model Anak
use App\Models\Measurement; // PENTING: Panggil Model Pengukuran
use Illuminate\Support\Facades\Auth;

class GrowthController extends Controller
{
    // Halaman Form (Pilih Anak Dulu)
    public function index()
    {
        // Ambil daftar anak milik user yang login
        $children = Child::where('user_id', Auth::id())->get();
        return view('growth.index', compact('children'));
    }

    public function process(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'child_id' => 'required|exists:children,id', // Harus pilih anak
            'image' => 'required|image|max:10240',
            'weight' => 'required|numeric|min:2|max:50',
            'age_months' => 'required|integer|min:0|max:60'
        ]);

        // Ambil Data Anak dari Database
        $child = Child::find($request->child_id);
        
        // Simpan Foto ke Folder Public
        $path = $request->file('image')->store('measurements', 'public');
        
        // Siapkan Stream Foto untuk AI
        $imageStream = fopen(storage_path("app/public/{$path}"), 'r');

        try {
            // 2. Kirim ke AI (Python)
            $response = Http::attach(
                'image', $imageStream, 'photo.jpg'
            )->post('http://127.0.0.1:5000/predict');

            if ($response->successful()) {
                $data = $response->json();
                
                // DATA HASIL AI
                $tinggiAI = $data['tinggi_badan']; 
                $debugImage = $data['debug_image'];

                // 3. HITUNG STATUS GIZI
                $analisisTinggi = $this->analisisTinggi($tinggiAI, $request->age_months, $child->gender);
                $analisisBerat = $this->analisisBerat($request->weight, $request->age_months, $child->gender);

                // 4. SIMPAN KE DATABASE (INI YANG BARU!)
                Measurement::create([
                    'child_id' => $child->id,
                    'age_months' => $request->age_months,
                    'height' => $tinggiAI,
                    'weight' => $request->weight,
                    'status_height' => $analisisTinggi['status'],
                    'status_weight' => $analisisBerat['status'],
                    'photo_path' => $path, // Simpan lokasi foto asli
                ]);

                // 5. TAMPILKAN HASIL
                return view('growth.result', [
                    'child' => $child,
                    'age' => $request->age_months,
                    'height' => $tinggiAI,
                    'weight' => $request->weight,
                    'resT' => $analisisTinggi,
                    'resB' => $analisisBerat,
                    'debugImage' => $debugImage
                ]);

            } else {
                return back()->with('error', 'Gagal memproses gambar: ' . ($response->json()['error'] ?? 'Unknown Error'));
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Server AI Mati. Error: ' . $e->getMessage());
        }
    }

    // --- LOGIKA WHO (SAMA SEPERTI SEBELUMNYA) ---
    private function analisisTinggi($tinggi, $umur, $gender) {
        $medianT = ($gender == 'male') 
            ? [24=>87.1, 36=>96.1, 48=>103.3, 60=>110.0] 
            : [24=>85.7, 36=>95.1, 48=>102.7, 60=>109.4];
        $ref = $this->getRef($umur, $medianT);
        $zScore = ($tinggi - $ref) / 3.5;
        
        $status = "Normal"; $color="green";
        if ($zScore < -3) { $status = "Sangat Pendek"; $color="darkred"; }
        elseif ($zScore < -2) { $status = "Pendek"; $color="red"; }
        elseif ($zScore > 3) { $status = "Tinggi"; $color="blue"; }
        
        return ['status'=>$status, 'z'=>round($zScore,2), 'color'=>$color];
    }

    private function analisisBerat($berat, $umur, $gender) {
        $medianB = ($gender == 'male') 
            ? [24=>12.2, 36=>14.3, 48=>16.3, 60=>18.3] 
            : [24=>11.5, 36=>13.9, 48=>15.8, 60=>18.2];
        $ref = $this->getRef($umur, $medianB);
        $zScore = ($berat - $ref) / 1.5;

        $status = "Normal"; $color="green";
        if ($zScore < -3) { $status = "Gizi Buruk"; $color="darkred"; }
        elseif ($zScore < -2) { $status = "Gizi Kurang"; $color="orange"; }
        elseif ($zScore > 2) { $status = "Gemuk"; $color="purple"; }

        return ['status'=>$status, 'z'=>round($zScore,2), 'color'=>$color];
    }

    private function getRef($umur, $arr) {
        $keys = array_keys($arr);
        $closest = $keys[0];
        foreach ($keys as $k) {
            if (abs($umur - $k) < abs($umur - $closest)) $closest = $k;
        }
        return $arr[$closest];
    }

    // Fungsi untuk menyimpan validasi manual
    public function updateManual(Request $request, $id)
    {
        $request->validate([
            'manual_height' => 'required|numeric|min:10|max:200',
        ]);

        $measurement = \App\Models\Measurement::findOrFail($id);
        $measurement->manual_height = $request->manual_height;
        $measurement->save();

        return back()->with('success', 'Data validasi berhasil disimpan!');
    }
}