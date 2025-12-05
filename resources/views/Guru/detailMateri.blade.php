@extends('layouts.guru.app')

@section('content')

    <div class="flex flex-col gap-4 mb-6 sm:mb-8">
        <div class="flex items-start sm:items-center gap-3">
            <a href="{{ route('guru.materi') }}" class="text-blue-600 hover:text-blue-800 shrink-0 mt-1 sm:mt-0" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 sm:w-8 sm:h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-lg sm:text-2xl md:text-3xl font-bold text-blue-600 leading-tight">
                {{ $jadwal->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }} - {{ $jadwal->kelas->nama_kelas ?? '' }}
            </h2>
        </div>
        
        <div class="grid grid-cols-2 sm:flex sm:flex-row gap-3 w-full sm:w-auto sm:ml-auto">
            <a href="{{ route('guru.upload_materi', $jadwal->id_jadwal) }}" class="flex items-center justify-center space-x-2 bg-blue-400 text-white font-semibold px-3 py-2 sm:px-5 sm:py-3 rounded-xl hover:bg-blue-500 transition-colors shadow-md shadow-blue-200 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Materi</span>
            </a>
            <a href="{{ route('guru.upload_tugas', $jadwal->id_jadwal) }}" class="flex items-center justify-center space-x-2 bg-green-500 text-white font-semibold px-3 py-2 sm:px-5 sm:py-3 rounded-xl hover:bg-green-600 transition-colors shadow-md shadow-green-200 text-sm sm:text-base">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tugas</span>
            </a>
        </div>
    </div>

    <div class="mb-6 border-b border-slate-200 overflow-x-auto hide-scrollbar">
        <nav class="flex space-x-4 sm:space-x-8 min-w-max" role="tablist">
            <button type="button" class="tab-button py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-sm transition-colors active" data-tab="pertemuan" role="tab">
                <span class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Per Pertemuan</span>
                </span>
            </button>
            <button type="button" class="tab-button py-3 sm:py-4 px-2 sm:px-1 border-b-2 font-medium text-sm transition-colors" data-tab="semua" role="tab">
                <span class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                    <span>Semua Materi ({{ count($allMateriGuru) }})</span>
                </span>
            </button>
        </nav>
    </div>

    <div class="tab-content space-y-4 sm:space-y-6" id="tab-pertemuan">
        @forelse($pertemuans as $pertemuan)
        <div class="bg-white rounded-xl shadow-sm sm:shadow-md p-4 sm:p-6 border border-slate-200">
            <div class="flex justify-between items-center mb-4 sm:mb-6 cursor-pointer select-none" data-pertemuan-toggle>
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 text-blue-800 font-bold rounded-lg text-sm sm:text-base shrink-0">{{ $pertemuan->pertemuan_ke }}</span>
                    <h3 class="text-base sm:text-xl font-semibold text-slate-800">Pertemuan {{ $pertemuan->pertemuan_ke }}</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 text-slate-500 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
            
            <div class="space-y-4 pl-0 sm:pl-14 {{ $loop->first ? '' : 'hidden' }}">
                
                @forelse($pertemuan->materi as $m)
                <div>
                    <div class="border border-blue-200 rounded-xl p-3 sm:p-4 mb-2 bg-blue-50/30">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div class="flex items-start sm:items-center space-x-3">
                                <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Icon" class="w-6 h-6 sm:w-8 sm:h-8 shrink-0 mt-1 sm:mt-0">
                                <span class="font-semibold text-slate-700 text-sm sm:text-base break-words line-clamp-2">{{ $m->judul_materi }}</span>
                            </div>
                            <div class="flex items-center gap-2 w-full sm:w-auto mt-1 sm:mt-0">
                                <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, $m->id_materi]) }}" class="flex-1 sm:flex-none text-center bg-green-100 text-green-700 text-xs sm:text-sm font-medium px-4 py-2 rounded-lg hover:bg-green-200 transition-colors">Edit</a>
                                <a href="{{ route('guru.download_materi', ['materi', $m->id_materi]) }}" class="flex-1 sm:flex-none text-center bg-blue-400 text-white text-xs sm:text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors">Download</a>
                            </div>
                        </div>
                    </div>
                    <div class="border border-slate-100 bg-slate-50 rounded-xl p-3 sm:p-4">
                        <p class="text-xs font-medium text-slate-600 mb-1">Deskripsi Materi :</p>
                        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">{{ $m->deskripsi_materi }}</p>
                    </div>
                </div>
                @empty
                @endforelse
                
                @forelse($pertemuan->tugas as $t)
                <div class="pt-2 sm:pt-4">
                     <div class="border border-blue-200 rounded-xl p-3 sm:p-4 mb-2 bg-blue-50/30">
                        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div class="flex items-start sm:items-center space-x-3">
                                <img src="{{ asset('images/bxs_file.png') }}" alt="Icon" class="w-6 h-6 sm:w-8 sm:h-8 shrink-0 mt-1 sm:mt-0">
                                <span class="font-semibold text-slate-700 text-sm sm:text-base break-words line-clamp-2">{{ $t->judul_tugas }}</span>
                            </div>
                             <div class="w-full sm:w-auto mt-1 sm:mt-0">
                                <a href="{{ route('guru.detail_tugas', $t->id_tugas) }}" class="block w-full sm:w-auto text-center bg-blue-400 text-white text-xs sm:text-sm font-medium px-6 py-2 rounded-lg hover:bg-blue-500 transition-colors">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                    <div class="border border-slate-100 bg-slate-50 rounded-xl p-3 sm:p-4 space-y-2">
                        <div>
                            <p class="text-xs font-medium text-slate-600 mb-1">Deskripsi Tugas :</p>
                            <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">{{ $t->deskripsi }}</p>
                        </div>
                        @if($t->deadline)
                        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-slate-100 w-fit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <div>
                                <p class="text-[10px] sm:text-xs font-medium text-slate-600">Deadline</p>
                                <p class="text-[10px] sm:text-xs text-red-600 font-bold">{{ \Carbon\Carbon::parse($t->deadline)->translatedFormat('l, d F Y') }} - {{ \Carbon\Carbon::parse($t->waktu_ditutup)->format('H:i') }}</p>
                            </div>
                        </div>
                        @else
                        <p class="text-xs text-slate-400">Waktu: {{ \Carbon\Carbon::parse($t->waktu_dibuka)->format('H:i') }} - {{ \Carbon\Carbon::parse($t->waktu_ditutup)->format('H:i') }}</p>
                        @endif
                    </div>
                </div>
                @empty
                @endforelse

                @if($pertemuan->materi->isEmpty() && $pertemuan->tugas->isEmpty())
                <div class="border border-dashed border-slate-300 rounded-lg p-6 flex flex-col items-center justify-center text-center">
                    <p class="text-sm text-slate-500 mb-2">Belum ada aktivitas pada pertemuan ini</p>
                    <span class="text-xs text-slate-400">Gunakan tombol diatas untuk menambah materi/tugas</span>
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

    <div class="tab-content space-y-4 hidden" id="tab-semua">
        @forelse($allMateriGuru as $materi)
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                <div class="flex-1 w-full">
                    <div class="flex items-start space-x-3 mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg shrink-0">
                            <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Icon" class="w-6 h-6">
                        </div>
                        <div class="w-full">
                            <h4 class="font-semibold text-slate-800 text-base sm:text-lg line-clamp-2">{{ $materi->judul_materi }}</h4>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-[10px] sm:text-xs font-medium rounded-md border border-blue-100">
                                    Pertemuan {{ $materi->nomor_pertemuan }}
                                </span>
                                <span class="text-[10px] sm:text-xs text-slate-500 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($materi->tgl_upload)->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($materi->deskripsi)
                    <div class="bg-slate-50 rounded-lg p-3 mb-3 border border-slate-100">
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed line-clamp-3">{{ $materi->deskripsi }}</p>
                    </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:flex sm:flex-col gap-2 w-full sm:w-auto sm:ml-4 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                    <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, $materi->id_materi]) }}" class="flex items-center justify-center bg-green-100 text-green-700 text-xs sm:text-sm font-medium px-4 py-2 rounded-lg hover:bg-green-200 transition-colors">
                        Edit
                    </a>
                    <a href="{{ route('guru.download_materi', ['materi', $materi->id_materi]) }}" class="flex items-center justify-center bg-blue-400 text-white text-xs sm:text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors">
                        Download
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-slate-200">
            <p class="text-slate-500">Belum ada materi yang diupload</p>
        </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700', 'hover:border-slate-300');
                    });
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700', 'hover:border-slate-300');
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById('tab-' + tabName).classList.remove('hidden');
                });
            });
            document.querySelector('.tab-button.active').classList.add('border-blue-500', 'text-blue-600');
            document.querySelector('.tab-button.active').classList.remove('border-transparent', 'text-slate-500');
        });
    </script>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection