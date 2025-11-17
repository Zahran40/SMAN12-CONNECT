@extends('layouts.siswa.app')

@section('content')

<div class="max-w-6xl mx-auto px-4">

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('siswa.list_presensi', $pertemuan->jadwal->id_jadwal) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-blue-500">{{ $pertemuan->jadwal->mataPelajaran->nama_mapel }}</h2>
            <p class="text-sm text-slate-500 mt-1">Pertemuan {{ $pertemuan->nomor_pertemuan }} - {{ $pertemuan->jadwal->guru->nama_lengkap }}</p>
        </div>
    </div>

    <!-- Detail Pertemuan -->
    <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-slate-500 mb-1">Tanggal Pertemuan</p>
                <p class="text-lg font-bold text-slate-800">
                    {{ $pertemuan->tanggal_pertemuan ? \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('dddd, DD MMMM Y') : '-' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-slate-500 mb-1">Topik Bahasan</p>
                <p class="text-lg font-bold text-slate-800">{{ $pertemuan->topik_bahasan ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-slate-500 mb-1">Waktu Absensi</p>
                <p class="text-lg font-bold text-slate-800">
                    @if($pertemuan->jam_absen_buka && $pertemuan->jam_absen_tutup)
                        {{ substr($pertemuan->jam_absen_buka, 0, 5) }} - 
                        {{ \Carbon\Carbon::parse($pertemuan->jam_absen_tutup)->format('H:i') }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Status Absensi -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h3 class="text-2xl font-bold text-slate-800 mb-6">Status Kehadiran Anda</h3>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6">
                {{ session('info') }}
            </div>
        @endif

        @if($absensi)
            <!-- Sudah Absen -->
            <div class="bg-green-50 border-2 border-green-300 rounded-xl p-8">
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto mb-4 bg-green-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-green-700 mb-2">✓ Sudah Absen</h3>
                    <p class="text-sm text-green-600">
                        Dicatat pada {{ \Carbon\Carbon::parse($absensi->dicatat_pada)->format('H:i, d/m/Y') }}
                    </p>
                </div>
                
                @if($absensi->latitude && $absensi->longitude)
                <!-- Informasi Lokasi -->
                <div class="bg-white border border-green-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start space-x-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-600 mt-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800 mb-2">📍 Lokasi Saat Absen:</p>
                            @if($absensi->alamat_lengkap)
                            <p class="text-sm text-slate-700 mb-2">{{ $absensi->alamat_lengkap }}</p>
                            @endif
                            <p class="text-xs text-slate-500">
                                Koordinat: {{ $absensi->latitude }}, {{ $absensi->longitude }}
                            </p>
                            <a href="https://www.google.com/maps?q={{ $absensi->latitude }},{{ $absensi->longitude }}" 
                               target="_blank" 
                               class="inline-block mt-3 bg-blue-600 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                🗺️ Lihat di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                
                <p class="text-xs text-slate-500 text-center">
                    Status kehadiran Anda sedang diproses oleh guru
                </p>
            </div>
        @else
            <!-- Belum Absen -->
            <div class="text-center">
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
                    <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Absensi Dibuka</h3>
                    <p class="text-slate-600 mb-4">Klik tombol di bawah untuk melakukan absensi</p>
                    
                    <!-- Lokasi GPS -->
                    <div id="location-info" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                        <div class="flex items-start space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600 mt-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            <div class="flex-1">
                                <p class="font-semibold text-slate-800 mb-1">📍 Lokasi Anda:</p>
                                <p id="location-status" class="text-sm text-slate-600">Mendeteksi lokasi...</p>
                                <p id="location-address" class="text-sm text-blue-700 font-medium mt-2"></p>
                                <p id="location-coords" class="text-xs text-slate-500 mt-1"></p>
                            </div>
                        </div>
                    </div>
                    
                    <form id="absensi-form" action="{{ route('siswa.absen', $pertemuan->id_pertemuan) }}" method="POST">
                        @csrf
                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">
                        <input type="hidden" id="alamat_lengkap" name="alamat_lengkap">
                        
                        <button type="submit" id="btn-absen" disabled class="bg-slate-400 text-white text-lg font-semibold px-8 py-3 rounded-lg cursor-not-allowed shadow-lg">
                            <span id="btn-text">⏳ Mendeteksi Lokasi...</span>
                        </button>
                    </form>
                    
                    <p class="text-xs text-slate-500 mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 inline mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                        </svg>
                        Pastikan GPS/Lokasi sudah diaktifkan
                    </p>
                @else
                    <div class="w-20 h-20 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Waktu Absensi Belum Dibuka</h3>
                    <p class="text-slate-600">Silakan tunggu guru membuka waktu absensi</p>
                @endif
            </div>
        @endif
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
                
                // Enable tombol absen
                btnAbsen.disabled = false;
                btnAbsen.className = 'bg-blue-600 text-white text-lg font-semibold px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl';
                btnText.textContent = '📝 Absen Sekarang (Hadir)';
            })
            .catch(error => {
                console.warn('Geocoding error:', error);
                // Tetap enable tombol meski geocoding gagal
                addressInput.value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                locationAddress.textContent = 'Alamat tidak tersedia (hanya koordinat tersimpan)';
                
                btnAbsen.disabled = false;
                btnAbsen.className = 'bg-blue-600 text-white text-lg font-semibold px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl';
                btnText.textContent = '📝 Absen Sekarang (Hadir)';
            });
    }
    
    // Request lokasi pengguna
    navigator.geolocation.getCurrentPosition(
        // Success callback
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Set nilai ke input hidden
            latInput.value = lat;
            lngInput.value = lng;
            
            // Update koordinat display
            locationCoords.textContent = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            locationStatus.textContent = '✓ Lokasi berhasil dideteksi';
            locationStatus.className = 'text-sm text-green-600 font-medium';
            
            // Reverse geocoding
            reverseGeocode(lat, lng);
        },
        // Error callback
        function(error) {
            let errorMsg = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = '❌ Izin akses lokasi ditolak. Silakan aktifkan GPS dan izinkan akses lokasi di browser Anda.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = '❌ Informasi lokasi tidak tersedia. Pastikan GPS aktif.';
                    break;
                case error.TIMEOUT:
                    errorMsg = '❌ Waktu permintaan lokasi habis. Coba refresh halaman.';
                    break;
                default:
                    errorMsg = '❌ Terjadi kesalahan saat mendeteksi lokasi.';
            }
            locationStatus.textContent = errorMsg;
            locationStatus.className = 'text-sm text-red-600';
            
            btnText.textContent = '❌ Lokasi Tidak Terdeteksi';
        },
        // Options
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
});
</script>

@endsection