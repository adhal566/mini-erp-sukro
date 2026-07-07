@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('header', 'Manajemen Produk')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('produk.index') }}" class="text-gray-500 hover:text-blue-600 mr-4">← Kembali</a>
    <h3 class="text-lg font-semibold text-gray-800">Tambah Produk Baru</h3>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden max-w-2xl">
    <form action="{{ route('produk.store') }}" method="POST" class="p-6">
        @csrf
        
        <div class="mb-4">
            <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('nama_produk') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label for="harga_jual" class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                <input type="number" name="harga_jual" id="harga_jual" value="{{ old('harga_jual', 0) }}" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('harga_jual') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label for="stok_produk" class="block text-sm font-medium text-gray-700 mb-1">Stok Awal Produk Jadi</label>
                <input type="number" name="stok_produk" id="stok_produk" value="{{ old('stok_produk', 0) }}" min="0" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('stok_produk') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 transition">Simpan Produk</button>
        </div>
    </form>
</div>
@endsection
