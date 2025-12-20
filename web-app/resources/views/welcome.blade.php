<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GrowthAI - Deteksi Stunting Dini</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-white text-gray-800 font-sans">

    <nav class="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <div class="bg-blue-600 text-white p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <span class="text-xl font-bold text-gray-800">Growth<span class="text-blue-600">AI</span></span>
        </div>

        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-blue-600">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-blue-600">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition">Daftar Sekarang</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-16 flex flex-col-reverse md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold mb-4 inline-block">Teknologi AI Terbaru</span>
            <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4 text-gray-900">
                Pantau Tumbuh Kembang Anak, <span class="text-blue-600">Cegah Stunting</span> Sejak Dini.
            </h1>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                Gunakan kecerdasan buatan (AI) untuk mengukur tinggi badan anak hanya lewat foto. Akurat, cepat, dan sesuai standar WHO.
            </p>
            <div class="flex gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                    Coba Gratis
                </a>
                <a href="#fitur" class="px-8 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Pelajari Cara Kerja
                </a>
            </div>
        </div>
        
        <div class="md:w-1/2 flex justify-center">
            <img src="{{ asset('img/dokter_anak.jpg') }}" 
            alt="Ilustrasi Dokter dan Anak" 
            class="rounded-2xl shadow-xl w-auto h-[400px] object-contain bg-blue-50">
        </div>
    </div>

    <div id="fitur" class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Mengapa GrowthAI?</h2>
                <p class="text-gray-600 mt-2">Solusi modern untuk pemantauan kesehatan balita.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Mudah Digunakan</h3>
                    <p class="text-gray-600">Cukup unggah foto anak berdiri tegak, sistem akan otomatis mendeteksi titik tubuhnya.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Akurasi Tinggi</h3>
                    <p class="text-gray-600">Menggunakan Computer Vision & Pose Estimation untuk pengukuran presisi hingga satuan cm.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Standar WHO</h3>
                    <p class="text-gray-600">Hasil analisis status gizi (Normal/Stunting) langsung disesuaikan dengan kurva pertumbuhan WHO.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t py-8">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-500">
            <p>&copy; 2025 GrowthAI System. Universitas Islam Sultan Agung.</p>
        </div>
    </footer>
</body>
</html>