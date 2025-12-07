@extends('layouts.siswa.app')

@section('title', 'Presensi ' . $jadwal->nama_mapel)

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&libraries=places"></script>
@endpush

@section('content')

<div class="flex items-center space-x-4 mb-6 sm:mb-8">
    <a href="{{ route('siswa.presensi') }}" class="w-10 h-10 flex items-center justify-center bg-blue-50 text-blue-600 rounded-full hover:bg-blue-100 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </a>
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ $jadwal->nama_mapel }}</h2>
        <p class="text-sm text-slate-500">{{ $jadwal->nama_guru }} • {{ $jadwal->nama_kelas }}</p>
    </div>
</div>

@if($pertemuanAktif->count() > 0)
    <div class="space-y-4">
        @foreach($pertemuanAktif as $pertemuan)
            <div class="bg-white rounded-xl shadow-sm border border-blue-400 p-5 hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xl">
                            {{ $pertemuan->nomor_pertemuan }}
                        </div>
                        
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-lg font-bold text-slate-800">Pertemuan {{ $pertemuan->nomor_pertemuan }}</h3>
                                @if($pertemuan->status_kehadiran === 'Hadir')
                                    <span class="px-2.5 py-0.5 bg-green-100 text-green-700 text-xs font-semibold rounded-md">
                                        Sudah Absen
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 text-xs font-semibold rounded-md">
                                        Belum Absen
                                    </span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-slate-500 space-y-0.5">
                                <p>{{ \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('dddd, DD MMMM Y') }}</p>
                                @if($pertemuan->topik_bahasan)
                                    <p class="text-slate-400">{{ $pertemuan->topik_bahasan }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 mt-2 text-xs font-semibold text-slate-700">
                                <span>
                                    Buka: {{ $pertemuan->jam_absen_buka ? substr($pertemuan->jam_absen_buka, 0, 5) : '-' }}
                                </span>
                                <span>
                                    Tutup: {{ $pertemuan->jam_absen_tutup ? substr($pertemuan->jam_absen_tutup, 0, 5) : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex-shrink-0 w-full md:w-auto">
                        @php
                            $now = \Carbon\Carbon::now();
                            $waktuBuka = null;
                            $waktuTutup = null;
                            
                            if ($pertemuan->tanggal_absen_dibuka && $pertemuan->jam_absen_buka) {
                                $waktuBuka = \Carbon\Carbon::parse($pertemuan->tanggal_absen_dibuka . ' ' . $pertemuan->jam_absen_buka);
                            }
                            if ($pertemuan->tanggal_absen_ditutup && $pertemuan->jam_absen_tutup) {
                                $waktuTutup = \Carbon\Carbon::parse($pertemuan->tanggal_absen_ditutup . ' ' . $pertemuan->jam_absen_tutup);
                            }
                            
                            $isOpen = $waktuBuka && $waktuTutup 
                                && $now->greaterThanOrEqualTo($waktuBuka) 
                                && $now->lessThanOrEqualTo($waktuTutup);
                            $isBeforeOpen = $waktuBuka && $now->lessThan($waktuBuka);
                            $isClosed = $waktuTutup && $now->greaterThan($waktuTutup);
                        @endphp

                        @if($pertemuan->status_kehadiran)
                            @php
                                $statusColors = [
                                    'Hadir' => 'bg-green-50 border-green-200 text-green-700',
                                    'Sakit' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                                    'Izin' => 'bg-blue-50 border-blue-200 text-blue-700',
                                    'Alfa' => 'bg-red-50 border-red-200 text-red-700',
                                ];
                                $colorClass = $statusColors[$pertemuan->status_kehadiran] ?? 'bg-slate-50 border-slate-200 text-slate-700';
                            @endphp
                            <div class="text-right md:text-center px-4 py-3 rounded-lg border {{ $colorClass }}">
                                <p class="text-xs font-medium mb-1">Status Kehadiran</p>
                                <p class="text-sm font-bold mb-1">{{ $pertemuan->status_kehadiran }}</p>
                                @if($pertemuan->keterangan)
                                    <p class="text-xs italic mt-2">"{{ $pertemuan->keterangan }}"</p>
                                @endif
                                <p class="text-xs mt-2 opacity-75">
                                    {{ \Carbon\Carbon::parse($pertemuan->dicatat_pada)->format('H:i, d M Y') }}
                                </p>
                            </div>
                        @elseif($isOpen)
                            <button onclick="openAbsensiModal({{ $pertemuan->id_pertemuan }})"
                               class="block w-full md:w-48 py-3 bg-blue-500 text-white text-center text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm shadow-blue-200">
                                Isi Absensi
                            </button>
                        @elseif($isBeforeOpen)
                            <button disabled class="block w-full md:w-48 py-3 bg-slate-100 text-slate-400 text-center text-sm font-bold rounded-lg cursor-not-allowed">
                                Belum Dibuka
                            </button>
                        @elseif($isClosed)
                            <button disabled class="block w-full md:w-48 py-3 bg-red-50 text-red-400 text-center text-sm font-bold rounded-lg cursor-not-allowed border border-red-100">
                                Sudah Ditutup
                            </button>
                        @else
                            <button disabled class="block w-full md:w-48 py-3 bg-slate-100 text-slate-400 text-center text-sm font-bold rounded-lg cursor-not-allowed">
                                Menunggu
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada pertemuan</h3>
        <p class="text-slate-500 mb-4 sm:mb-6">Daftar pertemuan akan muncul di sini.</p>
        <a href="{{ route('siswa.presensi') }}" class="inline-block px-6 py-2.5 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 transition-colors">
            Kembali
        </a>
    </div>
@endif

<!-- Modal Absensi -->
<div id="modal-absensi" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        
        <!-- Header Modal -->
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0">
            <h1 class="text-lg font-bold text-blue-600">Presensi Kelas</h1>
            <button onclick="closeAbsensiModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content Modal -->
        <div id="modal-content" class="p-6 space-y-4">
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-4 text-slate-500">Memuat data...</p>
            </div>
        </div>

        <!-- Footer Modal -->
        <div id="modal-footer" class="p-6 pt-2 flex justify-end space-x-3 bg-white border-t border-slate-100">
            <!-- Buttons will be dynamically added here -->
        </div>
    </div>
</div>

<script>
let currentPertemuanId = null;
let locationData = {
    latitude: null,
    longitude: null,
    alamat_lengkap: null
};

function openAbsensiModal(pertemuanId) {
    currentPertemuanId = pertemuanId;
    const modal = document.getElementById('modal-absensi');
    modal.classList.remove('hidden');
    
    // Reset location data
    locationData = { latitude: null, longitude: null, alamat_lengkap: null };
    
    // Load detail pertemuan via AJAX (GPS detection akan dipanggil setelah content siap)
    loadPertemuanDetail(pertemuanId);
}

function closeAbsensiModal() {
    const modal = document.getElementById('modal-absensi');
    modal.classList.add('hidden');
    currentPertemuanId = null;
}

async function loadPertemuanDetail(pertemuanId) {
    try {
        const response = await fetch(`/siswa/pertemuan/${pertemuanId}/detail`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) throw new Error('Failed to load');
        
        const data = await response.json();
        displayPertemuanDetail(data);
        
    } catch (error) {
        console.error('Error loading pertemuan:', error);
        document.getElementById('modal-content').innerHTML = `
            <div class="text-center py-8 text-red-600">
                <p>Gagal memuat data. Silakan coba lagi.</p>
            </div>
        `;
    }
}

function displayPertemuanDetail(data) {
    const content = document.getElementById('modal-content');
    const footer = document.getElementById('modal-footer');
    
    content.innerHTML = `
        <div class="space-y-3 text-sm">
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Mata Pelajaran</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-800 font-semibold">${data.mapel}</div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Kelas</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-800 font-semibold">${data.kelas}</div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Guru Pengampu</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-800">${data.guru}</div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Deskripsi</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-800">
                    Pertemuan ${data.nomor_pertemuan}<br>
                    <span class="text-slate-500 text-xs">${data.topik || '-'}</span>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Tanggal</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-800">${data.tanggal}</div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Jam Presensi</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-800">${data.jam_presensi}</div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Jenis Presensi</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Mandiri
                    </span>
                </div>
            </div>
            
            <div class="flex items-start">
                <div class="w-32 text-slate-500 font-medium shrink-0">Lokasi Anda</div>
                <div class="w-4 text-slate-500 text-center shrink-0">:</div>
                <div class="flex-1 text-slate-700 text-sm leading-relaxed">
                    <span id="location-status" class="text-slate-400 italic">Mendeteksi lokasi...</span>
                    <p id="location-address" class="mt-1"></p>
                    <p id="location-coords" class="text-xs text-slate-400 mt-1 font-mono"></p>
                </div>
            </div>
        </div>
    `;
    
    footer.innerHTML = `
        <button onclick="closeAbsensiModal()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
            Batal
        </button>
        <button id="btn-submit-absen" onclick="submitAbsensi()" disabled class="px-6 py-2 rounded-lg bg-blue-300 text-white font-bold shadow-md cursor-not-allowed transition-all">
            <span id="btn-text">Loading GPS...</span>
        </button>
    `;
    
    // Setelah content siap, baru jalankan GPS detection
    detectLocation();
}

function detectLocation() {
    const locationStatus = document.getElementById('location-status');
    const locationAddress = document.getElementById('location-address');
    const locationCoords = document.getElementById('location-coords');
    const btnSubmit = document.getElementById('btn-submit-absen');
    const btnText = document.getElementById('btn-text');
    
    if (!navigator.geolocation) {
        if (locationStatus) locationStatus.textContent = '❌ Browser tidak mendukung GPS';
        return;
    }
    
    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            const accuracy = position.coords.accuracy; // dalam meter
            
            console.log('GPS Position:', { lat, lng, accuracy: accuracy + 'm' });
            
            locationData.latitude = lat;
            locationData.longitude = lng;
            
            if (locationCoords) locationCoords.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            if (locationStatus) locationStatus.textContent = '';
            
            // Reverse geocoding menggunakan Google Maps JavaScript API Geocoder
            const geocoder = new google.maps.Geocoder();
            const latlng = { lat: lat, lng: lng };
            
            console.log('Geocoding position:', latlng);
            
            geocoder.geocode({ location: latlng, language: 'id' }, (results, status) => {
                console.log('Google Maps Geocoder Response:', { status, results });
                
                if (status === 'OK' && results && results.length > 0) {
                    locationData.alamat_lengkap = results[0].formatted_address;
                    if (locationAddress) locationAddress.textContent = results[0].formatted_address;
                } else {
                    console.warn('Geocoding failed. Status:', status);
                    locationData.alamat_lengkap = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                    if (locationAddress) locationAddress.textContent = 'Alamat tidak tersedia';
                }
                
                // Enable button
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.className = 'px-6 py-2 rounded-lg bg-blue-600 text-white font-bold shadow-md hover:bg-blue-700 transition-all';
                }
                if (btnText) btnText.textContent = 'Hadir';
            });
        },
        function(error) {
            let errorMsg = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    errorMsg = '❌ Izin akses lokasi ditolak';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorMsg = '❌ Lokasi tidak tersedia';
                    break;
                case error.TIMEOUT:
                    errorMsg = '❌ Waktu deteksi habis';
                    break;
                default:
                    errorMsg = '❌ Error GPS';
            }
            if (locationStatus) {
                locationStatus.textContent = errorMsg;
                locationStatus.className = 'text-sm text-red-600';
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

async function submitAbsensi() {
    if (!currentPertemuanId || !locationData.latitude || !locationData.longitude) {
        alert('Data lokasi belum tersedia. Mohon tunggu.');
        return;
    }
    
    const btnSubmit = document.getElementById('btn-submit-absen');
    const btnText = document.getElementById('btn-text');
    
    btnSubmit.disabled = true;
    btnText.textContent = 'Menyimpan...';
    
    try {
        const response = await fetch(`/siswa/presensi/${currentPertemuanId}/absen`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                latitude: locationData.latitude,
                longitude: locationData.longitude,
                alamat_lengkap: locationData.alamat_lengkap
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeAbsensiModal();
            // Reload halaman untuk update status
            window.location.reload();
        } else {
            alert('❌ ' + (data.message || 'Gagal menyimpan presensi'));
            btnSubmit.disabled = false;
            btnText.textContent = 'Hadir';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('❌ Terjadi kesalahan. Silakan coba lagi.');
        btnSubmit.disabled = false;
        btnText.textContent = 'Hadir';
    }
}

// Close modal when clicking outside
document.getElementById('modal-absensi')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAbsensiModal();
    }
});
</script>

@endsection

