@extends('layouts.guru.app')

@section('title', 'Laporan Perilaku Siswa')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-blue-500">Laporan Perilaku Siswa</h2>
            <p class="text-sm text-slate-500 mt-1">
                Pencatatan prestasi, kedisiplinan, dan pelanggaran siswa untuk evaluasi serta pemantauan orang tua
            </p>
        </div>
        <div>
            <button type="button" 
                    onclick="openModalTambah()" 
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-colors shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Catat Perilaku Siswa</span>
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Prestasi / Positif --}}
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Prestasi & Positif</p>
                <p class="text-2xl font-bold text-emerald-600 mt-0.5">{{ $totalPrestasi }}</p>
            </div>
        </div>

        {{-- Pelanggaran / Negatif --}}
        <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pelanggaran</p>
                <p class="text-2xl font-bold text-rose-600 mt-0.5">{{ $totalPelanggaran }}</p>
            </div>
        </div>

        {{-- Laporan Baru --}}
        <div class="bg-white rounded-2xl shadow-sm border border-amber-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Perlu Ditindak</p>
                <p class="text-2xl font-bold text-amber-600 mt-0.5">{{ $totalLaporanBaru }}</p>
            </div>
        </div>

        {{-- Net Total Poin --}}
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Skor Poin</p>
                <p class="text-2xl font-bold {{ $totalPoin >= 0 ? 'text-blue-600' : 'text-rose-600' }} mt-0.5">
                    {{ $totalPoin >= 0 ? '+' . $totalPoin : $totalPoin }}
                </p>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-4">
        
        {{-- Jenis Filter Tabs --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-4">
            @php
                $jenisTabs = [
                    'Semua' => ['label' => 'Semua Jenis', 'icon' => 'solar:layers-bold', 'color' => 'text-blue-500'],
                    'Prestasi' => ['label' => 'Prestasi', 'icon' => 'solar:cup-first-bold', 'color' => 'text-amber-500'],
                    'Pelanggaran' => ['label' => 'Pelanggaran', 'icon' => 'solar:danger-triangle-bold', 'color' => 'text-rose-500'],
                    'Catatan Positif' => ['label' => 'Catatan Positif', 'icon' => 'solar:stars-minimalistic-bold', 'color' => 'text-emerald-500'],
                    'Catatan Negatif' => ['label' => 'Catatan Negatif', 'icon' => 'solar:clipboard-list-bold', 'color' => 'text-slate-500'],
                ];
            @endphp
            @foreach($jenisTabs as $key => $tab)
                <a href="{{ route('guru.perilaku', array_merge(request()->query(), ['jenis' => $key])) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5 {{ $jenisFilter === $key ? 'bg-blue-500 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <iconify-icon icon="{{ $tab['icon'] }}" class="text-sm {{ $jenisFilter === $key ? 'text-white' : $tab['color'] }}"></iconify-icon>
                    <span>{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search & Filters Form --}}
        <form method="GET" action="{{ route('guru.perilaku') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <input type="hidden" name="jenis" value="{{ $jenisFilter }}">

            <div class="sm:col-span-5 md:col-span-6 relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama siswa, NISN, atau judul catatan..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="sm:col-span-3">
                <select name="kelas_id" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ $kelasFilter == $kelas->id_kelas ? 'selected' : '' }}>
                            Kelas {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2">
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="Semua" {{ $statusFilter == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Baru" {{ $statusFilter == 'Baru' ? 'selected' : '' }}>Baru</option>
                    <option value="Ditindaklanjuti" {{ $statusFilter == 'Ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                    <option value="Selesai" {{ $statusFilter == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition-colors">
                    Filter
                </button>
                @if($search || $kelasFilter || $jenisFilter !== 'Semua' || $statusFilter !== 'Semua')
                    <a href="{{ route('guru.perilaku') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center" title="Reset filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- List Laporan Perilaku --}}
    <div class="space-y-4">
        @forelse($laporanList as $item)
            @php
                $siswa = $item->siswa;
                $isPositif = in_array($item->jenis, ['Prestasi', 'Catatan Positif']);
                
                $namaKelas = '-';
                if ($siswa) {
                    if ($siswa->kelas) {
                        $namaKelas = $siswa->kelas->nama_kelas;
                    } elseif ($siswa->kelasAktif && $siswa->kelasAktif->count() > 0) {
                        $namaKelas = $siswa->kelasAktif->first()->nama_kelas;
                    }
                }

                $tglKejadian = $item->tanggal_kejadian ? \Carbon\Carbon::parse($item->tanggal_kejadian)->format('d M Y') : '-';
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border {{ $isPositif ? 'border-emerald-100' : 'border-rose-100' }} p-5 sm:p-6 hover:shadow-md transition-all">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    
                    <div class="flex items-start space-x-4 flex-1">
                        {{-- Icon --}}
                        <div class="w-12 h-12 rounded-2xl {{ $isPositif ? 'bg-emerald-50 border border-emerald-200 text-emerald-600' : 'bg-rose-50 border border-rose-200 text-rose-600' }} flex items-center justify-center flex-shrink-0">
                            @if($isPositif)
                                <iconify-icon icon="solar:cup-first-bold" class="text-2xl text-emerald-600"></iconify-icon>
                            @else
                                <iconify-icon icon="solar:danger-triangle-bold" class="text-2xl text-rose-600"></iconify-icon>
                            @endif
                        </div>

                        <div class="space-y-1.5 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base sm:text-lg font-bold text-slate-800">
                                    {{ $item->judul }}
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $isPositif ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $item->jenis }}
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $item->poin >= 0 ? 'bg-blue-100 text-blue-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $item->poin >= 0 ? '+' . $item->poin : $item->poin }} Poin
                                </span>
                                @if($item->tingkat_severity)
                                    <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold {{ $item->tingkat_severity == 'Berat' ? 'bg-red-500 text-white' : ($item->tingkat_severity == 'Sedang' ? 'bg-amber-400 text-slate-800' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $item->tingkat_severity }}
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs sm:text-sm text-slate-500 flex flex-wrap items-center gap-2">
                                <span>Siswa: <strong class="text-slate-700">{{ $siswa->nama_lengkap ?? 'Siswa' }}</strong> (Kelas {{ $namaKelas }})</span>
                                <span>•</span>
                                <span>Tanggal: <strong>{{ $tglKejadian }}</strong></span>
                                @if($item->lokasi)
                                    <span>•</span>
                                    <span>Lokasi: {{ $item->lokasi }}</span>
                                @endif
                            </p>

                            <p class="text-sm text-slate-700 pt-1 leading-relaxed">
                                {{ $item->deskripsi }}
                            </p>

                            @if($item->tindak_lanjut)
                                <div class="mt-2 bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs sm:text-sm text-slate-600">
                                    <strong class="text-slate-800">Tindak Lanjut:</strong> {{ $item->tindak_lanjut }}
                                </div>
                            @endif

                            <div class="flex flex-wrap items-center gap-4 pt-2 text-xs text-slate-400">
                                <span>Dicatat oleh: <strong class="text-slate-600">{{ $item->pelapor->name ?? 'Guru' }}</strong></span>
                                @if($item->bukti_foto)
                                    <button type="button" 
                                            onclick="previewFoto('{{ asset('storage/' . $item->bukti_foto) }}')" 
                                            class="text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Lihat Foto Bukti
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Status Badge & Action Controls --}}
                    <div class="flex flex-col items-start lg:items-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100 flex-shrink-0">
                        @php
                            $statusBadge = [
                                'Baru' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'Ditindaklanjuti' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'Selesai' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusBadge[$item->status] ?? 'bg-slate-100 text-slate-600' }}">
                            Status: {{ $item->status }}
                        </span>

                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    onclick="openModalStatus({{ $item->id_laporan }}, '{{ $item->status }}', '{{ addslashes($item->tindak_lanjut ?? '') }}')"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Update Status
                            </button>

                            <form method="POST" action="{{ route('guru.perilaku.destroy', $item->id_laporan) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan perilaku ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors" title="Hapus Catatan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h4 class="text-lg font-bold text-slate-700">Belum ada catatan perilaku</h4>
                <p class="text-sm text-slate-400 mt-1">Gunakan tombol "+ Catat Perilaku Siswa" untuk menambahkan catatan baru.</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($laporanList->hasPages())
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                {{ $laporanList->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Tambah Catatan Perilaku --}}
<div id="modalTambah" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4 my-8" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-lg">Tambah Catatan Perilaku Siswa</h3>
            <button onclick="closeModalTambah()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">✕</button>
        </div>

        <form method="POST" action="{{ route('guru.perilaku.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Pilih Siswa --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Pilih Siswa <span class="text-rose-500">*</span></label>
                    <select name="siswa_id" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($daftarSiswa as $s)
                            @php
                                $sKelas = $s->kelas ? $s->kelas->nama_kelas : ($s->kelasAktif->first()->nama_kelas ?? '-');
                            @endphp
                            <option value="{{ $s->id_siswa }}">
                                {{ $s->nama_lengkap }} (Kelas {{ $sKelas }} - NISN: {{ $s->nisn }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Perilaku --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Jenis Catatan <span class="text-rose-500">*</span></label>
                    <select name="jenis" id="inputJenis" onchange="adjustPoinPlaceholder()" required class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        <option value="Prestasi">Prestasi</option>
                        <option value="Catatan Positif">Catatan Positif</option>
                        <option value="Pelanggaran">Pelanggaran</option>
                        <option value="Catatan Negatif">Catatan Negatif</option>
                    </select>
                </div>

                {{-- Nilai Poin --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Bobot Poin</label>
                    <input type="number" 
                           name="poin" 
                           id="inputPoin" 
                           value="10" 
                           min="0"
                           max="100"
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                           placeholder="Contoh: 10">
                    <p class="text-[11px] text-slate-400 mt-1" id="poinHelpText">Nilai akan otomatis bernilai positif untuk prestasi</p>
                </div>

                {{-- Tingkat Severity --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tingkat Severity</label>
                    <select name="tingkat_severity" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        <option value="Ringan">Ringan</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Berat">Berat</option>
                    </select>
                </div>

                {{-- Tanggal Kejadian --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tanggal Kejadian <span class="text-rose-500">*</span></label>
                    <input type="date" 
                           name="tanggal_kejadian" 
                           value="{{ date('Y-m-d') }}" 
                           required 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Lokasi Kejadian --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Lokasi Kejadian (Opsional)</label>
                    <input type="text" 
                           name="lokasi" 
                           placeholder="Contoh: Lapangan Sekolah, Ruang Kelas X-E1, Perpustakaan" 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Judul --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Judul Kejadian <span class="text-rose-500">*</span></label>
                    <input type="text" 
                           name="judul" 
                           required 
                           placeholder="Contoh: Juara 1 Lomba Sains / Terlambat Masuk Kelas" 
                           class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- Deskripsi --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                    <textarea name="deskripsi" 
                              rows="3" 
                              required 
                              placeholder="Jelaskan secara rinci kronologi kejadian..." 
                              class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                {{-- Tindak Lanjut --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Tindak Lanjut yang Dilakukan</label>
                    <textarea name="tindak_lanjut" 
                              rows="2" 
                              placeholder="Contoh: Diberikan sertifikat penghargaan / Diberikan peringatan lisan dan bimbingan" 
                              class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                {{-- Bukti Foto --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Foto Bukti (Opsional)</label>
                    <input type="file" 
                           name="bukti_foto" 
                           accept="image/*" 
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModalTambah()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Update Status Tindak Lanjut --}}
<div id="modalStatus" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-lg">Perbarui Status Penanganan</h3>
            <button onclick="closeModalStatus()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">✕</button>
        </div>

        <form id="formUpdateStatus" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Status Penanganan</label>
                <select name="status" id="editStatusSelect" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="Baru">Baru</option>
                    <option value="Ditindaklanjuti">Ditindaklanjuti</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Catatan Tindak Lanjut</label>
                <textarea name="tindak_lanjut" id="editTindakLanjut" rows="3" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Tindakan yang telah dilakukan terhadap siswa..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeModalStatus()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Preview Foto --}}
<div id="modalPreviewFoto" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-lg">Foto Bukti Perilaku</h3>
            <button onclick="closePreviewFoto()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">✕</button>
        </div>
        <div class="max-h-[70vh] overflow-y-auto flex items-center justify-center bg-slate-50 rounded-xl p-3">
            <img id="previewImageSrc" src="" alt="Bukti Foto" class="max-w-full h-auto rounded-lg object-contain">
        </div>
        <div class="flex justify-end">
            <button onclick="closePreviewFoto()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openModalTambah() {
    document.getElementById('modalTambah').classList.remove('hidden');
}
function closeModalTambah() {
    document.getElementById('modalTambah').classList.add('hidden');
}
function adjustPoinPlaceholder() {
    const jenis = document.getElementById('inputJenis').value;
    const poinInput = document.getElementById('inputPoin');
    const helpText = document.getElementById('poinHelpText');
    if (jenis === 'Pelanggaran' || jenis === 'Catatan Negatif') {
        helpText.textContent = 'Poin akan dikurangkan (bernilai negatif) pada profil siswa';
        if (poinInput.value == 10) poinInput.value = 5;
    } else {
        helpText.textContent = 'Poin akan ditambahkan (bernilai positif) pada profil siswa';
        if (poinInput.value == 5) poinInput.value = 10;
    }
}

function openModalStatus(id, currentStatus, tindakLanjut) {
    const form = document.getElementById('formUpdateStatus');
    form.action = `/guru/perilaku/${id}/status`;
    document.getElementById('editStatusSelect').value = currentStatus;
    document.getElementById('editTindakLanjut').value = tindakLanjut || '';
    document.getElementById('modalStatus').classList.remove('hidden');
}
function closeModalStatus() {
    document.getElementById('modalStatus').classList.add('hidden');
}

function previewFoto(url) {
    document.getElementById('previewImageSrc').src = url;
    document.getElementById('modalPreviewFoto').classList.remove('hidden');
}
function closePreviewFoto() {
    document.getElementById('modalPreviewFoto').classList.add('hidden');
    document.getElementById('previewImageSrc').src = '';
}

// Close modals when clicking backdrop
['modalTambah', 'modalStatus', 'modalPreviewFoto'].forEach(id => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }
});
</script>
@endpush

@endsection
