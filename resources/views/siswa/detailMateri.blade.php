@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('siswa.materi') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">{{ $jadwal->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</h2>
    </div>

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

    <div class="bg-white rounded-xl border-2 border-blue-200 shadow-lg">
        
        <div class="divide-y divide-slate-200">

            @forelse($pertemuans as $pertemuan)
            <div class="p-6">
                <div class="flex justify-between items-center cursor-pointer" data-pertemuan-toggle>
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 shrink-0 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                            {{ $pertemuan->pertemuan_ke }}
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Pertemuan {{ $pertemuan->pertemuan_ke }}</h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-500 transition-transform">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div class="pl-14 pt-4 space-y-4 {{ $loop->first ? '' : 'hidden' }}">
                    
                    @forelse($pertemuan->materi as $m)
                    <div class="border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/weui_folder-filled.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-blue-600">
                                  <path d="M3.5 3.75a.25.25 0 0 1 .25-.25h13.5a.25.25 0 0 1 .25.25v10a.75.75 0 0 0 1.5 0v-10A1.75 1.75 0 0 0 17.25 2H3.75A1.75 1.75 0 0 0 2 3.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0 0 18 14.25v-2.5a.75.75 0 0 0-1.5 0v2.5a.25.25 0 0 1-.25.25H3.75a.25.25 0 0 1-.25-.25V3.75Z" />
                                </img>
                                <span class="font-semibold text-slate-700">{{ $m->judul_materi }}</span>
                            </div>
                            <a href="{{ route('siswa.download_materi', $m->id_materi) }}" class="bg-blue-600 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-blue-700 transition-colors">
                                Download
                            </a>
                        </div>
                        <div class="mt-3 border-t border-blue-100 pt-3">
                            <p class="text-sm font-medium text-slate-600">Deskripsi Materi :</p>
                            <p class="text-sm text-slate-500">{{ $m->deskripsi_materi }}</p>
                        </div>
                    </div>
                    @empty
                    @endforelse
                    
                    @forelse($pertemuan->tugas as $t)
                    @php
                        $now = now();
                        $jamBuka = \Carbon\Carbon::parse($t->jam_buka);
                        $jamTutup = \Carbon\Carbon::parse($t->jam_tutup);
                        $isOpen = $now->between($jamBuka, $jamTutup);
                        $detailTugas = $t->detailTugas->where('siswa_id', auth()->user()->siswa->id_siswa)->first();
                    @endphp
                    <div class="border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/bxs_file.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-blue-600">
                                  <path d="M3.5 3.75a.25.25 0 0 1 .25-.25h13.5a.25.25 0 0 1 .25.25v10a.75.75 0 0 0 1.5 0v-10A1.75 1.75 0 0 0 17.25 2H3.75A1.75 1.75 0 0 0 2 3.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0 0 18 14.25v-2.5a.75.75 0 0 0-1.5 0v2.5a.25.25 0 0 1-.25.25H3.75a.25.25 0 0 1-.25-.25V3.75Z" />
                                </img>
                                <span class="font-semibold text-slate-700">{{ $t->judul_tugas }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('siswa.download_tugas', $t->id_tugas) }}" class="bg-blue-600 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-blue-700 transition-colors">
                                    Download Soal
                                </a>
                                @if(!$detailTugas && $isOpen)
                                    <button onclick="document.getElementById('upload-form-{{ $t->id_tugas }}').classList.toggle('hidden')" class="bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-green-600 transition-colors">
                                        Upload Jawaban
                                    </button>
                                @elseif(!$detailTugas && !$isOpen)
                                    <span class="bg-gray-400 text-white text-xs font-medium px-4 py-1.5 rounded-full">
                                        Ditutup
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 border-t border-blue-100 pt-3 space-y-2">
                            <div>
                                <p class="text-sm font-medium text-slate-600">Deskripsi Tugas :</p>
                                <p class="text-sm text-slate-500">{{ $t->deskripsi_tugas }}</p>
                            </div>
                            @if($t->deadline)
                            <div>
                                <p class="text-xs font-medium text-slate-600">Deadline:</p>
                                <p class="text-xs text-red-600 font-semibold">{{ \Carbon\Carbon::parse($t->deadline)->translatedFormat('l, d F Y') }} - {{ $jamTutup->format('H:i') }}</p>
                            </div>
                            @else
                            <p class="text-xs text-slate-400">Waktu: {{ $jamBuka->format('H:i') }} - {{ $jamTutup->format('H:i') }}</p>
                            @endif
                        </div>
                        
                        @if($detailTugas)
                        <!-- Status Pengajuan Tugas -->
                        <div class="mt-4 bg-white border border-gray-200 rounded overflow-hidden">
                            <h4 class="font-bold text-gray-800 px-4 py-3 bg-gray-50 border-b border-gray-200">Status pengajuan tugas</h4>
                            
                            <table class="w-full">
                                <tbody>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 w-48 align-top">Status pengajuan</td>
                                        <td class="py-3 px-4 bg-green-100">
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-green-700 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-green-800 font-medium">Terkirim dan siap dinilai</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 align-top">Status penilaian</td>
                                        <td class="py-3 px-4 text-gray-800">
                                            @if($detailTugas->nilai)
                                                <span class="font-semibold text-green-700">Nilai: {{ $detailTugas->nilai }}</span>
                                            @else
                                                Belum dinilai
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 align-top">Waktu tersisa</td>
                                        <td class="py-3 px-4">
                                            @php
                                                $tglKumpul = \Carbon\Carbon::parse($detailTugas->tgl_kumpul);
                                                // Use deadline if available, otherwise use pertemuan date + jam_tutup
                                                if ($t->deadline) {
                                                    $deadlineDate = \Carbon\Carbon::parse($t->deadline)->format('Y-m-d');
                                                    $waktuTutup = \Carbon\Carbon::parse($deadlineDate . ' ' . $t->jam_tutup);
                                                } else {
                                                    $tglPertemuan = \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan);
                                                    $waktuTutup = \Carbon\Carbon::parse($tglPertemuan->format('Y-m-d') . ' ' . $t->jam_tutup);
                                                }
                                                $isLate = $tglKumpul->greaterThan($waktuTutup);
                                                // Calculate total minutes difference
                                                $totalMinutes = abs($tglKumpul->diffInMinutes($waktuTutup));
                                                $hours = floor($totalMinutes / 60);
                                                $minutes = $totalMinutes % 60;
                                            @endphp
                                            @if($isLate)
                                                <span class="inline-flex items-start">
                                                    <svg class="w-5 h-5 text-red-700 mr-2 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-red-700">Terlambat {{ $hours }} jam {{ $minutes }} min</span>
                                                </span>
                                            @else
                                                <div class="bg-green-100 px-3 py-2 rounded">
                                                    <span class="inline-flex items-start">
                                                        <svg class="w-5 h-5 text-green-700 mr-2 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-green-800">Penugasan diajukan {{ $hours }} jam {{ $minutes }} min lebih awal</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 align-top">Terakhir diubah</td>
                                        <td class="py-3 px-4 text-gray-800">{{ \Carbon\Carbon::parse($detailTugas->tgl_kumpul)->translatedFormat('l, d F Y, H:i') }}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200">
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 align-top">Pengajuan berkas</td>
                                        <td class="py-3 px-4">
                                            <div class="flex items-start space-x-2">
                                                @php
                                                    $extension = strtolower(pathinfo($detailTugas->file_path, PATHINFO_EXTENSION));
                                                    $iconColor = match($extension) {
                                                        'pdf' => 'text-red-600',
                                                        'doc', 'docx' => 'text-blue-600',
                                                        'xls', 'xlsx' => 'text-green-600',
                                                        'ppt', 'pptx' => 'text-orange-600',
                                                        'zip', 'rar' => 'text-purple-600',
                                                        default => 'text-gray-600'
                                                    };
                                                @endphp
                                                <svg class="w-5 h-5 {{ $iconColor }} mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div>
                                                    @php
                                                        $fileName = basename($detailTugas->file_path);
                                                        // Remove timestamp and anything before C2, keep everything after C2
                                                        preg_match('/.*?_(C\d+.*)/', $fileName, $matches);
                                                        $displayName = !empty($matches[1]) ? $matches[1] : $fileName;
                                                    @endphp
                                                    <a href="{{ Storage::url($detailTugas->file_path) }}" class="text-blue-600 hover:underline font-medium" download>
                                                        {{ $displayName }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($detailTugas->tgl_kumpul)->translatedFormat('d F Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 align-top">Komentar pengajuan</td>
                                        <td class="py-3 px-4">
                                            @if($detailTugas->komentar_guru)
                                                <div class="bg-blue-50 border border-blue-200 rounded p-3 text-sm text-gray-800">
                                                    {{ $detailTugas->komentar_guru }}
                                                </div>
                                            @else
                                                <button type="button" class="text-sm text-gray-600 flex items-center hover:text-gray-800">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                    Komentar (0)
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @elseif($isOpen)
                        <form id="upload-form-{{ $t->id_tugas }}" action="{{ route('siswa.upload_tugas', $t->id_tugas) }}" method="POST" enctype="multipart/form-data" class="mt-4 hidden">
                            @csrf
                            
                            <div class="bg-white border border-gray-200 rounded overflow-hidden">
                                <h4 class="font-bold text-gray-800 px-4 py-3 bg-gray-50 border-b border-gray-200">Upload Jawaban Anda</h4>
                                
                                <div class="p-4">
                                    <div id="drop-zone-{{ $t->id_tugas }}" class="border-2 border-dashed border-blue-300 rounded-lg p-8 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer">
                                        <div class="space-y-2">
                                            <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <label for="tugas-file-{{ $t->id_tugas }}" class="block text-blue-600 font-medium cursor-pointer">
                                                Upload file
                                            </label>
                                            <input type="file" name="file" id="tugas-file-{{ $t->id_tugas }}" class="hidden" required accept=".pdf,.doc,.docx,.zip,.rar">
                                        </div>
                                    </div>
                                    
                                    <div id="file-preview-{{ $t->id_tugas }}" class="mt-4 hidden">
                                        <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-8 h-8 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                                </svg>
                                                <div>
                                                    <p id="file-name-{{ $t->id_tugas }}" class="text-sm font-medium text-slate-700"></p>
                                                    <p id="file-size-{{ $t->id_tugas }}" class="text-xs text-slate-500"></p>
                                                </div>
                                            </div>
                                            <button type="button" onclick="removeFileTugas({{ $t->id_tugas }})" class="text-red-500 hover:text-red-700">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="mt-4 w-full bg-green-500 text-white px-4 py-2.5 rounded-lg hover:bg-green-600 transition-colors font-medium">
                                        Kirim
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                    @empty
                    @endforelse

                    @if($pertemuan->materi->isEmpty() && $pertemuan->tugas->isEmpty())
                    <div class="border border-slate-200 rounded-lg p-4 text-center">
                        <p class="text-sm text-slate-500">Belum ada materi atau tugas pada pertemuan ini</p>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-6 text-center">
                <p class="text-slate-500">Belum ada pertemuan untuk mata pelajaran ini</p>
            </div>
            @endforelse
            
        </div>
    </div>

    <script>
        // Drag & Drop for tugas upload
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[id^="drop-zone-"]').forEach(dropZone => {
                const tugasId = dropZone.id.split('-')[2];
                const fileInput = document.getElementById('tugas-file-' + tugasId);
                const filePreview = document.getElementById('file-preview-' + tugasId);
                const fileName = document.getElementById('file-name-' + tugasId);
                const fileSize = document.getElementById('file-size-' + tugasId);

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.add('border-blue-600', 'bg-blue-100');
                    });
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, () => {
                        dropZone.classList.remove('border-blue-600', 'bg-blue-100');
                    });
                });

                dropZone.addEventListener('drop', function(e) {
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        fileInput.files = files;
                        handleFiles(files, tugasId);
                    }
                });

                fileInput.addEventListener('change', function(e) {
                    if (this.files.length > 0) {
                        handleFiles(this.files, tugasId);
                    }
                });

                function handleFiles(files, id) {
                    const file = files[0];
                    const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                    
                    fileName.textContent = file.name;
                    fileSize.textContent = `${fileSizeMB} MB`;
                    
                    dropZone.classList.add('hidden');
                    filePreview.classList.remove('hidden');
                }
            });
        });

        function removeFileTugas(tugasId) {
            const fileInput = document.getElementById('tugas-file-' + tugasId);
            const dropZone = document.getElementById('drop-zone-' + tugasId);
            const filePreview = document.getElementById('file-preview-' + tugasId);
            
            fileInput.value = '';
            dropZone.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }
    </script>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection
        