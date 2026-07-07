@extends('layouts.app')

@section('title', 'Detail & Resep Produk')
@section('header', 'Manajemen Produk')

@section('content')
<div class="mb-6 flex items-center">
    <a href="{{ route('produk.index') }}" class="text-gray-500 hover:text-blue-600 mr-4">← Kembali</a>
    <h3 class="text-lg font-semibold text-gray-800">Detail Produk: {{ $produk->nama_produk }}</h3>
</div>

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informasi Produk & HPP -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h4 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Harga</h4>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Harga Jual</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</p>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500">Total HPP Saat Ini</p>
                    <p class="text-xl font-bold text-red-600">Rp {{ number_format($hpp, 0, ',', '.') }}</p>
                </div>
                
                <div class="pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-500">Estimasi Keuntungan Bersih / Pcs</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($produk->harga_jual - $hpp, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">Margin: {{ $produk->harga_jual > 0 ? round((($produk->harga_jual - $hpp) / $produk->harga_jual) * 100, 1) : 0 }}%</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h4 class="text-md font-semibold text-gray-800 mb-4 border-b pb-2">Tambah Komponen Resep</h4>
            
            <form action="{{ route('produk.resep.store', $produk->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="bahan_baku_id" class="block text-sm font-medium text-gray-700 mb-1">Bahan Baku</label>
                    <select name="bahan_baku_id" id="bahan_baku_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                        <option value="">-- Pilih Bahan Baku --</option>
                        @foreach($bahanBakuList as $bahan)
                            <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }} ({{ $bahan->satuan }}) - Rp{{ number_format($bahan->harga_per_satuan,0,',','.') }}/{{ $bahan->satuan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="kuantitas_dibutuhkan" class="block text-sm font-medium text-gray-700 mb-1">Kuantitas (Sesuai Satuan Bahan)</label>
                    <input type="number" step="0.01" name="kuantitas_dibutuhkan" id="kuantitas_dibutuhkan" min="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none" placeholder="Misal: 0.5 atau 100">
                </div>
                
                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Tambahkan ke Resep</button>
            </form>
        </div>
    </div>
    
    <!-- Tabel Resep -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h4 class="text-md font-semibold text-gray-800">Komposisi Resep (BOM - Bill of Materials)</h4>
            </div>
            
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Bahan Baku</th>
                        <th class="px-6 py-3">Harga/Satuan</th>
                        <th class="px-6 py-3">Kebutuhan</th>
                        <th class="px-6 py-3">Subtotal HPP</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($produk->reseps as $resep)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $resep->bahanBaku->nama_bahan }}</td>
                        <td class="px-6 py-4 text-gray-600">Rp {{ number_format($resep->bahanBaku->harga_per_satuan, 0, ',', '.') }} / {{ $resep->bahanBaku->satuan }}</td>
                        <td class="px-6 py-4 text-gray-800 font-semibold">{{ $resep->kuantitas_dibutuhkan }} {{ $resep->bahanBaku->satuan }}</td>
                        <td class="px-6 py-4 text-red-600 font-medium">Rp {{ number_format($resep->bahanBaku->harga_per_satuan * $resep->kuantitas_dibutuhkan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('produk.resep.destroy', [$produk->id, $resep->id]) }}" method="POST" onsubmit="return confirm('Hapus komponen resep ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">Resep belum dikonfigurasi. Silakan tambah komponen bahan baku.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
