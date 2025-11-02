@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4">

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Nama Mata Pelajaran</h2>
    </div>

    <!-- Tabel kartu dalam satu wrapper, tidak terpecah -->
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <!-- Header -->
        <div class="grid grid-cols-12 gap-4 px-6 py-4 text-sm font-semibold text-blue-500 uppercase bg-slate-50">
            <div class="col-span-2">Pertemuan</div>
            <div class="col-span-4">Materi</div>
            <div class="col-span-3">Waktu Absensi</div>
            <div class="col-span-3 text-center">Status</div>
        </div>

        <!-- Rows -->
        <div class="divide-y divide-slate-200">
            <div class="grid grid-cols-12 items-center gap-4 px-6 py-4">
                <div class="col-span-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">1</div>
                </div>
                <div class="col-span-4 text-sm font-medium text-slate-800">Nama Materi</div>
                <div class="col-span-3 flex items-center space-x-2 text-sm text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                    </svg>
                    <span>08:00-9:30</span>
                </div>
                <div class="col-span-3 flex justify-center">
                    <span class="bg-white border border-blue-200 text-blue-700 text-xs font-semibold px-4 py-1.5 rounded-full">Hadir</span>
                </div>
            </div>

            <div class="grid grid-cols-12 items-center gap-4 px-6 py-4">
                <div class="col-span-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">2</div>
                </div>
                <div class="col-span-4 text-sm font-medium text-slate-800">Nama Materi</div>
                <div class="col-span-3 flex items-center space-x-2 text-sm text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                    </svg>
                    <span>08:00-9:30</span>
                </div>
                <div class="col-span-3 flex justify-center">
                    <span class="bg-white border border-blue-200 text-blue-700 text-xs font-semibold px-4 py-1.5 rounded-full">Hadir</span>
                </div>
            </div>

            <div class="grid grid-cols-12 items-center gap-4 px-6 py-4">
                <div class="col-span-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">3</div>
                </div>
                <div class="col-span-4 text-sm font-medium text-slate-800">Nama Materi</div>
                <div class="col-span-3 flex items-center space-x-2 text-sm text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                    </svg>
                    <span>08:00-9:30</span>
                </div>
                <div class="col-span-3 flex justify-center">
                    <span class="bg-white border border-blue-200 text-blue-700 text-xs font-semibold px-4 py-1.5 rounded-full">Hadir</span>
                </div>
            </div>

            <div class="grid grid-cols-12 items-center gap-4 px-6 py-4">
                <div class="col-span-2">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">4</div>
                </div>
                <div class="col-span-4 text-sm font-medium text-slate-800">Nama Materi</div>
                <div class="col-span-3 flex items-center space-x-2 text-sm text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-400">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                    </svg>
                    <span>08:00-9:30</span>
                </div>
                <div class="col-span-3 flex justify-center">
                    <button class="bg-blue-600 text-white text-xs font-semibold px-4 py-1.5 rounded-full hover:bg-blue-700 transition-colors">Presensi</button>
                </div>
            </div>
        </div>
    </div>

    </div>


@endsection