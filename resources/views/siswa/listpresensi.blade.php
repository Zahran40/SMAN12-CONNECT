@extends('layouts.siswa.app')

@section('title', 'Presensi ' . $jadwal->nama_mapel)

@section('content')

<div class="flex items-center space-x-4 mb-6">
    <a href="{{ route('siswa.presensi') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
    </a>
    <div>
        <h2 class="text-3xl font-bold text-slate-800">{{ $jadwal->nama_mapel }}</h2>
        <p class="text-sm text-slate-500 mt-1">{{ $jadwal->nama_guru }} • {{ $jadwal->nama_kelas }}</p>
    </div>
</div>

@if($pertemuanAktif->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pertemuanAktif as $pertemuan)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border-2 {{ $pertemuan->status_kehadiran ? 'border-green-300' : 'border-blue-300' }} hover:shadow-xl transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-700 font-bold text-lg">
                            {{ $pertemuan->nomor_pertemuan }}
                        </span>
                        @if($pertemuan->status_kehadiran)
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                ✓ Sudah Absen
                            </span>
                        @else
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full animate-pulse">
                                ⚠ Belum Absen
                            </span>
                        @endif
                    </div>

                    <!-- Info -->
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Pertemuan {{ $pertemuan->nomor_pertemuan }}</h3>
                    
                    <div class="space-y-2 mb-4">
                        <div class="flex items-start text-sm">
                            <span class="text-slate-500 mr-2">📅</span>
                            <span class="text-slate-700">
                                {{ \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('dddd, DD MMMM Y') }}
                            </span>
                        </div>
                        
                        @if($pertemuan->topik_bahasan)
                            <div class="flex items-start text-sm">
                                <span class="text-slate-500 mr-2">📚</span>
                                <span class="text-slate-700">{{ $pertemuan->topik_bahasan }}</span>
                            </div>
                        @endif

                        <div class="flex items-start text-sm">
                            <span class="text-slate-500 mr-2">⏰</span>
                            <div class="text-slate-700">
                                <div class="text-green-600 font-medium">
                                    Buka: {{ $pertemuan->jam_absen_buka ? substr($pertemuan->jam_absen_buka, 0, 5) : '-' }}
                                </div>
                                <div class="text-red-600 font-medium">
                                    Tutup: {{ $pertemuan->jam_absen_tutup ? substr($pertemuan->jam_absen_tutup, 0, 5) : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Button -->
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
                        <!-- Sudah Absen -->
                        <div class="text-center">
                            <div class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-medium">
                                ✓ Sudah Absen
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ \Carbon\Carbon::parse($pertemuan->dicatat_pada)->format('H:i') }}
                            </p>
                        </div>
                    @elseif($isOpen)
                        <!-- Waktu Absen Terbuka -->
                        <a href="{{ route('siswa.detail_presensi', $pertemuan->id_pertemuan) }}"
                           class="block w-full py-3 bg-blue-500 text-white text-center font-semibold rounded-lg hover:bg-blue-600 transition-colors">
                            📝 Isi Absensi Sekarang
                        </a>
                    @elseif($isBeforeOpen)
                        <!-- Belum Dibuka -->
                        <button disabled
                                class="w-full py-3 bg-slate-100 text-slate-400 text-center font-semibold rounded-lg cursor-not-allowed">
                            🔒 Belum Dibuka
                        </button>
                        <p class="text-xs text-slate-500 mt-1 text-center">
                            Buka {{ $pertemuan->jam_absen_buka ? substr($pertemuan->jam_absen_buka, 0, 5) : '' }}
                        </p>
                    @elseif($isClosed)
                        <!-- Sudah Ditutup -->
                        <button disabled
                                class="w-full py-3 bg-red-100 text-red-500 text-center font-semibold rounded-lg cursor-not-allowed">
                            ⛔ Sudah Ditutup
                        </button>
                    @else
                        <!-- Waktu Belum Ditentukan -->
                        <button disabled
                                class="w-full py-3 bg-slate-100 text-slate-400 text-center font-semibold rounded-lg cursor-not-allowed">
                            ⏳ Menunggu Jadwal
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <div class="w-24 h-24 mx-auto mb-6 bg-slate-100 rounded-full flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">Tidak ada presensi aktif</h3>
        <p class="text-slate-500 mb-6">Presensi akan muncul saat guru membuka waktu absensi</p>
        <a href="{{ route('siswa.presensi') }}" class="inline-block px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-colors">
            Kembali ke Daftar Mata Pelajaran
        </a>
    </div>
@endif

@endsection
