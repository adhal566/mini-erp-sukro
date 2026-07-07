@extends('layouts.app')

@section('title', 'Absensi Harian')
@section('header', 'HR - Absensi Harian')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Area Clock In -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 flex flex-col items-center text-center">
            <h3 class="font-bold text-gray-800 text-lg mb-2">Presensi Hari Ini</h3>
            <p class="text-gray-500 mb-6">{{ now()->format('l, d F Y') }}</p>
            
            @if($hariIni)
                <div class="w-48 h-48 rounded-full overflow-hidden border-4 border-green-500 mb-4 bg-gray-100">
                    <img src="{{ asset('storage/' . $hariIni->foto_selfie_url) }}" alt="Selfie Absen" class="w-full h-full object-cover">
                </div>
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg font-bold text-lg mb-2">
                    SUDAH ABSEN
                </div>
                <p class="text-sm text-gray-600">Jam Masuk: <span class="font-bold">{{ $hariIni->jam_masuk }}</span></p>
            @else
                <div x-data="absensiCamera()" class="w-full">
                    <div x-show="!streamActive" class="w-full h-48 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center mb-4 cursor-pointer hover:bg-gray-50 transition" @click="startCamera()">
                        <div class="text-gray-400">
                            <span class="block text-3xl mb-2">📷</span>
                            <span class="text-sm font-medium">Buka Kamera</span>
                        </div>
                    </div>
                    
                    <div x-show="streamActive" class="relative w-full h-48 bg-black rounded-lg overflow-hidden mb-4">
                        <video x-ref="video" class="w-full h-full object-cover" autoplay playsinline></video>
                        <div class="absolute inset-0 flex items-end justify-center pb-2">
                            <button @click="takePhotoAndClockIn()" :disabled="isProcessing" class="w-12 h-12 bg-white rounded-full border-4 border-gray-300 flex items-center justify-center shadow-lg hover:bg-gray-100 focus:outline-none">
                                <div class="w-8 h-8 bg-blue-500 rounded-full" x-show="!isProcessing"></div>
                                <span x-show="isProcessing" class="text-xs font-bold text-blue-500">Wait</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 p-3 rounded-lg text-sm text-blue-800 text-left mb-4 flex items-start">
                        <span class="mr-2 mt-0.5">📍</span>
                        <p x-text="locationText">Mencari lokasi GPS...</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Riwayat Absensi -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="font-semibold text-gray-800">Riwayat Kehadiran (Semua Karyawan)</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Karyawan</th>
                            <th class="px-6 py-4">Jam Masuk</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Lokasi (Lat, Lng)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($absensi as $a)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $a->tanggal->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">{{ $a->user->name }}</td>
                            <td class="px-6 py-4 font-bold text-blue-600">{{ $a->jam_masuk }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $a->status == 'hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} font-bold uppercase">
                                    {{ $a->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500 font-mono">
                                {{ $a->lokasi_lat }}, {{ $a->lokasi_lng }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">Belum ada riwayat absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $absensi->links() }}
            </div>
        </div>
    </div>
</div>

@if(!$hariIni)
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('absensiCamera', () => ({
            streamActive: false,
            videoStream: null,
            locationText: 'Mencari lokasi GPS...',
            latitude: null,
            longitude: null,
            isProcessing: false,
            
            init() {
                // Get Location automatically on init
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.locationText = `Lokasi Ditemukan: ${this.latitude.toFixed(4)}, ${this.longitude.toFixed(4)}`;
                        },
                        (error) => {
                            this.locationText = 'Gagal dapat lokasi asli. Menggunakan GPS simulasi (-6.20, 106.81)';
                            this.latitude = -6.200000;
                            this.longitude = 106.816666;
                            console.error('Error GPS:', error);
                        }
                    );
                } else {
                    this.locationText = 'Browser tidak mendukung Geolocation.';
                }
            },
            
            async startCamera() {
                try {
                    this.videoStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                    this.$refs.video.srcObject = this.videoStream;
                    this.streamActive = true;
                } catch (error) {
                    alert('Gagal mengakses kamera. Pastikan browser diizinkan mengakses kamera.');
                    console.error('Kamera Error:', error);
                }
            },
            
            async takePhotoAndClockIn() {
                if (!this.latitude || !this.longitude) {
                    alert('Lokasi GPS belum ditemukan. Harap tunggu atau izinkan akses lokasi.');
                    return;
                }

                this.isProcessing = true;
                
                // Create canvas to capture frame
                const canvas = document.createElement('canvas');
                canvas.width = this.$refs.video.videoWidth;
                canvas.height = this.$refs.video.videoHeight;
                canvas.getContext('2d').drawImage(this.$refs.video, 0, 0);
                
                const photoBase64 = canvas.toDataURL('image/png');
                
                // Stop camera
                if(this.videoStream) {
                    this.videoStream.getTracks().forEach(track => track.stop());
                }
                
                // Send to backend
                try {
                    const response = await fetch('{{ route('absensi.clock_in') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            lokasi_lat: this.latitude,
                            lokasi_lng: this.longitude,
                            photo: photoBase64
                        })
                    });

                    const result = await response.json();
                    
                    if(result.success) {
                        alert('Berhasil Clock-In!');
                        window.location.reload();
                    } else {
                        alert('Gagal: ' + result.message);
                        this.isProcessing = false;
                        this.startCamera();
                    }
                } catch (error) {
                    console.error('API Error:', error);
                    alert('Terjadi kesalahan sistem.');
                    this.isProcessing = false;
                    this.startCamera();
                }
            }
        }));
    });
</script>
@endif
@endsection
