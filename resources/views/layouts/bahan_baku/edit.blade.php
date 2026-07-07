@extends('layouts.app')

@section('title', 'Edit Bahan Baku')
@section('header', 'Manajemen Bahan Baku')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('bahan-baku.index') }}" class="text-gray-500 hover:text-blue-600 mr-4">← Kembali</a>
    <h3 class="text-lg font-semibold text-gray-800">Edit Data: {{ $bahanBaku->nama_bahan }}</h3>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <form action="{{ route('bahan-baku.update', $bahanBaku->id) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="nama_bahan" class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan Baku</label>
            <input type="text" name="nama_bahan" id="nama_bahan" value="{{ old('nama_bahan', $bahanBaku->nama_bahan) }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('nama_bahan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <select name="satuan" id="satuan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                    <option value="kg" {{ old('satuan', $bahanBaku->satuan) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                    <option value="gram" {{ old('satuan', $bahanBaku->satuan) == 'gram' ? 'selected' : '' }}>Gram (g)</option>
                    <option value="liter" {{ old('satuan', $bahanBaku->satuan) == 'liter' ? 'selected' : '' }}>Liter (l)</option>
                    <option value="pcs" {{ old('satuan', $bahanBaku->satuan) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                </select>
                @error('satuan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="harga_per_satuan" class="block text-sm font-medium text-gray-700 mb-1">Harga per Satuan (Rp)</label>
                <input type="number" name="harga_per_satuan" id="harga_per_satuan" value="{{ old('harga_per_satuan', (int) $bahanBaku->harga_per_satuan) }}" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('harga_per_satuan') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="stok_saat_ini" class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
            <input type="number" step="0.01" name="stok_saat_ini" id="stok_saat_ini" value="{{ old('stok_saat_ini', (float) $bahanBaku->stok_saat_ini) }}" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('stok_saat_ini') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition">Update Data</button>
        </div>
    </form>
</div>
@endsection
