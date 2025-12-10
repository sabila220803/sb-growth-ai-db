<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GrowthAI - Cek Gizi Lengkap</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #eef2f7; display: flex; justify-content: center; padding: 20px; }
        .container { display: flex; gap: 20px; flex-wrap: wrap; justify-content: center; width: 100%; max-width: 900px; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); flex: 1; min-width: 300px; }
        
        h1 { color: #2c3e50; font-size: 24px; margin-bottom: 5px; }
        .subtitle { color: #7f8c8d; font-size: 14px; margin-bottom: 20px; display: block; }
        
        label { display: block; margin-top: 15px; font-weight: 600; color: #34495e; font-size: 14px; }
        input, select { width: 100%; padding: 12px; margin-top: 5px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        
        .btn-submit { width: 100%; margin-top: 25px; padding: 14px; background: #007bff; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-submit:hover { background: #0056b3; }

        /* Style untuk Panduan */
        .guide-box { background: #f8f9fa; border-left: 4px solid #f39c12; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .guide-title { font-weight: bold; color: #e67e22; display: block; margin-bottom: 5px; }
        .guide-list { margin: 0; padding-left: 20px; font-size: 13px; color: #555; }
        .guide-list li { margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="container">
    <div class="card" style="max-width: 350px;">
        <h3>📸 Panduan Foto</h3>
        <p style="font-size:13px; color:#666;">Agar hasil akurat, mohon ikuti aturan ini:</p>
        
        <div class="guide-box">
            <span class="guide-title">⚠️ Wajib Dilakukan:</span>
            <ul class="guide-list">
                <li>Tempelkan kertas A4 di dinding/lemari (jangan dipegang).</li>
                <li>Anak berdiri tegak, tumit menempel ke dinding.</li>
                <li>Anak berdiri di samping kertas (sejajar).</li>
                <li>Foto dari depan (sejajar dada anak), lurus, tidak miring.</li>
            </ul>
        </div>

        {{-- <div style="text-align: center;">
            <img src="https://cdn-icons-png.flaticon.com/512/2659/2659360.png" width="100" style="opacity:0.7">
            <p style="font-size:12px; color:#999;">Posisi Kamera Harus Tegak Lurus</p>
        </div> --}}
        <div style="text-align: center; margin-top: 20px;">
            <div style="border: 2px dashed #ccc; padding: 5px; border-radius: 10px; background: white; display: inline-block;">
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhX5Pz7J7z9l9l9l9l9/s1600/posisi-foto-stunting.png"
                {{-- <img src="{{ asset('images/panduan.jpg') }}"  --}}
                     alt="Contoh Posisi Foto" 
                     style="width: 100%; max-width: 250px; border-radius: 8px;">
            </div>
            
            <div style="margin-top: 10px;">
                <img src="https://cdn-icons-png.flaticon.com/512/2659/2659360.png" width="40" style="opacity:0.7; vertical-align: middle;">
                <span style="font-size:12px; color:#e67e22; font-weight: bold;">Posisi Kamera Harus Tegak Lurus</span>
            </div>
        </div>
    </div>

    <div class="card">
        <h1>GrowthAI 🌱</h1>
        <span class="subtitle">Sistem Deteksi Dini Stunting & Gizi</span>

        <form action="{{ route('analyze.image') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <label>Jenis Kelamin:</label>
            <select name="gender" required>
                <option value="male">Laki-laki</option>
                <option value="female">Perempuan</option>
            </select>

            <div style="display: flex; gap: 10px;">
                <div style="flex:1;">
                    <label>Umur (Bulan):</label>
                    <input type="number" name="age_months" placeholder="24-60" min="24" max="60" required>
                </div>
                <div style="flex:1;">
                    <label>Berat Badan (Kg):</label>
                    <input type="number" name="weight" placeholder="Contoh: 12.5" step="0.1" required>
                </div>
            </div>

            <label>Upload Foto (dengan Kertas A4):</label>
            <input type="file" name="image" required accept="image/*">

            <button type="submit" class="btn-submit">Mulai Analisis</button>
        </form>
    </div>
</div>

</body>
</html>