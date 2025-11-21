@extends('layouts.siswa.app')

@section('title', 'Beranda')

@section('content')

    <h2 class="text-3xl font-bold text-slate-800 mb-6">Beranda</h2>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center space-x-4">
        <div class="rounded-full overflow-hidden w-16 h-16 ring-4 ring-blue-100">
            @if($siswa && $siswa->foto_profil)
                <img src="{{ asset('storage/' . $siswa->foto_profil) }}" alt="Foto Siswa" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
            @endif
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">{{ $siswa->nama_lengkap ?? 'Nama Siswa' }}</h3>
            <p class="text-sm text-slate-500">NISN: {{ $siswa->nisn ?? '-' }}</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">Kelas 11</span>
        </div>
    </div>

    <section class="mb-8">
        <h3 class="text-xl font-semibold text-slate-800 mb-4">Jadwal Mata Pelajaran</h3>
        <div class="flex space-x-2 mb-4">
            @foreach($allDays as $day)
                <button onclick="switchDay('{{ $day }}')" 
                        class="day-tab px-8 py-10 rounded-lg text-sm font-medium {{ $day == $hariIni ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50' }}"
                        data-day="{{ $day }}">
                    {{ $day }}
                </button>
            @endforeach
        </div>
        
        @foreach($allDays as $day)
            <div id="jadwal-{{ $day }}" class="day-schedule bg-white rounded-xl shadow-lg p-6 {{ $day != $hariIni ? 'hidden' : '' }}">
                @if($jadwalPerHari[$day]->count() > 0)
                    <div class="space-y-4">
                        @foreach($jadwalPerHari[$day] as $jadwal)
                            <div class="flex items-center justify-between {{ !$loop->last ? 'pb-4 border-b border-slate-100' : '' }}">
                                <div>
                                    <h4 class="font-semibold text-slate-800">{{ $jadwal->nama_mapel }}</h4>
                                    <p class="text-sm text-slate-500">{{ $jadwal->nama_guru }}</p>
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-slate-500">
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
        <h3 class="text-xl font-semibold text-slate-800 mb-4">Presensi Berlangsung</h3>
        <div class="bg-white rounded-xl shadow-lg p-6">
            @if($presensiAktif && $presensiAktif->count() > 0)
                <div class="space-y-5">
                    @foreach($presensiAktif as $pertemuan)
                        <div class="flex items-center justify-between {{ !$loop->last ? 'pb-5 border-b border-slate-100' : '' }}">
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $pertemuan->nama_mapel }}</h4>
                                <p class="text-sm text-slate-500">{{ $pertemuan->nama_guru }}</p>
                                <p class="text-xs text-slate-600 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 inline">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Waktu Absen: {{ $pertemuan->jam_absen_buka ? substr($pertemuan->jam_absen_buka, 0, 5) : '-' }} - 
                                    {{ $pertemuan->jam_absen_tutup ? substr($pertemuan->jam_absen_tutup, 0, 5) : '-' }}
                                </p>
                            </div>
                            <div class="text-center">
                                @if($pertemuan->sudah_absen)
                                    <span class="bg-green-100 text-green-700 text-sm font-medium px-5 py-2 rounded-full">
                                        ✓ Sudah Absen
                                    </span>
                                @else
                                    <form action="{{ route('siswa.absen', $pertemuan->id_pertemuan) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">
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