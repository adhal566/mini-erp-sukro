@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('header', 'Riwayat Transaksi')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
        <h3 class="font-semibold text-gray-800">Daftar Transaksi Kasir</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">Nomor Invoice</th>
                    <th class="px-6 py-4">Tanggal & Waktu</th>
                    <th class="px-6 py-4">Kasir</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Total Penjualan</th>
                    <th class="px-6 py-4">Total Profit</th>
                    <th class="px-6 py-4">Item Terjual</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transaksi as $t)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-bold text-blue-600">{{ $t->nomor_invoice }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $t->tanggal_transaksi->format('d M Y, H:i') }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $t->user->name ?? 'Kasir' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $t->metode_pembayaran == 'qris' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }} font-bold">
                            {{ strtoupper($t->metode_pembayaran) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-800">Rp {{ number_format($t->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($t->detail->sum('subtotal_profit'), 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <ul class="list-disc list-inside text-xs text-gray-500">
                            @foreach($t->detail as $dt)
                                <li>{{ $dt->produk->nama_produk ?? 'Produk Dihapus' }} ({{ $dt->kuantitas }}x)</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada transaksi tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">
        {{ $transaksi->links() }}
    </div>
</div>
@endsection
