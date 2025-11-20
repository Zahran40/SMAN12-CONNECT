@extends('layouts.siswa.app')

@section('title', 'Presensi ' . $jadwal->nama_mapel)

@section('content')

<div class="flex items-center space-x-4 mb-8">
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
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold text-xl">
                            {{ $pertemuan->nomor_pertemuan }}
                        </div>
                        
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h3 class="text-lg font-bold text-slate-800">Pertemuan {{ $pertemuan->nomor_pertemuan }}</h3>
                                @if($pertemuan->status_kehadiran)
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
                            <div class="text-right md:text-center px-4 py-2 bg-slate-50 rounded-lg border border-slate-100">
                                <p class="text-xs text-slate-400 mb-1">Dicatat pada</p>
                                <p class="text-sm font-bold text-slate-700">
                                    {{ \Carbon\Carbon::parse($pertemuan->dicatat_pada)->format('H:i') }}
                                </p>
                            </div>
                        @elseif($isOpen)
                            <a href="{{ route('siswa.detail_presensi', $pertemuan->id_pertemuan) }}"
                               class="block w-full md:w-48 py-3 bg-blue-500 text-white text-center text-sm font-bold rounded-lg hover:bg-blue-600 transition-colors shadow-sm shadow-blue-200">
                                Isi Absensi
                            </a>
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
        <p class="text-slate-500 mb-6">Daftar pertemuan akan muncul di sini.</p>
        <a href="{{ route('siswa.presensi') }}" class="inline-block px-6 py-2.5 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 transition-colors">
            Kembali
        </a>
    </div>
@endif

@endsection