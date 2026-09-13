@extends('layouts.guru.app')

@section('title', 'Perizinan Siswa')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-blue-500">Kelola Perizinan Siswa</h2>
            <p class="text-sm text-slate-500 mt-1">
                Tinjau dan beri persetujuan untuk pengajuan izin sakit dan izin dari orang tua siswa
                @if($isWaliKelas)
                    <span class="inline-block ml-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                        Wali Kelas: {{ $kelasWali->pluck('nama_kelas')->join(', ') }}
                    </span>
                @endif
            </p>
        </div>
    </div>

    {{-- Filter Bar & Stats --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-4">
        
        {{-- Status Tabs --}}
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-4">
            @php
                $statusTabs = [
                    'Semua' => ['label' => 'Semua Pengajuan', 'count' => $countSemua, 'color' => 'bg-slate-100 text-slate-700', 'icon' => 'solar:layers-bold'],
                    'Menunggu' => ['label' => 'Menunggu Verifikasi', 'count' => $countMenunggu, 'color' => 'bg-amber-100 text-amber-700 font-bold', 'icon' => 'solar:clock-circle-bold'],
                    'Disetujui' => ['label' => 'Disetujui', 'count' => $countDisetujui, 'color' => 'bg-emerald-100 text-emerald-700', 'icon' => 'solar:check-circle-bold'],
                    'Ditolak' => ['label' => 'Ditolak', 'count' => $countDitolak, 'color' => 'bg-rose-100 text-rose-700', 'icon' => 'solar:close-circle-bold'],
                ];
            @endphp

            @foreach($statusTabs as $tabKey => $tab)
                <a href="{{ route('guru.perizinan', array_merge(request()->query(), ['status' => $tabKey])) }}"
                   class="px-4 py-2 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 {{ $statusFilter === $tabKey ? 'bg-blue-500 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <iconify-icon icon="{{ $tab['icon'] }}" class="text-base {{ $statusFilter === $tabKey ? 'text-white' : 'text-slate-500' }}"></iconify-icon>
                    <span>{{ $tab['label'] }}</span>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $statusFilter === $tabKey ? 'bg-white/20 text-white' : $tab['color'] }}">
                        {{ $tab['count'] }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Search & Class Filter Form --}}
        <form method="GET" action="{{ route('guru.perizinan') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <input type="hidden" name="status" value="{{ $statusFilter }}">

            <div class="sm:col-span-6 md:col-span-7 relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama siswa atau NISN..." 
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <div class="sm:col-span-4 md:col-span-3">
                <select name="kelas_id" 
                        onchange="this.form.submit()" 
                        class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ $kelasFilter == $kelas->id_kelas ? 'selected' : '' }}>
                            Kelas {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold text-sm px-4 py-2.5 rounded-xl transition-colors">
                    Filter
                </button>
                @if($search || $kelasFilter || $statusFilter !== 'Semua')
                    <a href="{{ route('guru.perizinan') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center" title="Reset filter">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- List Perizinan --}}
    <div class="space-y-4">
        @forelse($perizinanList as $item)
            @php
                $siswa = $item->siswa;
                $tglMulai = $item->tgl_mulai ? \Carbon\Carbon::parse($item->tgl_mulai)->format('d M Y') : '-';
                $tglSelesai = $item->tgl_selesai ? \Carbon\Carbon::parse($item->tgl_selesai)->format('d M Y') : '-';
                $durasi = ($tglMulai === $tglSelesai || !$item->tgl_selesai) ? $tglMulai : "{$tglMulai} s/d {$tglSelesai}";
                
                $namaKelas = '-';
                if ($siswa) {
                    if ($siswa->kelas) {
                        $namaKelas = $siswa->kelas->nama_kelas;
                    } elseif ($siswa->kelasAktif && $siswa->kelasAktif->count() > 0) {
                        $namaKelas = $siswa->kelasAktif->first()->nama_kelas;
                    }
                }
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sm:p-6 hover:shadow-md transition-all">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    
                    {{-- Student & Permission Info --}}
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-200 flex items-center justify-center flex-shrink-0 text-blue-600 font-bold text-lg">
                            {{ $siswa ? strtoupper(substr($siswa->nama_lengkap, 0, 1)) : '?' }}
                        </div>
                        
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base sm:text-lg font-bold text-slate-800">
                                    {{ $siswa ? $siswa->nama_lengkap : 'Siswa Tidak Diketahui' }}
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    Kelas {{ $namaKelas }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    NISN: {{ $siswa->nisn ?? '-' }}
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-3 text-xs sm:text-sm text-slate-600 pt-1">
                                {{-- Jenis Izin Badge --}}
                                @if($item->jenis_izin === 'Sakit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold text-xs bg-red-100 text-red-700 border border-red-200">
                                        <iconify-icon icon="solar:stethoscope-bold" class="text-sm mr-1.5 text-red-600"></iconify-icon>
                                        Sakit
                                    </span>
                                @elseif($item->jenis_izin === 'Izin')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold text-xs bg-amber-100 text-amber-700 border border-amber-200">
                                        <iconify-icon icon="solar:document-text-bold" class="text-sm mr-1.5 text-amber-600"></iconify-icon>
                                        Izin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-bold text-xs bg-slate-100 text-slate-700 border border-slate-200">
                                        <iconify-icon icon="solar:file-text-bold" class="text-sm mr-1.5 text-slate-600"></iconify-icon>
                                        {{ $item->jenis_izin }}
                                    </span>
                                @endif

                                <span class="flex items-center text-slate-500">
                                    <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $durasi }}
                                </span>

                                <span class="text-slate-400">•</span>

                                <span class="text-slate-500">
                                    Diajukan oleh: <strong class="text-slate-700">{{ $item->orangTua->name ?? 'Orang Tua' }}</strong>
                                </span>
                            </div>

                            {{-- Keterangan / Alasan --}}
                            <p class="text-sm text-slate-600 pt-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <span class="font-medium text-slate-700">Alasan:</span> "{{ $item->keterangan }}"
                            </p>

                            {{-- Bukti Dokumen jika ada --}}
                            @if($item->file_bukti)
                                <div class="pt-1">
                                    <button type="button" 
                                            onclick="previewDokumen('{{ asset('storage/' . $item->file_bukti) }}')" 
                                            class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Lihat Surat / Bukti Foto
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Status & Action Buttons --}}
                    <div class="flex flex-col sm:flex-row lg:flex-col items-start lg:items-end justify-between gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-slate-100">
                        
                        {{-- Status Badge --}}
                        <div>
                            @if($item->status === 'Menunggu')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                    Menunggu Persetujuan
                                </span>
                            @elseif($item->status === 'Disetujui')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Disetujui
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200 inline-flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Ditolak
                                </span>
                            @endif
                        </div>

                        {{-- Approver info if already processed --}}
                        @if($item->approval_date)
                            <p class="text-xs text-slate-400">
                                Oleh: <span class="font-medium text-slate-600">{{ $item->approver->name ?? 'Guru/Wali' }}</span>
                                <br>{{ \Carbon\Carbon::parse($item->approval_date)->format('d M Y H:i') }}
                            </p>
                        @endif

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 mt-2">
                            @if($item->status !== 'Disetujui')
                                <form method="POST" action="{{ route('guru.perizinan.update_status', $item->id_izin) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="Disetujui">
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin menyetujui perizinan ini?')"
                                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                                        <iconify-icon icon="solar:check-circle-bold" class="text-sm"></iconify-icon>
                                        Setujui
                                    </button>
                                </form>
                            @endif

                            @if($item->status !== 'Ditolak')
                                <form method="POST" action="{{ route('guru.perizinan.update_status', $item->id_izin) }}">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin menolak perizinan ini?')"
                                            class="px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors flex items-center gap-1.5">
                                        <iconify-icon icon="solar:close-circle-bold" class="text-sm"></iconify-icon>
                                        Tolak
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h4 class="text-lg font-bold text-slate-700">Tidak ada data perizinan</h4>
                <p class="text-sm text-slate-400 mt-1">Belum ada pengajuan izin siswa yang sesuai dengan filter yang dipilih.</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($perizinanList->hasPages())
            <div class="bg-white p-4 rounded-xl border border-slate-200">
                {{ $perizinanList->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Pratinjau Dokumen --}}
<div id="modalPreviewDoc" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-lg">Bukti Surat / Dokumen Izin</h3>
            <button onclick="closePreviewDoc()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">✕</button>
        </div>
        <div class="max-h-[70vh] overflow-y-auto flex items-center justify-center bg-slate-50 rounded-xl p-3">
            <img id="previewImageDoc" src="" alt="Bukti Dokumen" class="max-w-full h-auto rounded-lg object-contain">
        </div>
        <div class="flex justify-end">
            <button onclick="closePreviewDoc()" class="px-5 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewDokumen(url) {
    document.getElementById('previewImageDoc').src = url;
    document.getElementById('modalPreviewDoc').classList.remove('hidden');
}
function closePreviewDoc() {
    document.getElementById('modalPreviewDoc').classList.add('hidden');
    document.getElementById('previewImageDoc').src = '';
}
document.getElementById('modalPreviewDoc').addEventListener('click', function(e) {
    if (e.target === this) closePreviewDoc();
});
</script>
@endpush

@endsection
