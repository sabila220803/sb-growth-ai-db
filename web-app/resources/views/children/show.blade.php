<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat: {{ $child->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between mb-6">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-bold">
                    &larr; Kembali ke Dashboard
                </a>
                <a href="{{ route('growth.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    + Cek Stunting Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Grafik Pertumbuhan (Riwayat)</h3>

                @if($child->measurements->isEmpty())
                    <p class="text-center text-gray-500 py-10">Belum ada riwayat pengukuran untuk anak ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Tanggal</th>
                                    <th class="py-3 px-6 text-center">Umur</th>
                                    <th class="py-3 px-6 text-center">Tinggi (AI)</th>
                                    <th class="py-3 px-6 text-center">Berat</th>
                                    <th class="py-3 px-6 text-center">Status Gizi</th>
                                    <th class="py-3 px-6 text-center">Foto</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @foreach($child->measurements->sortByDesc('created_at') as $m)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        <td class="py-3 px-6 text-left whitespace-nowrap">
                                            {{ $m->created_at->format('d M Y') }}
                                            <br><span class="text-xs text-gray-400">{{ $m->created_at->format('H:i') }}</span>
                                        </td>
                                        <td class="py-3 px-6 text-center font-bold">{{ $m->age_months }} Bln</td>
                                        <td class="py-3 px-6 text-center text-blue-600 font-bold">{{ $m->height }} cm</td>
                                        <td class="py-3 px-6 text-center">{{ $m->weight }} kg</td>
                                        <td class="py-3 px-6 text-center">
                                            <span class="px-2 py-1 rounded text-xs text-white 
                                                {{ str_contains($m->status_height, 'Normal') ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ $m->status_height }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center">
                                            <a href="{{ asset('storage/' . $m->photo_path) }}" target="_blank" class="text-indigo-500 hover:underline">
                                                Lihat Foto
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>