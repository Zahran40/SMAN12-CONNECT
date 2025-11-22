@extends('layouts.siswa.app')

@section('title', 'Presensi Kehadiran')

@section('content')

    <div class="mb-6">
        <h2 class="text-3xl font-bold text-blue-600">Presensi Kehadiran</h2>
    </div>

    @if($jadwalList && count($jadwalList) > 0)
        <div class="flex flex-col space-y-4">
            @foreach($jadwalList as $jadwal)
                <a href="{{ route('siswa.list_presensi', $jadwal->id_jadwal) }}" 
                   class="block w-full bg-white border border-blue-400 rounded-xl p-5 hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md group">
                    
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col space-y-1">
                            <h3 class="text-xl font-bold text-slate-800 group-hover:text-blue-700 transition-colors">
                                {{ $jadwal->nama_mapel }}
                            </h3>
                            
                            <div class="flex items-center text-sm text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1.5 text-slate-400">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                                </svg>
                                {{ $jadwal->nama_guru }}
                            </div>

                            <div class="mt-2 flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $jadwal->hari }} • {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <span class="text-sm font-semibold text-blue-600 mr-2 group-hover:underline hidden md:inline-block">Lihat Presensi</span>
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-blue-600 group-hover:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Jadwal</h3>
            <p class="text-slate-500 text-sm">Jadwal mata pelajaran belum tersedia saat ini.</p>
        </div>
    @endif

@endsection
