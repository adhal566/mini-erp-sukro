@extends('layouts.app')

@section('title', 'Dashboard - MINI ERP')
@section('header', 'Dashboard Utama')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl">📦</div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total Produk</h3>
                <p class="text-2xl font-semibold text-gray-800">{{ \App\Models\Produk::count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xl">💰</div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Omset Hari Ini</h3>
                <p class="text-2xl font-semibold text-gray-800">Rp {{ number_format(\App\Models\Transaksi::whereDate('tanggal_transaksi', today())->sum('total_pembayaran'), 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 flex items-center">
            <div class="h-12 w-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xl">👥</div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Karyawan Hadir</h3>
                <p class="text-2xl font-semibold text-gray-800">{{ \App\Models\Absensi::where('tanggal', today())->where('status', 'hadir')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaksi Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3">No. Invoice</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Metode</th>
                        <th class="px-6 py-3">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Transaksi::orderBy('tanggal_transaksi', 'desc')->take(5)->get() as $t)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $t->nomor_invoice }}</td>
                        <td class="px-6 py-4">{{ $t->tanggal_transaksi->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full {{ $t->metode_pembayaran == 'qris' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                                {{ strtoupper($t->metode_pembayaran) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($t->total_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
