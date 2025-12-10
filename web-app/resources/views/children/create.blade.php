<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Anak') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('children.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Nama Anak</label>
                            <input type="text" name="name" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Jenis Kelamin</label>
                            <select name="gender" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1">
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block w-full mt-1" required>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Simpan Data
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>