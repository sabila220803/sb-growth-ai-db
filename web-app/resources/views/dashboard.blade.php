<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Orang Tua') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 text-right">
                <a href="{{ route('children.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    + Tambah Data Anak
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Daftar Anak Anda:</h3>

                    {{-- Cek apakah ada data anak? --}}
                    @if($children->isEmpty())
                        
                        <div class="text-center py-10">
                            <p class="text-gray-500 mb-4">Anda belum menambahkan data anak.</p>
                            <a href="{{ route('children.create') }}" class="text-blue-600 hover:underline">
                                Klik di sini untuk menambah data
                            </a>
                        </div>

                    @else

                        {{-- Tampilkan Kartu Anak (Grid) --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($children as $child)
                                <div class="border border-gray-200 p-5 rounded-xl shadow-sm hover:shadow-md transition bg-gray-50">
                                    
                                    <div class="flex items-center mb-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3 {{ $child->gender == 'male' ? 'bg-blue-500' : 'bg-pink-500' }}">
                                            {{ substr($child->name, 0, 1) }}
                                        </div>
                                        <div>
                                            {{-- <h4 class="text-lg font-bold text-gray-800">{{ $child->name }}</h4> --}}
                                            <a href="{{ route('children.show', $child->id) }}" class="text-lg font-bold text-blue-600 hover:underline">
                                                {{ $child->name }} &rarr;
                                            </a>
                                            <span class="text-xs text-gray-500 uppercase tracking-wide">{{ $child->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-4 text-sm text-gray-600">
                                        Umur: <strong>{{ \Carbon\Carbon::parse($child->date_of_birth)->age }} Tahun</strong>
                                        <br>
                                        <span class="text-xs">({{ \Carbon\Carbon::parse($child->date_of_birth)->diffInMonths(\Carbon\Carbon::now()) }} Bulan)</span>
                                    </div>

                                    <hr class="border-gray-200 my-3">

                                    {{-- <a href="{{ route('growth.index') }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                                        📸 Cek Stunting
                                    </a> --}}
                                    <div class="flex gap-2">
                                        <a href="{{ route('growth.index') }}" class="flex-1 text-center bg-indigo-600 text-white py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                                            📸 Cek
                                        </a>

                                        <form action="{{ route('children.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data {{ $child->name }}? Semua riwayat pengukurannya juga akan hilang permanen.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full h-full px-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus Data">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>