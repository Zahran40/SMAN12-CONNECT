@extends('layouts.siswa.app')

@section('title', 'Beranda')

@section('content')

    <!-- ALERT TUNGGAKAN SPP -->
    @if(isset($tunggakan) && $tunggakan && $tunggakan->jumlah_tunggakan > 0)
        @if($tunggakan->jumlah_tunggakan >= 3)
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-lg" role="alert">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="font-bold text-base sm:text-lg">⚠️ Akses Terbatas - Tunggakan SPP</p>
                    <p class="text-sm sm:text-base mt-1">Anda memiliki <strong>{{ $tunggakan->jumlah_tunggakan }} bulan</strong> tunggakan SPP senilai <strong>Rp {{ number_format($tunggakan->total_tunggakan, 0, ',', '.') }}</strong></p>
                    <p class="text-sm mt-2">Akses menu lain dibatasi. Segera lakukan pembayaran atau hubungi bagian keuangan.</p>
                </div>
            </div>
        </div>
        @elseif($tunggakan->jumlah_tunggakan >= 1)
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg mb-6 shadow" role="alert">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Peringatan Pembayaran SPP</p>
                    <p class="text-sm mt-1">Anda memiliki {{ $tunggakan->jumlah_tunggakan }} bulan tunggakan SPP. Segera lakukan pembayaran.</p>
                </div>
            </div>
        </div>
        @endif
    @endif

    <h2 class="text-2xl sm:text-3xl font-bold text-blue-500 mb-4 sm:mb-6">Beranda</h2>

    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 flex items-center space-x-3 sm:space-x-4">
        <div class="rounded-full overflow-hidden w-10 h-10 sm:w-12 sm:h-12 sm:w-16 sm:h-16 ring-4 ring-blue-100 shrink-0">
            @if($siswa && $siswa->foto_profil)
                <img src="{{ asset('storage/' . $siswa->foto_profil) }}" alt="Foto Siswa" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base sm:text-xl font-bold text-blue-600 truncate">{{ $siswa->nama_lengkap ?? 'Nama Siswa' }}</h3>
            <p class="text-xs sm:text-sm text-slate-500">NISN: {{ $siswa->nisn ?? '-' }}</p>
            @if($kelasNama)
                <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">{{ $kelasNama }}</span>
            @else
                <span class="inline-block bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full mt-2">Belum Ada Kelas</span>
            @endif
        </div>
    </div>

    <section class="mb-6 sm:mb-8">
        <h3 class="text-lg sm:text-xl font-semibold text-blue-600 mb-3 sm:mb-4">Jadwal Mata Pelajaran</h3>
        <div class="flex overflow-x-auto space-x-2 mb-4 pb-2 scrollbar-hide">
            @foreach($allDays as $day)
                <button onclick="switchDay('{{ $day }}')" 
                        class="day-tab px-4 sm:px-6 md:px-8 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium whitespace-nowrap {{ $day == $hariIni ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50' }}"
                        data-day="{{ $day }}">
                    {{ $day }}
                </button>
            @endforeach
        </div>
        
        @foreach($allDays as $day)
            <div id="jadwal-{{ $day }}" class="day-schedule bg-white rounded-xl shadow-lg p-4 sm:p-6 {{ $day != $hariIni ? 'hidden' : '' }}">
                @if($jadwalPerHari[$day]->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($jadwalPerHari[$day] as $jadwal)
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between {{ !$loop->last ? 'pb-3 sm:pb-4 border-b border-slate-100' : '' }}">
                                <div class="mb-2 sm:mb-0">
                                    <h4 class="font-semibold text-sm sm:text-base text-slate-800">{{ $jadwal->nama_mapel }}</h4>
                                    <p class="text-xs sm:text-sm text-slate-500">{{ $jadwal->nama_guru }}</p>
                                </div>
                                <div class="flex items-center space-x-2 text-xs sm:text-sm text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-3 opacity-50">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <p class="font-medium">Tidak ada jadwal pelajaran</p>
                        <p class="text-sm mt-1">Tidak ada mata pelajaran pada hari {{ $day }}</p>
                    </div>
                @endif
            </div>
        @endforeach
        
        <script>
            function switchDay(day) {
                // Hide all schedules
                document.querySelectorAll('.day-schedule').forEach(el => el.classList.add('hidden'));
                
                // Show selected day schedule
                document.getElementById('jadwal-' + day).classList.remove('hidden');
                
                // Update button styles
                document.querySelectorAll('.day-tab').forEach(btn => {
                    if (btn.dataset.day === day) {
                        btn.classList.remove('bg-white', 'text-slate-700', 'border', 'border-slate-300', 'hover:bg-slate-50');
                        btn.classList.add('bg-blue-400', 'text-white');
                    } else {
                        btn.classList.remove('bg-blue-400', 'text-white');
                        btn.classList.add('bg-white', 'text-slate-700', 'border', 'border-slate-300', 'hover:bg-slate-50');
                    }
                });
            }
        </script>
    </section>

    <section>
        <h3 class="text-lg sm:text-xl font-semibold text-blue-600 mb-3 sm:mb-4">Presensi Berlangsung</h3>
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
            @if($presensiAktif && $presensiAktif->count() > 0)
                <div class="space-y-4 sm:space-y-5">
                    @foreach($presensiAktif as $pertemuan)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between {{ !$loop->last ? 'pb-4 sm:pb-5 border-b border-slate-100' : '' }} gap-3">
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm sm:text-base text-slate-800">{{ $pertemuan->nama_mapel }}</h4>
                                <p class="text-xs sm:text-sm text-slate-500">{{ $pertemuan->nama_guru }}</p>
                                <p class="text-xs text-slate-600 mt-1 flex items-start gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 shrink-0 mt-0.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0 Z" />
                                    </svg>
                                    <span>Waktu: {{ $pertemuan->jam_absen_buka ? substr($pertemuan->jam_absen_buka, 0, 5) : '-' }} - 
                                    {{ $pertemuan->jam_absen_tutup ? substr($pertemuan->jam_absen_tutup, 0, 5) : '-' }}</span>
                                </p>
                            </div>
                            <div class="text-left sm:text-center shrink-0">
                                @if($pertemuan->sudah_absen)
                                    <span class="inline-block bg-green-100 text-green-700 text-xs sm:text-sm font-medium px-4 sm:px-5 py-2 rounded-full">
                                        ✓ Sudah Absen
                                    </span>
                                @else
                                    <form action="{{ route('siswa.absen', $pertemuan->id_pertemuan) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto bg-blue-400 text-white text-xs sm:text-sm font-medium px-4 sm:px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">
                                            Presensi Sekarang
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-3 opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <p class="font-medium">Tidak ada presensi aktif saat ini</p>
                    <p class="text-sm mt-1">Presensi akan muncul saat guru membuka waktu absensi</p>
                </div>
            @endif
        </div>
    </section>

        </div>
    </section>

@endsection
