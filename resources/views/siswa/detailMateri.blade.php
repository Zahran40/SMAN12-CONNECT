@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-3 sm:space-x-4 mb-6 sm:mb-8">
        <a href="{{ route('siswa.materi') }}" class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors shrink-0" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 sm:w-8 sm:h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-blue-500">{{ $jadwal->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</h2>
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
    
    @if($errors->any())
        <div class="alert-auto-hide bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-t-xl border-2 border-b-0 border-blue-200">
        <nav class="flex overflow-x-auto px-4 sm:px-6 pt-4 scrollbar-hide" role="tablist">
            <button type="button" class="tab-button-siswa py-2 sm:py-3 px-4 sm:px-6 font-medium text-xs sm:text-sm transition-colors rounded-t-lg active whitespace-nowrap" data-tab="pertemuan" role="tab">
                <span class="flex items-center space-x-1 sm:space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <span class="hidden sm:inline">Materi Per Pertemuan</span>
                    <span class="sm:hidden">Materi</span>
                </span>
            </button>
            <button type="button" class="tab-button-siswa py-2 sm:py-3 px-4 sm:px-6 font-medium text-xs sm:text-sm transition-colors rounded-t-lg whitespace-nowrap" data-tab="tugas" role="tab">
                <span class="flex items-center space-x-1 sm:space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span class="hidden sm:inline">Status Tugas ({{ $allTugasSiswa->count() }})</span>
                    <span class="sm:hidden">Tugas ({{ $allTugasSiswa->count() }})</span>
                </span>
            </button>
        </nav>
    </div>

    <!-- Tab Content: Per Pertemuan -->
    <div class="tab-content-siswa mb-6 bg-white rounded-b-xl border-2 border-t-0 border-blue-200 shadow-lg" id="tab-siswa-pertemuan">
        
        <div class="divide-y divide-slate-200">

            @forelse($pertemuans as $pertemuan)
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center cursor-pointer" data-pertemuan-toggle>
                    <div class="flex items-center space-x-3 sm:space-x-4">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 shrink-0 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-sm sm:text-base">
                            {{ $pertemuan->pertemuan_ke }}
                        </div>
                        <h3 class="font-bold text-base sm:text-lg text-slate-800">Pertemuan {{ $pertemuan->pertemuan_ke }}</h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-500 transition-transform shrink-0">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div class="pl-0 sm:pl-14 pt-4 space-y-3 sm:space-y-4 {{ $loop->first ? '' : 'hidden' }}">
                    
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
                        
                        // Parse tanggal dari database (already in date format)
                        $tanggalDibuka = $t->tanggal_dibuka ? \Carbon\Carbon::parse($t->tanggal_dibuka)->startOfDay() : now();
                        $tanggalDitutup = $t->tanggal_ditutup ? \Carbon\Carbon::parse($t->tanggal_ditutup)->startOfDay() : now();
                        
                        // Create full datetime by setting time on the date
                        $waktuBuka = clone $tanggalDibuka;
                        if ($t->jam_buka) {
                            $jamBukaParts = explode(':', $t->jam_buka);
                            $waktuBuka->setTime((int)$jamBukaParts[0], (int)$jamBukaParts[1], isset($jamBukaParts[2]) ? (int)$jamBukaParts[2] : 0);
                        }
                        
                        $waktuTutup = clone $tanggalDitutup;
                        if ($t->jam_tutup) {
                            $jamTutupParts = explode(':', $t->jam_tutup);
                            $waktuTutup->setTime((int)$jamTutupParts[0], (int)$jamTutupParts[1], isset($jamTutupParts[2]) ? (int)$jamTutupParts[2] : 0);
                        }
                        
                        // Tugas terbuka jika: sekarang >= waktu buka DAN sekarang <= waktu tutup
                        $isOpen = $now->greaterThanOrEqualTo($waktuBuka) && $now->lessThanOrEqualTo($waktuTutup);
                        $isBelumBuka = $now->lessThan($waktuBuka);
                        $isSudahTutup = $now->greaterThan($waktuTutup);
                        
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
                                @if($t->file_path)
                                    <a href="{{ route('siswa.download_tugas', $t->id_tugas) }}" class="bg-blue-600 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-blue-700 transition-colors">
                                        Download Soal
                                    </a>
                                @endif
                                
                                @if($isBelumBuka)
                                    <span class="bg-yellow-500 text-white text-xs font-medium px-4 py-1.5 rounded-full flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Belum Dibuka</span>
                                    </span>
                                @elseif($isOpen)
                                    <button onclick="document.getElementById('upload-form-{{ $t->id_tugas }}').classList.toggle('hidden')" class="bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-green-600 transition-colors flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" />
                                            <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                                        </svg>
                                        <span>{{ $detailTugas ? 'Upload Ulang' : 'Upload Jawaban' }}</span>
                                    </button>
                                @elseif($isSudahTutup)
                                    <span class="bg-gray-400 text-white text-xs font-medium px-4 py-1.5 rounded-full flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                                        </svg>
                                        <span>Ditutup</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 border-t border-blue-100 pt-3 space-y-2">
                            <div>
                                <p class="text-sm font-medium text-slate-600">Deskripsi Tugas :</p>
                                <p class="text-sm text-slate-500">{{ $t->deskripsi_tugas ?? '-' }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3 space-y-1.5">
                                <div class="flex items-center space-x-2 text-xs text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-green-600">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                                    </svg>
                                    <span><strong>Dibuka:</strong> {{ $tanggalDibuka->translatedFormat('l, d F Y') }} - {{ substr($t->jam_buka, 0, 5) }}</span>
                                </div>
                                <div class="flex items-center space-x-2 text-xs text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-red-600">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                                    </svg>
                                    <span><strong>Ditutup:</strong> {{ $tanggalDitutup->translatedFormat('l, d F Y') }} - {{ substr($t->jam_tutup, 0, 5) }}</span>
                                </div>
                            </div>
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
                                                
                                                // Create batas waktu from tanggal_ditutup + jam_tutup
                                                $batasWaktu = clone $tanggalDitutup;
                                                if ($t->jam_tutup) {
                                                    $jamTutupParts = explode(':', $t->jam_tutup);
                                                    $batasWaktu->setTime((int)$jamTutupParts[0], (int)$jamTutupParts[1], isset($jamTutupParts[2]) ? (int)$jamTutupParts[2] : 0);
                                                }
                                                
                                                $isLate = $tglKumpul->greaterThan($batasWaktu);
                                                // Calculate total minutes difference
                                                $totalMinutes = abs($tglKumpul->diffInMinutes($batasWaktu));
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
                                        <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 align-top">Komentar Guru</td>
                                        <td class="py-3 px-4">
                                            @if($detailTugas->komentar_guru)
                                                <div class="bg-blue-50 border-l-4 border-blue-500 rounded p-4">
                                                    <div class="flex items-start space-x-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-600 mt-0.5 shrink-0">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-xs font-semibold text-blue-700 mb-1">💬 Feedback dari Guru:</p>
                                                            <p class="text-sm text-gray-800 leading-relaxed">{{ $detailTugas->komentar_guru }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-500 italic">Belum ada komentar dari guru</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @endif
                        
                        @if($isOpen)
                        <!-- Form Upload - Selalu ada jika tugas terbuka -->
                        <form id="upload-form-{{ $t->id_tugas }}" action="{{ route('siswa.upload_tugas', $t->id_tugas) }}" method="POST" enctype="multipart/form-data" class="mt-4 hidden">
                            @csrf
                            
                            <div class="bg-white border border-gray-200 rounded overflow-hidden">
                                <h4 class="font-bold text-gray-800 px-4 py-3 bg-gray-50 border-b border-gray-200">Upload Jawaban Anda</h4>
                                
                                <div class="p-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Teks Jawaban (Opsional)</label>
                                        <textarea name="teks_jawaban" rows="4" class="w-full border-2 border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-blue-500" placeholder="Tulis jawaban atau catatan Anda di sini...">{{ old('teks_jawaban') }}</textarea>
                                        <p class="text-xs text-gray-500 mt-1">Anda bisa menulis jawaban langsung atau upload file, atau keduanya</p>
                                    </div>
                                    
                                    <div id="drop-zone-{{ $t->id_tugas }}" class="border-2 border-dashed border-blue-300 rounded-lg p-8 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer">
                                        <div class="space-y-2">
                                            <svg class="mx-auto h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <label for="tugas-file-{{ $t->id_tugas }}" class="block text-blue-600 font-medium cursor-pointer">
                                                Upload file (Opsional)
                                            </label>
                                            <p class="text-xs text-gray-500">PDF, DOC, DOCX, ZIP, RAR (Max: 20MB)</p>
                                            <input type="file" name="file" id="tugas-file-{{ $t->id_tugas }}" class="hidden" accept=".pdf,.doc,.docx,.zip,.rar">
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
                    
                    // Validasi ukuran file (max 20MB)
                    if (fileSizeMB > 20) {
                        alert('❌ Ukuran file terlalu besar!\n\nFile: ' + file.name + '\nUkuran: ' + fileSizeMB + ' MB\n\nMaksimal ukuran file adalah 20MB.\nSilakan kompres atau kurangi ukuran file Anda.');
                        fileInput.value = '';
                        return;
                    }
                    
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

    <!-- Tab Content: Status Tugas -->
    <div class="tab-content-siswa hidden bg-white rounded-b-xl border-2 border-t-0 border-blue-200 shadow-lg p-6" id="tab-siswa-tugas">
        <div class="space-y-4">
            @forelse($allTugasSiswa as $tugas)
            <div class="bg-white rounded-xl shadow-md p-5 border-l-4 {{ $tugas->status_pengumpulan == 'Belum Dikumpulkan' ? 'border-red-400' : ($tugas->status_pengumpulan == 'Tepat Waktu' ? 'border-green-400' : 'border-yellow-400') }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h4 class="font-semibold text-slate-800 text-lg">{{ $tugas->judul_tugas }}</h4>
                            <span class="inline-flex items-center px-2 py-1 {{ $tugas->status_pengumpulan == 'Belum Dikumpulkan' ? 'bg-red-100 text-red-700' : ($tugas->status_pengumpulan == 'Tepat Waktu' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700') }} text-xs font-semibold rounded-full">
                                {{ $tugas->status_pengumpulan }}
                            </span>
                        </div>

                        <div class="space-y-2 mb-3">
                            <div class="flex items-center space-x-2 text-sm text-slate-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <span>Pertemuan {{ $tugas->nomor_pertemuan }}</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="font-medium text-red-600">Ditutup: {{ \Carbon\Carbon::parse($tugas->waktu_ditutup)->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                            @if($tugas->tgl_kumpul)
                            <div class="flex items-center space-x-2 text-sm text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>Dikumpulkan: {{ \Carbon\Carbon::parse($tugas->tgl_kumpul)->translatedFormat('d F Y, H:i') }}</span>
                            </div>
                            @endif
                        </div>

                        @if($tugas->deskripsi)
                        <div class="bg-slate-50 rounded-lg p-3 mb-3">
                            <p class="text-xs font-medium text-slate-600 mb-1">Deskripsi:</p>
                            <p class="text-sm text-slate-700">{{ $tugas->deskripsi }}</p>
                        </div>
                        @endif

                        @if($tugas->nilai)
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-4 border-2 border-green-200">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-3 flex-1">
                                    <div class="bg-green-500 text-white rounded-full p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-baseline space-x-2 mb-2">
                                            <span class="text-2xl font-bold text-green-700">{{ $tugas->nilai }}</span>
                                            <span class="text-sm text-gray-600">/ 100</span>
                                        </div>
                                        @if($tugas->komentar_guru)
                                        <div class="bg-white rounded-md p-3 border-l-4 border-blue-400 mt-2">
                                            <p class="text-xs font-semibold text-blue-700 mb-1">💬 Feedback dari Guru:</p>
                                            <p class="text-sm text-gray-800 leading-relaxed">{{ $tugas->komentar_guru }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($tugas->tgl_kumpul)
                        <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                            <div class="flex items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-yellow-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <p class="text-sm font-medium text-yellow-700">⏳ Sedang diproses oleh guru</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-md p-8 text-center border border-slate-200">
                <div class="inline-block p-4 bg-slate-100 rounded-full mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                </div>
                <p class="text-slate-500 text-lg">Belum ada tugas untuk mata pelajaran ini</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button-siswa');
            const tabContents = document.querySelectorAll('.tab-content-siswa');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;
                    
                    // Remove active class from all buttons
                    tabButtons.forEach(btn => {
                        btn.classList.remove('active', 'bg-blue-500', 'text-white');
                        btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active', 'bg-blue-500', 'text-white');
                    this.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show selected tab content
                    document.getElementById('tab-siswa-' + tabName).classList.remove('hidden');
                });
            });
            
            // Set initial active state
            document.querySelector('.tab-button-siswa.active').classList.add('bg-blue-500', 'text-white');
            document.querySelector('.tab-button-siswa.active').classList.remove('bg-slate-100', 'text-slate-600');
        });
    </script>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection
        