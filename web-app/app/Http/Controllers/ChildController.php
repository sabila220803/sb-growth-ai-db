<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildController extends Controller
{
    // 1. Halaman Form Tambah Anak
    public function create()
    {
        return view('children.create');
    }

    // 2. Proses Simpan Data Anak ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
        ]);

        Child::create([
            'user_id' => Auth::id(), // Hubungkan dengan user yang sedang login
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return redirect()->route('dashboard')->with('success', 'Data anak berhasil ditambahkan!');
    }

    // 3. Halaman Detail Riwayat (Saat nama anak diklik)
    public function show($id)
    {
        // Cari anak berdasarkan ID, pastikan milik user yang login
        // 'with measurements' agar data pengukuran ikut terambil
        $child = Child::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->with('measurements')
                      ->firstOrFail();

        return view('children.show', compact('child'));
    }

    // 4. Proses Hapus Data Anak
    public function destroy($id)
    {
        // Cari anak berdasarkan ID dan pastikan milik user yang sedang login
        // (Ini keamanan agar user tidak bisa menghapus anak orang lain)
        $child = Child::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();
        
        // Hapus anak
        // Catatan: Karena di database kita set 'onDelete cascade', 
        // maka semua riwayat pengukuran anak ini akan otomatis terhapus juga.
        $child->delete();

        return redirect()->route('dashboard')->with('success', 'Data anak berhasil dihapus!');
    }
}