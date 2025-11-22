@extends('layouts.siswa.app')

@section('title', 'Materi Pembelajaran')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-6">Materi Kelas</h2>

    @if(session('success'))
        <div class="alert-auto-hide bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse($jadwalPelajaran as $jadwal)
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow border-2 border-transparent hover:border-blue-300">
            <h4 class="font-bold text-xl text-blue-600 mb-2">{{ $jadwal->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</h4>
            <p class="text-sm text-slate-500 mb-4">{{ $jadwal->kelas->nama_kelas ?? '' }}</p>
            
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6 shadow-md">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            
            <a href="{{ route('siswa.detail_materi', $jadwal->id_jadwal) }}" class="w-full bg-blue-500 text-white font-semibold py-3 px-6 rounded-xl hover:bg-blue-600  shadow-md hover:shadow-lg flex items-center justify-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </svg>
                <span>Buka Materi</span>
            </a>
        </div>
        @empty
        <div class="col-span-full bg-white rounded-xl shadow-lg p-8 text-center">
            <p class="text-slate-500">Belum ada jadwal pelajaran untuk kelas Anda</p>
        </div>
        @endforelse

    </div>

@endsection