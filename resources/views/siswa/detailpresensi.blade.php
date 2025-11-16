@extends('layouts.siswa.app')

@section('content')

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
                    @if($pertemuan->waktu_absen_dibuka && $pertemuan->waktu_absen_ditutup)
                        {{ \Carbon\Carbon::parse($pertemuan->waktu_absen_dibuka)->format('H:i') }} - 
                        {{ \Carbon\Carbon::parse($pertemuan->waktu_absen_ditutup)->format('H:i') }}
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
            <div class="flex items-center justify-center bg-green-50 border-2 border-green-300 rounded-xl p-8">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-green-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-green-700 mb-2">✓ Sudah Absen</h3>
                    <p class="text-sm text-green-600">
                        Dicatat pada {{ \Carbon\Carbon::parse($absensi->dicatat_pada)->format('H:i, d/m/Y') }}
                    </p>
                    <p class="text-xs text-slate-500 mt-2">
                        Status kehadiran Anda sedang diproses oleh guru
                    </p>
                </div>
            </div>
        @else
            <!-- Belum Absen -->
            <div class="text-center">
                @php
                    $now = \Carbon\Carbon::now();
                    $isOpen = $pertemuan->waktu_absen_dibuka && $pertemuan->waktu_absen_ditutup 
                        && $now->greaterThanOrEqualTo($pertemuan->waktu_absen_dibuka) 
                        && $now->lessThanOrEqualTo($pertemuan->waktu_absen_ditutup);
                @endphp

                @if($isOpen)
                    <div class="w-20 h-20 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Absensi Dibuka</h3>
                    <p class="text-slate-600 mb-6">Klik tombol di bawah untuk melakukan absensi</p>
                    
                    <form action="{{ route('siswa.absen', $pertemuan->id_pertemuan) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-600 text-white text-lg font-semibold px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl">
                            📝 Absen Sekarang (Hadir)
                        </button>
                    </form>
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


@endsection