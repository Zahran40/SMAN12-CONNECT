@extends('layouts.guru.app')

@section('content')

    <div class="flex justify-between items-center mb-6 sm:mb-8">
        <div class="flex items-center space-x-3">
            <a href="{{ route('guru.materi') }}" class="text-blue-600 hover:text-blue-800" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-blue-600">{{ $jadwal->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }} - {{ $jadwal->kelas->nama_kelas ?? '' }}</h2>
        </div>
        
        <div class="flex gap-3">
            <a href="{{ route('guru.upload_materi', $jadwal->id_jadwal) }}" class="flex items-center space-x-2 bg-blue-400 text-white font-semibold px-5 py-3 rounded-xl hover:bg-blue-500 transition-colors shadow-md shadow-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Materi</span>
            </a>
            <a href="{{ route('guru.upload_tugas', $jadwal->id_jadwal) }}" class="flex items-center space-x-2 bg-green-500 text-white font-semibold px-5 py-3 rounded-xl hover:bg-green-600 transition-colors shadow-md shadow-green-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Tugas</span>
            </a>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-slate-200">
        <nav class="flex space-x-8" role="tablist">
            <button type="button" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors active" data-tab="pertemuan" role="tab">
                <span class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span>Per Pertemuan</span>
                </span>
            </button>
            <button type="button" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="semua" role="tab">
                <span class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                    <span>Semua Materi Saya ({{ count($allMateriGuru) }})</span>
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content: Per Pertemuan -->
    <div class="tab-content space-y-4 sm:space-y-6" id="tab-pertemuan">
        @forelse($pertemuans as $pertemuan)
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 border border-slate-200">
            <div class="flex justify-between items-center mb-4 sm:mb-6 cursor-pointer" data-pertemuan-toggle>
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
                                <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, $m->id_materi]) }}" class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</a>
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
                                <a href="{{ route('guru.detail_tugas', $t->id_tugas) }}" class="bg-blue-400 text-white text-sm font-medium px-9 py-1 rounded-full hover:bg-blue-500 transition-colors">Lihat</a>
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
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 text-center">
            <p class="text-slate-500">Belum ada pertemuan untuk jadwal ini</p>
        </div>
        @endforelse
    </div>

    <!-- Tab Content: Semua Materi -->
    <div class="tab-content space-y-4 hidden" id="tab-semua">
        @forelse($allMateriGuru as $materi)
        <div class="bg-white rounded-xl shadow-md p-5 border border-slate-200 hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Icon Folder" class="w-6 h-6">
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-800 text-lg">{{ $materi->judul_materi }}</h4>
                            <div class="flex items-center space-x-3 mt-1">
                                <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Pertemuan {{ $materi->nomor_pertemuan }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 inline mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($materi->tgl_upload)->translatedFormat('d F Y, H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($materi->deskripsi)
                    <div class="bg-slate-50 rounded-lg p-3 mb-3">
                        <p class="text-xs font-medium text-slate-600 mb-1">Deskripsi:</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $materi->deskripsi }}</p>
                    </div>
                    @endif

                    @if($materi->topik_bahasan)
                    <div class="text-xs text-slate-500 italic">
                        Topik: {{ $materi->topik_bahasan }}
                    </div>
                    @endif
                </div>

                <div class="flex flex-col space-y-2 ml-4">
                    <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, $materi->id_materi]) }}" class="bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-lg hover:bg-green-200 transition-colors text-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 inline mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                        Edit
                    </a>
                    <a href="{{ route('guru.download_materi', ['materi', $materi->id_materi]) }}" class="bg-blue-400 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-500 transition-colors text-center whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 inline mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8 text-center border border-slate-200">
            <div class="inline-block p-4 bg-slate-100 rounded-full mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                </svg>
            </div>
            <p class="text-slate-500 text-lg">Belum ada materi yang diupload</p>
            <p class="text-slate-400 text-sm mt-1">Klik "Buat Materi" untuk mulai mengupload materi pembelajaran</p>
        </div>
        @endforelse
    </div>

    <!-- Tab Switching Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700', 'hover:border-slate-300');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700', 'hover:border-slate-300');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show selected tab content
                    document.getElementById('tab-' + tabName).classList.remove('hidden');
                });
            });
            
            // Set initial active state
            document.querySelector('.tab-button.active').classList.add('border-blue-500', 'text-blue-600');
            document.querySelector('.tab-button.active').classList.remove('border-transparent', 'text-slate-500');
        });
    </script>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection

