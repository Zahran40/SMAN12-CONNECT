@extends('layouts.siswa.app')

@section('title', 'Beranda')

@section('content')

    <h2 class="text-3xl font-bold text-slate-800 mb-6">Beranda</h2>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center space-x-4">
        <div class="rounded-full overflow-hidden w-16 h-16 ring-4 ring-blue-100">
            <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">Nama Siswa</h3>
            <p class="text-sm text-slate-500">NISN: 0123456789</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">Kelas 11</span>
        </div>
    </div>

    <section class="mb-8">
        <h3 class="text-xl font-semibold text-slate-800 mb-4">Jadwal Mata Pelajaran</h3>
        <div class="flex space-x-2 mb-4">
            <button class="px-8 py-10 rounded-lg bg-blue-400 hover:bg-blue-500 text-white text-sm font-medium">Senin</button>
            <button class="px-8 py-10 rounded-lg bg-white text-slate-700 text-sm font-medium border border-slate-300 hover:bg-slate-50">Selasa</button>
            <button class="px-8 py-10 rounded-lg bg-white text-slate-700 text-sm font-medium border border-slate-300 hover:bg-slate-50">Rabu</button>
            <button class="px-8 py-10 rounded-lg bg-white text-slate-700 text-sm font-medium border border-slate-300 hover:bg-slate-50">Kamis</button>
            <button class="px-8 py-10 rounded-lg bg-white text-slate-700 text-sm font-medium border border-slate-300 hover:bg-slate-50">Jumat</button>
            <button class="px-8 py-10 rounded-lg bg-white text-slate-700 text-sm font-medium border border-slate-300 hover:bg-slate-50">Sabtu</button>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="space-y-4">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h4 class="font-semibold text-slate-800">Mata Pelajaran 1</h4>
                        <p class="text-sm text-slate-500">Nama Guru</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>08:00 - 9:30</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-0">
                    <div>
                        <h4 class="font-semibold text-slate-800">Mata Pelajaran 2</h4>
                        <p class="text-sm text-slate-500">Nama Guru</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span>09:30 - 10:30</span>
                    </div>
                </div>
                <div class="text-right">
                    <a href="#" class="text-blue-400 font-medium text-sm tracking-widest hover:underline">...</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h3 class="text-xl font-semibold text-slate-800 mb-4">Presensi Berlangsung</h3>
        <div class="bg-white rounded-xl shadow-lg p-6 space-y-5">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-slate-800">Mata Pelajaran 1</h4>
                    <p class="text-sm text-slate-500">Nama Guru</p>
                </div>
                <div class="flex items-center space-x-2 text-sm text-blue-400 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>08:00 - 9:30</span>
                </div>
                <button class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">
                    Presensi
                </button>
            </div>
            
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-slate-800">Mata Pelajaran 2</h4>
                    <p class="text-sm text-slate-500">Nama Guru</p>
                </div>
                <div class="flex items-center space-x-2 text-sm text-blue-400 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>09:30 - 10:30</span>
                </div>
                <button class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">
                    Presensi
                </button>
            </div>

        </div>
    </section>

@endsection