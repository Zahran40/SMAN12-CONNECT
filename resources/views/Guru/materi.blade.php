@extends('layouts.guru.app')

@section('content')
    
    <h2 class="text-3xl font-bold text-slate-800 mb-6">Materi Kelas Saya</h2>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
            <p class="font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

        @forelse($jadwalMengajar as $group)
            @php
                $jadwal = $group->first();
            @endphp
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center">
                
                <h3 class="font-semibold text-lg text-slate-800">{{ $jadwal->mataPelajaran->nama_mapel }}</h3>
                <p class="text-sm text-slate-500">{{ $jadwal->kelas->nama_kelas }}</p>
                
                <div class="w-32 h-32 rounded-full bg-yellow-400 flex items-center justify-center my-6">
                    <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-16 h-16 text-green-700">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.5-15h15a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75A2.25 2.25 0 014.5 4.5z" />
                    </img>
                </div>

                <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="w-full max-w-xs bg-blue-400 text-white font-medium py-2 rounded-lg text-center hover:bg-blue-500 transition-colors">
                    Lihat
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-slate-500 text-lg">Belum ada jadwal mengajar</p>
            </div>
        @endforelse

    </div>
@endsection