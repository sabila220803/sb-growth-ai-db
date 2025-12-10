<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Analisis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Hasil Pemeriksaan: {{ $child->name }}</h3>
                <p class="text-gray-500 mb-6">Umur: {{ $age }} Bulan | Berat: {{ $weight }} Kg</p>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 bg-gray-50 rounded-lg border border-l-4" style="border-left-color: {{ $resT['color'] }}">
                        <p class="text-sm text-gray-600">Tinggi Badan (AI)</p>
                        <h2 class="text-3xl font-bold text-gray-800">{{ $height }} cm</h2>
                        <span class="px-2 py-1 text-xs font-bold text-white rounded" style="background-color: {{ $resT['color'] }}">
                            {{ $resT['status'] }}
                        </span>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-l-4" style="border-left-color: {{ $resB['color'] }}">
                        <p class="text-sm text-gray-600">Berat Badan</p>
                        <h2 class="text-3xl font-bold text-gray-800">{{ $weight }} kg</h2>
                        <span class="px-2 py-1 text-xs font-bold text-white rounded" style="background-color: {{ $resB['color'] }}">
                            {{ $resB['status'] }}
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="font-bold text-left mb-2 text-gray-700">Visualisasi Debug AI:</h4>
                    <img src="data:image/jpeg;base64, {{ $debugImage }}" class="w-full rounded-lg border">
                </div>

                <a href="{{ route('dashboard') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>