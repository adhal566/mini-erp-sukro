<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with('user')->orderBy('tanggal', 'desc')->paginate(15);
        
        // Setup dummy user jika belum ada
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Karyawan Produksi',
                'email' => 'karyawan@sukro.com',
                'password' => bcrypt('password'),
                'role' => 'production',
                'gaji_pokok_harian' => 100000,
                'potongan_alpa_harian' => 50000
            ]);
        }
        
        // Cek apakah user sudah absen hari ini
        $hariIni = Absensi::where('user_id', $user->id)->where('tanggal', today())->first();
        
        return view('absensi.index', compact('absensi', 'hariIni', 'user'));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'lokasi_lat' => 'required',
            'lokasi_lng' => 'required',
            'photo' => 'required'
        ]);

        $user = User::first();

        // Simpan foto base64
        $image = $request->photo;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10) . '.png';
        Storage::disk('public')->put('absensi/' . $imageName, base64_decode($image));

        Absensi::updateOrCreate(
            ['user_id' => $user->id, 'tanggal' => today()],
            [
                'jam_masuk' => now()->format('H:i:s'),
                'lokasi_lat' => $request->lokasi_lat,
                'lokasi_lng' => $request->lokasi_lng,
                'foto_selfie_url' => 'absensi/' . $imageName,
                'status' => 'hadir'
            ]
        );

        return response()->json(['success' => true, 'message' => 'Berhasil Clock-In.']);
    }
}
