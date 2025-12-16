@extends('layouts.guru.app')

@section('content')

    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6 sm:mb-8">
         <a href="{{ route('guru.raport_siswa') }}" class="shrink-0 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" class="w-6 h-6 sm:w-8 sm:h-8" alt="Back Icon">
        </a>
        <h2 class="text-xl sm:text-3xl font-bold text-blue-500 leading-tight">
            {{ $jadwal->mataPelajaran->nama_mapel }} <span class="hidden sm:inline">-</span> <br class="sm:hidden"> {{ $jadwal->kelas->nama_kelas }}
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 md:p-8 border-2 border-blue-400">
        <div class="mb-6 sm:mb-8">
            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-1">Daftar Siswa Anda</h3>
            <p class="text-xs sm:text-sm text-slate-500">Klik ikon buku untuk mengisi nilai raport siswa</p>
        </div>

        <div>
            <div class="hidden sm:grid grid-cols-12 gap-4 px-6 mb-4 text-sm font-bold text-blue-600 uppercase tracking-wider">
                <div class="col-span-1">No</div>
                <div class="col-span-5">Nama</div>
                <div class="col-span-4">NIS</div>
                <div class="col-span-2 text-right">Detail Raport</div>
            </div>

            @if($siswaList && $siswaList->count() > 0)
                <div class="space-y-3">
                    @foreach($siswaList as $index => $siswa)
                        <div class="bg-slate-50 border border-blue-200 rounded-2xl p-4 sm:py-5 sm:px-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex sm:grid sm:grid-cols-12 items-center justify-between gap-3 sm:gap-4">
                                
                                <div class="flex items-center gap-3 sm:contents w-full sm:w-auto overflow-hidden">
                                    <div class="sm:col-span-1 shrink-0">
                                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-slate-800 font-bold rounded-lg text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col sm:contents min-w-0">
                                        <div class="sm:col-span-5 font-bold text-slate-800 text-sm sm:text-base truncate sm:whitespace-normal">
                                            {{ $siswa->nama_lengkap }}
                                        </div>
                                        <div class="sm:col-span-4 text-xs sm:text-base text-slate-500 sm:text-slate-800 font-medium sm:font-semibold mt-0.5 sm:mt-0">
                                            <span class="sm:hidden text-slate-400">NIS: </span>{{ $siswa->nis ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="sm:col-span-2 flex justify-end shrink-0 ml-2 sm:ml-0">
                                    <a href="{{ route('guru.detail_raport_siswa', [$jadwal->id_jadwal, $siswa->id_siswa]) }}" class="text-blue-600 hover:text-blue-800 p-1">
                                        <img src="{{ asset('images/solar_book-2-bold.png') }}" alt="Detail" class="w-7 h-7 sm:w-8 sm:h-8">
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 text-slate-500 border-2 border-dashed border-slate-200 rounded-xl">
                    <p>Tidak ada siswa di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>

@endsection