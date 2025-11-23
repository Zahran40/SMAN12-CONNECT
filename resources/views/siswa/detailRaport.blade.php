@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6 sm:mb-8">
        <a href="{{ route('siswa.nilai') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
             <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Laporan Nilai Raport</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-2 border-blue-200 p-4 sm:p-6">
        
        <div class="mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Tahun Ajaran {{ $tahunAjaranLabel }} - Semester {{ $semester }}</h3>
        </div>

        @if($raports->count() > 0)
        <div class="grid grid-cols-12 gap-4 mb-4 px-2">
            <div class="col-span-1 text-sm font-semibold text-blue-700">No</div>
            <div class="col-span-7 text-sm font-semibold text-blue-700">Nama Mata Pelajaran</div>
            <div class="col-span-2 text-sm font-semibold text-blue-700">Nilai</div>
            <div class="col-span-2 text-sm font-semibold text-blue-700">Grade</div>
        </div>

        <div class="space-y-4">

        @foreach($raports as $index => $raport)
            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        {{ $index + 1 }}
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">{{ $raport->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">{{ number_format($raport->nilai_akhir, 2) }}</div>
                <div class="col-span-2">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold 
                        @if($raport->grade == 'A') bg-green-100 text-green-800
                        @elseif($raport->grade == 'B') bg-blue-100 text-blue-800
                        @elseif($raport->grade == 'C') bg-yellow-100 text-yellow-800
                        @elseif($raport->grade == 'D') bg-orange-100 text-orange-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $raport->grade }}
                    </span>
                </div>
            </div>
        @endforeach

        </div>

        <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <div class="flex justify-between items-center">
                <h4 class="font-semibold text-slate-800">Rata-rata Nilai:</h4>
                <p class="text-2xl font-bold text-blue-700">{{ number_format($rataRata, 2) }}</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 sm:px-6 sm:py-3 rounded-lg shadow-lg transition-colors flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Raport</span>
            </button>
        </div>

        @else
        <div class="text-center py-12">
            <p class="text-slate-500">Nilai untuk semester ini belum tersedia</p>
        </div>
        @endif

    </div>

@endsection


