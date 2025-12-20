<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat: {{ $child->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="flex justify-between mb-6 print:hidden">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-bold">
                    &larr; Kembali ke Dashboard
                </a>
                
                <div class="flex gap-2">
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded shadow hover:bg-gray-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak
                    </button>

                    <a href="{{ route('growth.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        + Cek Stunting Baru
                    </a>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Grafik Pertumbuhan & Validasi Akurasi</h3>

                @if($child->measurements->isEmpty())
                    <p class="text-center text-gray-500 py-10">Belum ada riwayat pengukuran untuk anak ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-3 px-4 text-left">Tanggal</th>
                                    <th class="py-3 px-4 text-center">Umur</th>
                                    <th class="py-3 px-4 text-center bg-blue-50 text-blue-700 border-l">Tinggi (AI)</th>
                                    <th class="py-3 px-4 text-center bg-yellow-100 text-yellow-800 border-l border-yellow-200">Validasi (Manual)</th> 
                                    <th class="py-3 px-4 text-center border-r">Akurasi</th> 
                                    <th class="py-3 px-4 text-center">Berat</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                    <th class="py-3 px-4 text-center">Foto</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @foreach($child->measurements->sortByDesc('created_at') as $m)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                                        
                                        <td class="py-3 px-4 text-left whitespace-nowrap">
                                            {{ $m->created_at->format('d M Y') }}
                                            <br><span class="text-xs text-gray-400">{{ $m->created_at->format('H:i') }}</span>
                                        </td>
                                        
                                        <td class="py-3 px-4 text-center font-bold">{{ $m->age_months }} Bln</td>
                                        
                                        <td class="py-3 px-4 text-center text-blue-600 font-bold bg-blue-50 border-l text-lg">
                                            {{ $m->height }} cm
                                        </td>

                                        <td class="py-3 px-4 text-center bg-yellow-50 border-l border-yellow-100">
                                            @if($m->manual_height)
                                                <div id="display-{{ $m->id }}" class="flex flex-col items-center justify-center">
                                                    <span class="font-bold text-gray-800 text-lg">{{ $m->manual_height }} cm</span>
                                                    <button onclick="document.getElementById('form-{{ $m->id }}').classList.remove('hidden'); document.getElementById('display-{{ $m->id }}').classList.add('hidden');" 
                                                            class="text-[10px] text-blue-500 hover:text-blue-700 underline mt-1 cursor-pointer">
                                                        [Ubah]
                                                    </button>
                                                </div>
                                                <form id="form-{{ $m->id }}" action="{{ route('measurements.update_manual', $m->id) }}" method="POST" class="hidden flex gap-1 justify-center items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" step="0.1" name="manual_height" value="{{ $m->manual_height }}"
                                                           class="w-16 text-xs border border-blue-400 rounded p-1 focus:ring-blue-500" required>
                                                    <button type="submit" class="bg-green-500 text-white p-1 rounded hover:bg-green-600 shadow-sm">✓</button>
                                                    <button type="button" onclick="document.getElementById('form-{{ $m->id }}').classList.add('hidden'); document.getElementById('display-{{ $m->id }}').classList.remove('hidden');" 
                                                            class="text-red-500 text-xs ml-1">x</button>
                                                </form>
                                            @else
                                                <form action="{{ route('measurements.update_manual', $m->id) }}" method="POST" class="flex gap-1 justify-center items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" step="0.1" name="manual_height" 
                                                           class="w-20 text-xs border border-gray-300 rounded p-1 focus:ring-yellow-500 focus:border-yellow-500" 
                                                           placeholder="cm asli" required>
                                                    <button type="submit" class="bg-yellow-500 text-white p-1 rounded hover:bg-yellow-600 shadow-sm">✓</button>
                                                </form>
                                            @endif
                                        </td>

                                        <td class="py-3 px-4 text-center border-r">
                                            @if($m->manual_height)
                                                @php
                                                    $selisih = abs($m->height - $m->manual_height);
                                                    $akurasi = 100 - (($selisih / $m->manual_height) * 100);
                                                    $bgBadge = $akurasi >= 95 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                                @endphp
                                                <div class="flex flex-col items-center">
                                                    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $bgBadge }}">
                                                        {{ number_format($akurasi, 1) }}%
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 mt-1">
                                                        Selisih: {{ number_format($selisih, 1) }} cm
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-gray-300">-</span>
                                            @endif
                                        </td>

                                        <td class="py-3 px-4 text-center">{{ $m->weight }} kg</td>
                                        
                                        <td class="py-3 px-4 text-center">
                                            <span class="px-2 py-1 rounded text-xs text-white {{ str_contains($m->status_height, 'Normal') ? 'bg-green-500' : 'bg-red-500' }}">
                                                {{ $m->status_height ?? '-' }}
                                            </span>
                                        </td>
                                        
                                        <td class="py-3 px-4 text-center">
                                            <button onclick="showImageModal('{{ asset('storage/' . $m->photo_path) }}')" 
                                                    class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 transition duration-150 ease-in-out shadow-sm flex items-center gap-1 mx-auto font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                Lihat
                                            </button>
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

    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeImageModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2" id="modal-title">Hasil Deteksi AI</h3>
                        <div class="mt-2 bg-gray-100 rounded-lg p-2 border border-gray-300">
                            <img id="modalImage" src="" alt="Bukti Foto" class="w-full h-auto rounded-md object-contain max-h-[70vh]">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeImageModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
            document.getElementById('modalImage').src = '';
        }
    </script>

    <style>
        @media print {
            nav, header, .print\:hidden, #imageModal { display: none !important; }
            body, .bg-gray-100 { background-color: white !important; }
        }
    </style>
</x-app-layout>