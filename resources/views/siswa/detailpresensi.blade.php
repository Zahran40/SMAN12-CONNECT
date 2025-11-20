@extends('layouts.siswa.app')

@section('content')

<div class="min-h-screen flex items-center justify-center py-10 px-4 bg-slate-50">
    <div class="w-full max-w-lg bg-white rounded-xl shadow-2xl overflow-hidden border border-slate-100 relative">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white">
            <h1 class="text-lg font-bold text-blue-400">Presensi Kelas</h1>
            <a href="{{ route('siswa.list_presensi', $pertemuan->jadwal->id_jadwal) }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </a>
        </div>

        <div class="p-6 space-y-4">

            @if(session('success'))
                <div class="mb-4 bg-green-50 text-green-700 px-4 py-2 rounded text-sm border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-50 text-red-700 px-4 py-2 rounded text-sm border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-3 text-sm md:text-base">
                
                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Mata Pelajaran</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-800 font-semibold">
                        {{ $pertemuan->jadwal->mataPelajaran->nama_mapel }}
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Kelas</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-800 font-semibold">
                        {{ $pertemuan->jadwal->kelas->nama_kelas }}
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Guru Pengampu</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-800">
                        {{ $pertemuan->jadwal->guru->nama_lengkap }}
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Deskripsi</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-800">
                        Pertemuan {{ $pertemuan->nomor_pertemuan }} <br>
                        <span class="text-slate-500 text-xs">{{ $pertemuan->topik_bahasan ?? '-' }}</span>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Tanggal</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-800">
                        {{ $pertemuan->tanggal_pertemuan ? \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('DD MMMM Y') : '-' }}
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Jam Presensi</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-800">
                        @if($pertemuan->jam_absen_buka && $pertemuan->jam_absen_tutup)
                            {{ substr($pertemuan->jam_absen_buka, 0, 5) }} - 
                            {{ \Carbon\Carbon::parse($pertemuan->jam_absen_tutup)->format('H:i') }}
                        @else
                            -
                        @endif
                    </div>
                </div>

                 <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Jenis Presensi</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                            Mandiri
                        </span>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="w-32 text-slate-500 font-medium shrink-0">Lokasi Anda</div>
                    <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                    <div class="flex-1 text-slate-700 text-sm leading-relaxed">
                        @if($absensi)
                            {{ $absensi->alamat_lengkap ?? 'Koordinat: ' . $absensi->latitude . ', ' . $absensi->longitude }}
                            <br>
                            <a href="https://www.google.com/maps?q={{ $absensi->latitude }},{{ $absensi->longitude }}" target="_blank" class="text-blue-600 text-xs hover:underline mt-1 inline-block">Lihat Peta</a>
                        @else
                            <span id="location-status" class="text-slate-400 italic">Mendeteksi lokasi...</span>
                            <p id="location-address" class="mt-1"></p>
                            <p id="location-coords" class="text-xs text-slate-400 mt-1 font-mono"></p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <div class="p-6 pt-2 flex justify-end space-x-3 bg-white">
            <a href="{{ route('siswa.list_presensi', $pertemuan->jadwal->id_jadwal) }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                Batal
            </a>

            @if($absensi)
                <button disabled class="px-6 py-2 rounded-lg bg-slate-100 text-slate-500 font-medium cursor-not-allowed flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Sudah Hadir
                </button>
            @else
                @php
                    $now = \Carbon\Carbon::now();
                    $waktuBuka = null;
                    $waktuTutup = null;
                    
                    if ($pertemuan->tanggal_absen_dibuka && $pertemuan->jam_absen_buka) {
                        $waktuBuka = \Carbon\Carbon::parse($pertemuan->tanggal_absen_dibuka)->setTimeFromTimeString($pertemuan->jam_absen_buka);
                    }
                    if ($pertemuan->tanggal_absen_ditutup && $pertemuan->jam_absen_tutup) {
                        $waktuTutup = \Carbon\Carbon::parse($pertemuan->tanggal_absen_ditutup)->setTimeFromTimeString($pertemuan->jam_absen_tutup);
                    }
                    
                    $isOpen = $waktuBuka && $waktuTutup 
                        && $now->greaterThanOrEqualTo($waktuBuka) 
                        && $now->lessThanOrEqualTo($waktuTutup);
                @endphp

                @if($isOpen)
                    <form id="absensi-form" action="{{ route('siswa.absen', $pertemuan->id_pertemuan) }}" method="POST">
                        @csrf
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                        <input type="hidden" id="alamat_lengkap" name="alamat_lengkap">
                        
                        <button type="submit" id="btn-absen" disabled class="px-6 py-2 rounded-lg bg-blue-300 text-white font-bold shadow-md cursor-not-allowed transition-all">
                            <span id="btn-text">Loading GPS...</span>
                        </button>
                    </form>
                @else
                    <button disabled class="px-6 py-2 rounded-lg bg-slate-200 text-slate-500 font-medium cursor-not-allowed">
                        Absen Ditutup
                    </button>
                @endif
            @endif
        </div>
        
        <div class="bg-slate-50 px-6 py-3 text-center border-t border-slate-100">
             <p class="text-xs text-slate-400">SMA NEGERI 12 MEDAN &copy; 2025</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationStatus = document.getElementById('location-status');
    const locationAddress = document.getElementById('location-address');
    const locationCoords = document.getElementById('location-coords');
    const btnAbsen = document.getElementById('btn-absen');
    const btnText = document.getElementById('btn-text');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const addressInput = document.getElementById('alamat_lengkap');
    
    // Cek apakah semua elemen ada (halaman absensi terbuka)
    if (!locationStatus || !btnAbsen || !latInput) {
        console.log('GPS tracking not initialized: form elements not found');
        return; // Form belum muncul (mungkin sudah absen atau belum dibuka)
    }
    
    // Cek apakah browser support geolocation
    if (!navigator.geolocation) {
        locationStatus.textContent = '❌ Browser Anda tidak mendukung GPS';
        locationStatus.className = 'text-sm text-red-600';
        return;
    }
    
    // Function untuk reverse geocoding menggunakan Nominatim (Open Street Map - GRATIS)
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    const address = data.display_name;
                    addressInput.value = address;
                    locationAddress.textContent = address;
                } else {
                    // Fallback: koordinat saja
                    addressInput.value = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                    locationAddress.textContent = 'Alamat tidak dapat dideteksi, tetapi koordinat tersimpan';
                }
                
                // Enable tombol absen - STYLE BUTTON UPDATE DISINI
                btnAbsen.disabled = false;
                // Ubah warna tombol jadi Biru Tua (hover lebih gelap) saat aktif
                btnAbsen.className = 'px-6 py-2 rounded-lg bg-blue-600 text-white font-bold shadow-md hover:bg-blue-700 hover:shadow-lg transition-all transform active:scale-95';
                btnText.textContent = 'Hadir'; // Text simpel sesuai screenshot
            })
            .catch(error => {
                console.warn('Geocoding error:', error);
                // Tetap enable tombol meski geocoding gagal
                addressInput.value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                locationAddress.textContent = 'Alamat tidak tersedia (hanya koordinat)';
                
                btnAbsen.disabled = false;
                btnAbsen.className = 'px-6 py-2 rounded-lg bg-blue-600 text-white font-bold shadow-md hover:bg-blue-700 transition-all';
                btnText.textContent = 'Hadir';
            });
    }
    
    // Request lokasi pengguna
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            latInput.value = lat;
            lngInput.value = lng;
            
            locationCoords.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            locationStatus.textContent = ''; // Hilangkan text "Mendeteksi..."
            
            reverseGeocode(lat, lng);
        },
        function(error) {
            let errorMsg = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = '❌ Izin akses lokasi ditolak.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = '❌ Lokasi tidak tersedia.';
                    break;
                case error.TIMEOUT:
                    errorMsg = '❌ Waktu habis.';
                    break;
                default:
                    errorMsg = '❌ Error GPS.';
            }
            locationStatus.textContent = errorMsg;
            locationStatus.className = 'text-sm text-red-600 italic';
            btnText.textContent = 'Lokasi Gagal';
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
});
</script>

@endsection