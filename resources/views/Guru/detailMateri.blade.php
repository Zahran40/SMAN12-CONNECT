@extends('layouts.guru.app')

@section('content')

    @if(session('success'))
        <div class="alert-auto-hide bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-auto-hide bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-3">
            <a href="{{ route('guru.materi') }}" class="text-blue-600 hover:text-blue-800" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-blue-600">{{ $jadwal->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }} - {{ $jadwal->kelas->nama_kelas ?? '' }}</h2>
        </div>
        
    <a href="{{ route('guru.upload_materi', $jadwal->id_jadwal) }}" class="flex items-center space-x-2 bg-blue-400 text-white font-semibold px-5 py-3 rounded-xl hover:bg-blue-500 transition-colors shadow-md shadow-blue-200">
            <!-- Ikon "Tambah" (Plus) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Buat Materi</span>
        </a>
    </div>

    <div class="space-y-6">
        @forelse($pertemuans as $pertemuan)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex justify-between items-center mb-6 cursor-pointer" data-pertemuan-toggle>
                <div class="flex items-center space-x-4">
                    <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 font-bold rounded-lg text-base">{{ $pertemuan->pertemuan_ke }}</span>
                    <h3 class="text-xl font-semibold text-slate-800">Pertemuan {{ $pertemuan->pertemuan_ke }}</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-slate-500 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
            
            <div class="space-y-4 pl-12 {{ $loop->first ? '' : 'hidden' }}">
                @forelse($pertemuan->materi as $m)
                <div>
                    <div class="border-2 border-blue-200 rounded-xl p-4 mb-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Icon Folder" class="w-6 h-6">
                                <span class="font-semibold text-slate-700">{{ $m->judul_materi }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, 'materi', $m->id_materi]) }}" class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</a>
                                <a href="{{ route('guru.download_materi', ['materi', $m->id_materi]) }}" class="bg-blue-400 text-white text-sm font-medium px-5 py-1 rounded-full hover:bg-blue-500 transition-colors">Download</a>
                            </div>
                        </div>
                    </div>
                    <div class="border-2 border-blue-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-slate-600 mb-1">Deskripsi Materi :</p>
                        <p class="text-sm text-slate-500 leading-relaxed">{{ $m->deskripsi_materi }}</p>
                    </div>
                </div>
                @empty
                @endforelse
                
                @forelse($pertemuan->tugas as $t)
                <div class="pt-4">
                    <div class="border-2 border-blue-200 rounded-xl p-4 mb-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/bxs_file.png') }}" alt="Icon File" class="w-6 h-6">
                                <span class="font-semibold text-slate-700">{{ $t->judul_tugas }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, 'tugas', $t->id_tugas]) }}" class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</a>
                                <a href="{{ route('guru.detail_tugas') }}" class="bg-blue-400 text-white text-sm font-medium px-9 py-1 rounded-full hover:bg-blue-500 transition-colors">Lihat</a>
                            </div>
                        </div>
                    </div>
                    <div class="border-2 border-blue-200 rounded-xl p-4 space-y-2">
                        <div>
                            <p class="text-sm font-medium text-slate-600 mb-1">Deskripsi Tugas :</p>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ $t->deskripsi_tugas }}</p>
                        </div>
                        @if($t->deadline)
                        <div>
                            <p class="text-xs font-medium text-slate-600">Deadline:</p>
                            <p class="text-xs text-red-600 font-semibold">{{ \Carbon\Carbon::parse($t->deadline)->translatedFormat('l, d F Y') }} - {{ \Carbon\Carbon::parse($t->jam_tutup)->format('H:i') }}</p>
                        </div>
                        @else
                        <p class="text-xs text-slate-400">Waktu: {{ \Carbon\Carbon::parse($t->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($t->jam_tutup)->format('H:i') }}</p>
                        @endif
                    </div>
                </div>
                @empty
                @endforelse

                @if($pertemuan->materi->isEmpty() && $pertemuan->tugas->isEmpty())
                <div class="border border-slate-200 rounded-lg p-4">
                    <p class="text-sm text-slate-500 text-center">Anda belum membuat materi pada pertemuan ini</p>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <p class="text-slate-500">Belum ada pertemuan untuk jadwal ini</p>
        </div>
        @endforelse
    </div>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection