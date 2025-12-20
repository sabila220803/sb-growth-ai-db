<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cek Stunting Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 mb-4 rounded border border-red-200">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('analyze.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Pilih Anak</label>
                        <select name="child_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            @if($children->isEmpty())
                                <option value="" disabled selected>-- Tidak ada data anak --</option>
                            @else
                                @foreach($children as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }} ({{ ucfirst($child->gender) }})</option>
                                @endforeach
                            @endif
                        </select>
                        @if($children->isEmpty())
                            <a href="{{ route('children.create') }}" class="text-xs text-blue-600 hover:underline mt-1 block">+ Tambah Data Anak Dulu</a>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Umur (Bulan)</label>
                            <input type="number" name="age_months" min="0" max="60" 
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Contoh: 12" required>
                            <p class="text-xs text-gray-500 mt-1">*Maksimal 60 bulan (5 tahun)</p>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1">Berat Badan (Kg)</label>
                            <input type="number" name="weight" step="0.1" min="1" max="50"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="Contoh: 10.5" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-1">Upload Foto (dengan Kertas A4)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload file</span>
                                        <input id="file-upload" name="image" type="file" accept="image/*" class="sr-only" required onchange="document.getElementById('file-name').innerText = this.files[0].name">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB</p>
                                <p id="file-name" class="text-sm text-green-600 font-bold mt-2"></p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-0.5">
                        Mulai Analisis AI 🚀
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>