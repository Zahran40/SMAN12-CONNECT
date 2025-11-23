@extends('layouts.guru.app')

@section('content')
    
    <h2 class="text-3lg sm:xl font-bold text-blue-500 mb-4 sm:mb-6">Materi Kelas Saya</h2>

    


    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

        @forelse($jadwalMengajar as $group)
            @php
                $jadwal = $group->first();
            @endphp
            <div class="bg-white rounded-lg sm:xl p-4 sm:p-6 flex flex-col items-center text-center  shadow-md border-2 border-transparent hover:border-blue-300">
                
                <h3 class="font-bold text-xl text-blue-400 mb-1">{{ $jadwal->mataPelajaran->nama_mapel }}</h3>
                <p class="text-sm text-blue-400 mb-4">{{ $jadwal->kelas->nama_kelas }}</p>
                
                <div class="w-32 h-32 rounded-full bg-yellow-400 flex items-center justify-center mb-4 sm:mb-6 ">
                    <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-16 h-16 text-green-700">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.5-15h15a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 014.5 4.5z" />
                    </img>
                </div>

                <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="w-full bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg sm:xl text-center hover:bg-blue-600   flex items-center justify-center space-x-2">
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


