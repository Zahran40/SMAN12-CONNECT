@extends('layouts.admin.app')

@section('title', 'Data Master Mata Pelajaran')

@section('content')
    <div class="flex flex-col h-full space-y-4 sm:space-y-6">
        <div class="flex items-center space-x-4">
        <a href="{{ route('admin.data-master.index') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Detail Kelas</h1>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm flex justify-between items-center">
            <div class="flex items-center space-x-4 sm:space-x-6">
                <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-16 h-16">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $kelas->nama_kelas }}</h2>
                    <div class="flex items-center text-slate-500 text-sm mt-1 mb-2">
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                        </svg>
                        <span>{{ $kelas->siswa_count ?? 0 }} Siswa</span>
                    </div>
                    <span class="border border-yellow-400 text-yellow-600 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $kelas->tahunAjaran->tahun_mulai }}/{{ $kelas->tahunAjaran->tahun_selesai }}
                    </span>
                </div>
            </div>
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>Hapus</span>
            </button>
        </div>

        <div class="overflow-x-auto scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
            <div class="flex space-x-3 min-w-max">
                <a href="{{ route('admin.data-master.kelas.siswa', $kelas->id_kelas) }}" class="px-6 sm:px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors whitespace-nowrap">
                    Siswa
                </a>
                <a href="{{ route('admin.data-master.kelas.guru', $kelas->id_kelas) }}" class="px-6 sm:px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors whitespace-nowrap">
                    Guru
                </a>
                <a href="{{ route('admin.data-master.kelas.mapel', $kelas->id_kelas) }}" class="px-6 sm:px-8 py-2 bg-blue-400 text-white font-semibold rounded-full shadow-sm hover:bg-blue-500 transition-colors whitespace-nowrap">
                    Mata Pelajaran
                </a>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm grow">
            <h3 class="text-xl font-bold text-blue-600 mb-4 sm:mb-6">Daftar Mata Pelajaran</h3>

            <div class="hidden lg:grid grid-cols-12 gap-4 text-blue-600 font-semibold mb-4 px-6">
                <div class="col-span-1">No</div>
                <div class="col-span-4">Nama Mata Pelajaran</div>
                <div class="col-span-4">Nama Pengajar</div>
                <div class="col-span-3 text-right pr-12">Aksi</div>
            </div>

            <div class="space-y-4">
                @forelse($mapelList as $index => $mapel)
                <div class="border-2 border-blue-100 rounded-xl px-4 sm:px-6 py-4">
                    <!-- Mobile Layout -->
                    <div class="lg:hidden space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="font-bold text-slate-700 flex items-center justify-center bg-blue-100 w-8 h-8 rounded-lg shrink-0">{{ $index + 1 }}</div>
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $mapel->nama_mapel }}</div>
                                    <div class="text-slate-600 text-sm">{{ $mapel->guru_count }} Guru</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.akademik.mapel.show', $mapel->id_mapel) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-1 transition-colors text-sm w-full">
                            <span>Detail</span>
                        </a>
                    </div>
                    <!-- Desktop Layout -->
                    <div class="hidden lg:grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-1 font-bold text-slate-700 flex items-center justify-center bg-blue-100 w-8 h-8 rounded-lg">{{ $index + 1 }}</div>
                        <div class="col-span-4 font-semibold text-slate-800">{{ $mapel->nama_mapel }}</div>
                        <div class="col-span-4 text-slate-600 font-medium">{{ $mapel->guru_count }} Guru</div>
                        <div class="col-span-3 text-right">
                            <a href="{{ route('admin.akademik.mapel.show', $mapel->id_mapel) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg inline-flex items-center space-x-1 transition-colors text-sm">
                                <span>Detail</span>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-500">
                    Belum ada mata pelajaran
                </div>
                @endforelse
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('admin.akademik.mapel.create') }}" class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 sm:px-6 sm:py-3 rounded-full font-bold flex items-center justify-center space-x-2 shadow-lg transition-all hover:shadow-xl w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tambah Mapel</span>
            </button>
        </div>

    </div>
@endsection

