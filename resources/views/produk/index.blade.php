@extends('layouts.app')

@section('title', 'Data Produk & Resep')
@section('header', 'Manajemen Produk')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800">Daftar Produk Sukro</h3>
    <a href="{{ route('produk.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition">
        + Tambah Produk
    </a>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($produk as $item)
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-6 flex-1">
            <div class="flex justify-between items-start mb-4">
                <h4 class="text-xl font-bold text-gray-800">{{ $item->nama_produk }}</h4>
                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-bold rounded-md">Stok: {{ $item->stok_produk }}</span>
            </div>
            
            <div class="space-y-2 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Harga Jual:</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">HPP Saat Ini:</span>
                    <span class="font-semibold text-red-600">Rp {{ number_format($item->hpp_terakhir, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm pt-2 border-t border-gray-50">
                    <span class="text-gray-500">Estimasi Profit:</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($item->harga_jual - $item->hpp_terakhir, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center">
            <a href="{{ route('produk.show', $item->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">Kelola Resep & HPP →</a>
            <div class="space-x-3 flex items-center">
                <a href="{{ route('produk.edit', $item->id) }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">Edit</a>
                <form action="{{ route('produk.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus produk ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center bg-white rounded-lg border border-gray-100">
        <p class="text-gray-500">Belum ada produk yang ditambahkan.</p>
    </div>
    @endforelse
</div>
@endsection
