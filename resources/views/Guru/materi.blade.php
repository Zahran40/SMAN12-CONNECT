@extends('layouts.guru.app')

@section('content')
    
    <h2 class="text-2xl sm:text-3xl font-bold text-blue-500 mb-4 sm:mb-6">Materi Kelas Saya</h2>

    


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">

        @forelse($jadwalMengajar as $group)
            @php
                $jadwal = $group->first();
            @endphp
            <div class="bg-white rounded-2xl p-5 sm:p-6 flex flex-col items-center text-center shadow-md border-2 border-transparent hover:border-blue-300 transition-all h-full">
                
                <div class="h-20 sm:h-24 flex flex-col justify-center items-center mb-4 w-full">
                    <h3 class="font-bold text-lg sm:text-xl text-blue-500 leading-snug line-clamp-2" title="{{ $jadwal->mataPelajaran->nama_mapel }}">
                        {{ $jadwal->mataPelajaran->nama_mapel }}
                    </h3>
                    <p class="text-xs sm:text-sm font-medium text-blue-400 mt-1">{{ $jadwal->kelas->nama_kelas }}</p>
                </div>
                
                <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full bg-yellow-400 flex items-center justify-center mb-6 shadow-inner shrink-0 my-auto">
                    <img src="{{ asset('images/Book.png') }}" class="w-14 h-14 sm:w-16 sm:h-16 object-contain" alt="Ikon Buku">
                </div>

                <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="w-full mt-auto bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl text-center hover:bg-blue-600 transition-colors flex items-center justify-center space-x-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <span>Lihat Materi</span>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-slate-500 text-lg">Belum ada jadwal mengajar</p>
            </div>
        @endforelse

    </div>
@endsection


