@extends('layouts.admin.app')

@section('title', 'Arsip Tahun Ajaran')

@section('content')
<div class="content-wrapper p-4 sm:p-6">

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4 mb-4 sm:mb-6">
        <img src="{{ asset('images/clarity_administrator-solid.png') }}" alt="Operator Icon" class="w-16 h-16">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Operator</h2>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <div>
            <h1 class="text-xl font-bold text-blue-700">Arsip Tahun Ajaran</h1>
            <p class="text-sm text-gray-600 mt-1">Tahun ajaran yang telah diarsipkan</p>
        </div>
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center space-x-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            {{ session('error') }}
        </div>
    @endif

    @forelse($tahunAjaranArsip as $index => $ta)
    <div class="bg-gray-50 border-2 border-gray-300 rounded-2xl shadow p-5 {{ $index < count($tahunAjaranArsip) - 1 ? 'mb-6' : '' }}">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
                <h2 class="font-semibold text-gray-700 text-lg">Tahun Ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}</h2>
                <span class="text-xs bg-gray-400 text-white font-semibold px-3 py-1 rounded-full">Diarsipkan</span>
            </div>

            <form action="{{ route('admin.tahun-ajaran.unarchive-year', [$ta->tahun_mulai, $ta->tahun_selesai]) }}" method="POST" onsubmit="return confirm('Yakin ingin memulihkan tahun ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} dari arsip?')">
                @csrf
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Pulihkan
                </button>
            </form>
        </div>

        {{-- Info Semester --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            @if($ta->ganjil)
            <div class="border-2 border-gray-300 bg-gray-100 rounded-xl p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">📚 Semester Ganjil</h3>
                    <span class="text-xs bg-gray-400 text-white font-semibold px-3 py-1 rounded-full">{{ $ta->ganjil->status }}</span>
                </div>
                <p class="text-xs text-gray-600">Juli - Desember</p>
            </div>
            @endif

            @if($ta->genap)
            <div class="border-2 border-gray-300 bg-gray-100 rounded-xl p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">📖 Semester Genap</h3>
                    <span class="text-xs bg-gray-400 text-white font-semibold px-3 py-1 rounded-full">{{ $ta->genap->status }}</span>
                </div>
                <p class="text-xs text-gray-600">Januari - Juni</p>
            </div>
            @endif
        </div>

        {{-- Statistik --}}
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <h3 class="font-semibold text-gray-700 mb-3">📊 Data Tersimpan</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ $ta->jumlah_kelas }}</p>
                    <p class="text-xs text-gray-600">Kelas</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ $ta->jumlah_siswa }}</p>
                    <p class="text-xs text-gray-600">Siswa</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ $ta->jumlah_guru }}</p>
                    <p class="text-xs text-gray-600">Guru</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-700">{{ $ta->jumlah_mapel }}</p>
                    <p class="text-xs text-gray-600">Mapel</p>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Data kelas, siswa, dan raport masih tersimpan untuk keperluan history
            </p>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow p-8 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
        </svg>
        <p class="text-slate-500 text-lg">Tidak ada tahun ajaran yang diarsipkan</p>
        <p class="text-slate-400 text-sm mt-2">Tahun ajaran yang diarsipkan akan muncul di sini</p>
    </div>
    @endforelse
</div>
@endsection
