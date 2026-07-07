@extends('layouts.app')

@section('title', 'Data Bahan Baku')
@section('header', 'Manajemen Bahan Baku')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800">Daftar Bahan Baku</h3>
    <a href="{{ route('bahan-baku.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow-sm hover:bg-blue-700 transition">
        + Tambah Bahan Baku
    </a>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
            <tr>
                <th class="px-6 py-4">Nama Bahan</th>
                <th class="px-6 py-4">Stok Saat Ini</th>
                <th class="px-6 py-4">Satuan</th>
                <th class="px-6 py-4">Harga per Satuan</th>
                <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bahanBaku as $item)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-gray-800 font-medium">{{ $item->nama_bahan }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md font-semibold">{{ $item->stok_saat_ini }}</span>
                </td>
                <td class="px-6 py-4 text-gray-500 uppercase text-xs font-semibold">{{ $item->satuan }}</td>
                <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item->harga_per_satuan, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('bahan-baku.edit', $item->id) }}" class="text-blue-500 hover:text-blue-700 font-medium">Edit</a>
                    <form action="{{ route('bahan-baku.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada data bahan baku.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
