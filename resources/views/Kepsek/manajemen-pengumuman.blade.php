@extends('layouts.kepsek.app')

@section('title', 'Manajemen Pengumuman')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengumuman</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola, setujui, dan publikasi pengumuman sekolah</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-5 text-center">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Aktif</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $pengumumanAktif->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-5 text-center">
            <p class="text-xs font-semibold text-amber-500 uppercase">Menunggu Persetujuan</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $pengumumanPending->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $pengumumanAktif->count() + $pengumumanPending->count() }}</p>
        </div>
    </div>

    {{-- Pending Pengumuman --}}
    @if($pengumumanPending->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="p-6 border-b border-amber-200 bg-amber-50">
            <h3 class="text-lg font-semibold text-amber-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Menunggu Persetujuan
            </h3>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($pengumumanPending as $p)
            <div class="p-6 hover:bg-slate-50 transition" id="pending-{{ $p->id_pengumuman }}">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-800">{{ $p->judul }}</h4>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ strip_tags($p->isi) }}</p>
                        <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                            <span>Target: {{ $p->target_role ?? 'Semua' }}</span>
                            <span>Dibuat: {{ \Carbon\Carbon::parse($p->tgl_publikasi)->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button onclick="approvePengumuman({{ $p->id_pengumuman }})" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg px-4 py-2 transition">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Setujui
                        </button>
                        <button onclick="toggleDetail({{ $p->id_pengumuman }})" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg px-4 py-2 transition">
                            Detail
                        </button>
                    </div>
                </div>
                <div class="hidden mt-4 bg-slate-50 rounded-lg p-4 text-sm text-slate-600" id="detail-{{ $p->id_pengumuman }}">
                    {!! nl2br(e($p->isi)) !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Pengumuman Aktif --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Pengumuman Aktif</h3>
        </div>
        @if($pengumumanAktif->isEmpty())
            <div class="p-12 text-center text-slate-400">Belum ada pengumuman aktif</div>
        @else
        <div class="divide-y divide-slate-100">
            @foreach($pengumumanAktif as $p)
            <div class="p-6 hover:bg-slate-50 transition">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="font-semibold text-slate-800">{{ $p->judul }}</h4>
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">Aktif</span>
                        </div>
                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ strip_tags($p->isi) }}</p>
                        <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                            <span>Target: {{ $p->target_role ?? 'Semua' }}</span>
                            <span>Publikasi: {{ \Carbon\Carbon::parse($p->tgl_publikasi)->translatedFormat('d M Y') }}</span>
                            @if($p->tgl_berakhir)
                            <span>Berakhir: {{ \Carbon\Carbon::parse($p->tgl_berakhir)->translatedFormat('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <button onclick="toggleDetail('aktif-{{ $p->id_pengumuman }}')" class="text-sm text-blue-600 hover:text-blue-800 font-medium shrink-0">
                        Lihat Detail
                    </button>
                </div>
                <div class="hidden mt-4 bg-slate-50 rounded-lg p-4 text-sm text-slate-600" id="detail-aktif-{{ $p->id_pengumuman }}">
                    {!! nl2br(e($p->isi)) !!}
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleDetail(id) {
    const el = document.getElementById('detail-' + id);
    if (el) el.classList.toggle('hidden');
}

function approvePengumuman(id) {
    if (!confirm('Setujui dan publikasikan pengumuman ini?')) return;

    fetch(`/api/pimpinan/pengumuman/${id}/approve`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success || data.message) {
            const el = document.getElementById('pending-' + id);
            if (el) {
                el.style.opacity = '0.5';
                el.innerHTML = '<div class="p-4 text-center text-emerald-600 font-medium">Pengumuman berhasil disetujui!</div>';
                setTimeout(() => el.remove(), 2000);
            }
        }
    })
    .catch(() => alert('Gagal menyetujui pengumuman'));
}
</script>
@endpush
