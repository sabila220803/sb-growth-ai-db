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

                <form action="{{ route('analyze.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Pilih Anak</label>
                        <select name="child_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($children as $child)
                                <option value="{{ $child->id }}">{{ $child->name }} ({{ ucfirst($child->gender) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Umur (Bulan)</label>
                            <input type="number" name="age_months" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Berat Badan (Kg)</label>
                            <input type="number" name="weight" step="0.1" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">Upload Foto (dengan Kertas A4)</label>
                        <input type="file" name="image" class="w-full border p-2 rounded" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                        Mulai Analisis AI 🚀
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>