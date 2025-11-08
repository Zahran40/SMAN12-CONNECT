@extends('layouts.guru.app')

@section('content')

    <h2 class="text-3xl font-bold text-slate-800 mb-6">Presensi Kelas</h2>

    <!-- Presensi Kelas Section -->
    <section class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-slate-800 mb-4">Presensi Mata Pelajaran Anda</h3>
        <p class="text-sm text-slate-500 mb-4">Pilih Mata Pelajaran anda untuk membuat presensi kehadiran</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h4 class="font-semibold text-slate-800 mb-2">Kelas 2A</h4>
                <p class="text-sm text-slate-500 mb-4">36 Siswa</p>
                <a href="#" class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">Pergi</a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h4 class="font-semibold text-slate-800 mb-2">Kelas 2B</h4>
                <p class="text-sm text-slate-500 mb-4">32 Siswa</p>
                <a href="#" class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">Pergi</a>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h4 class="font-semibold text-slate-800 mb-2">Kelas 3A</h4>
                <p class="text-sm text-slate-500 mb-4">40 Siswa</p>
                <a href="#" class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">Pergi</a>
            </div>
        </div>
    </section>

@endsection
