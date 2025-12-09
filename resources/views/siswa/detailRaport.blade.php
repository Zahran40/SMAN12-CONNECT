@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center justify-between mb-6 sm:mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('siswa.nilai', ['tahun_ajaran_id' => $tahunAjaranId ?? '']) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
                 <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </img>
            </a>
            <h2 class="text-3xl font-bold text-blue-500">Laporan Nilai Raport</h2>
        </div>
      
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

        <!-- Statistik Nilai -->
       <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <div class="relative overflow-hidden rounded-xl bg-green-50 bg-gradient-to-br from-green-500 to-green-700 shadow-md text-white p-4">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <p class="text-green-500 text-xs font-semibold tracking-wide uppercase">Kelas Saat Ini</p>
                        <h4 class="text-2xl font-bold text-green-500 mt-1">{{ $namaKelas ?? '-' }}</h4>
                        <div class="mt-2 inline-flex items-center bg-white bg-opacity-20 px-2 py-0.5 rounded text-[10px] font-medium border text-green-500 border-white border-opacity-10">
                            <span>TA: {{ $tahunAjaranLabel }}</span>
                        </div>
                    </div>
                    <div class="p-2 bg-green-500 bg-opacity-20 rounded-lg shadow-sm border border-white border-opacity-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="relative overflow-hidden rounded-xl bg-blue-50 bg-gradient-to-br from-blue-500 to-blue-700 shadow-md text-white p-4">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-white opacity-10 blur-xl"></div>

                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <p class="text-blue-500 text-xs font-semibold tracking-wide uppercase">Rata-rata Semester {{ $semester }}</p>
                        <h4 class="text-3xl font-bold text-blue-500 mt-1">{{ number_format($rataRata, 2) }}</h4>
                        <p class="text-[10px] text-blue-500 opacity-90 mt-1">
                            Semua Mata Pelajaran
                        </p>
                    </div>
                    <div class="p-2 bg-blue-500 bg-opacity-20 rounded-lg shadow-sm border border-white border-opacity-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="cetakRaport()" class="bg-emerald-500 hover:bg-emerald-700 text-white font-semibold px-4 py-2 sm:px-6 sm:py-3 rounded-lg shadow-lg transition-colors flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Raport</span>
            </button>
        </div>

        @else
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-slate-500 font-medium">Nilai untuk semester ini belum tersedia</p>
            <p class="text-slate-400 text-sm mt-2">Silakan pilih tahun ajaran atau semester lain</p>
        </div>
        @endif

    </div>

    <script>
        function cetakRaport() {
            window.print();
        }
    </script>

@endsection


