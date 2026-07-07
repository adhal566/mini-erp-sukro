<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;

class PenggajianController extends Controller
{
    public function index()
    {
        $penggajian = Penggajian::with('user')->orderBy('periode_bulan', 'desc')->paginate(15);
        $karyawan = User::where('role', '!=', 'admin')->get();
        
        return view('penggajian.index', compact('penggajian', 'karyawan'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'periode_bulan' => 'required|date_format:Y-m'
        ]);

        $user = User::find($request->user_id);
        $periode = $request->periode_bulan; // format 'YYYY-MM'
        $year = explode('-', $periode)[0];
        $month = explode('-', $periode)[1];

        // Hitung total hadir
        $totalHadir = Absensi::where('user_id', $user->id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('status', 'hadir')
            ->count();

        // Asumsi hari kerja standar sebulan = 26 hari (Senin-Sabtu)
        $hariKerjaStandar = 26;
        $totalAlpa = max(0, $hariKerjaStandar - $totalHadir);

        // Kalkulasi Payroll
        $gajiPokok = $user->gaji_pokok_harian ?? 100000;
        $potonganAlpa = $user->potongan_alpa_harian ?? 50000;

        $totalGajiHarian = $gajiPokok * $totalHadir;
        $totalPotongan = $potonganAlpa * $totalAlpa;
        
        $totalGajiBersih = max(0, $totalGajiHarian - $totalPotongan);

        // Update or Create data Penggajian
        Penggajian::updateOrCreate(
            [
                'user_id' => $user->id,
                'periode_bulan' => $periode . '-01' // Simpan sebagai format date
            ],
            [
                'total_kehadiran' => $totalHadir,
                'total_potongan' => $totalPotongan,
                'total_gaji_bersih' => $totalGajiBersih,
                'status_pembayaran' => 'belum_dibayar'
            ]
        );

        return redirect()->route('penggajian.index')->with('success', 'Slip Gaji berhasil di-generate.');
    }
}
