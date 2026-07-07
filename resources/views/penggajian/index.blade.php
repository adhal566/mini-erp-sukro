@extends('layouts.app')

@section('title', 'Payroll & Penggajian')
@section('header', 'HR - Payroll & Penggajian')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Generate Payroll -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-4">Generate Slip Gaji</h3>
            
            @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm border border-green-200">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('penggajian.generate') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Karyawan</label>
                    <select name="user_id" id="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="periode_bulan" class="block text-sm font-medium text-gray-700 mb-1">Periode Bulan</label>
                    <input type="month" name="periode_bulan" id="periode_bulan" required value="{{ date('Y-m') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white outline-none">
                    <p class="text-xs text-gray-400 mt-1">Format: Tahun-Bulan (Contoh: 2026-06)</p>
                </div>
                
                <button type="submit" class="w-full py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">Hitung & Generate Gaji</button>
            </form>
        </div>
        
        <div class="mt-6 bg-blue-50 rounded-lg p-4 border border-blue-100">
            <h4 class="font-bold text-blue-800 text-sm mb-2">ℹ️ Info Kalkulasi Otomatis</h4>
            <ul class="text-xs text-blue-700 space-y-1 list-disc list-inside">
                <li>Berdasarkan hari kehadiran (Hadir).</li>
                <li>Standar hari kerja = 26 hari.</li>
                <li>Gaji Pokok Default: Rp 100.000/hari.</li>
                <li>Potongan Alpa Default: Rp 50.000/hari.</li>
            </ul>
        </div>
    </div>
    
    <!-- Riwayat Payroll -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Riwayat Slip Gaji</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Periode</th>
                            <th class="px-6 py-4">Karyawan</th>
                            <th class="px-6 py-4">Kehadiran</th>
                            <th class="px-6 py-4">Potongan</th>
                            <th class="px-6 py-4">Gaji Bersih</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($penggajian as $p)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ \Carbon\Carbon::parse($p->periode_bulan)->format('F Y') }}</td>
                            <td class="px-6 py-4">{{ $p->user->name }}</td>
                            <td class="px-6 py-4 font-bold text-blue-600">{{ $p->total_kehadiran }} Hari</td>
                            <td class="px-6 py-4 text-red-500 font-medium">- Rp {{ number_format($p->total_potongan, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 font-bold text-green-600">Rp {{ number_format($p->total_gaji_bersih, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $p->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} font-bold uppercase">
                                    {{ str_replace('_', ' ', $p->status_pembayaran) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Belum ada data penggajian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $penggajian->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
