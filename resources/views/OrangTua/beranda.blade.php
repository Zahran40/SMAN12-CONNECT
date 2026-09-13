@extends('layouts.orangtua.app')

@section('title', 'Dashboard Orang Tua')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Orang Tua</h1>
            <p class="text-slate-500 text-sm mt-1">Informasi lengkap anak Anda</p>
        </div>
        <div class="text-sm text-slate-500">
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
        </div>
    </div>

    {{-- Info Siswa --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 sm:p-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center shrink-0 border border-blue-100">
                <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-slate-500 text-sm mt-0.5">{{ $siswa->nis }} &bull; Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="dashboardCards">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Kehadiran</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1" id="cardKehadiran">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Rata-rata Nilai</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="cardNilai">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Tugas Belum Selesai</p>
            <p class="text-2xl font-bold text-amber-600 mt-1" id="cardTugas">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Status SPP</p>
            <p class="text-2xl font-bold mt-1" id="cardSpp">-</p>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <a href="{{ route('orangtua.presensi-realtime') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-300 hover:shadow-md transition">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-xs font-medium text-slate-700 text-center">Presensi</span>
        </a>
        <a href="{{ route('orangtua.monitoring-nilai') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-300 hover:shadow-md transition">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span class="text-xs font-medium text-slate-700 text-center">Nilai</span>
        </a>
        <a href="{{ route('orangtua.jadwal-pelajaran') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-300 hover:shadow-md transition">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span class="text-xs font-medium text-slate-700 text-center">Jadwal</span>
        </a>
        <a href="{{ route('orangtua.riwayat-pembayaran-spp') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-300 hover:shadow-md transition">
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-xs font-medium text-slate-700 text-center">Pembayaran</span>
        </a>
        <a href="{{ route('orangtua.notifikasi-pengumuman') }}" class="flex flex-col items-center gap-2 p-4 bg-white rounded-xl shadow-sm border border-slate-200 hover:border-blue-300 hover:shadow-md transition">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="text-xs font-medium text-slate-700 text-center">Pengumuman</span>
        </a>
    </div>

    {{-- Pengumuman Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Pengumuman Terbaru</h3>
        <div id="pengumumanList" class="space-y-3">
            <div class="text-center text-slate-400 py-4 animate-pulse">Memuat...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/orangtua/dashboard', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        document.getElementById('cardKehadiran').textContent = (d.persentase_kehadiran || d.kehadiran || '-') + '%';
        document.getElementById('cardNilai').textContent = d.rata_rata_nilai || d.nilai_rata_rata || '-';
        document.getElementById('cardTugas').textContent = d.tugas_belum_selesai || d.tugas_pending || '0';

        const spp = d.status_spp || d.spp;
        const sppEl = document.getElementById('cardSpp');
        if (spp === 'Lunas' || spp === 'lunas') {
            sppEl.textContent = 'Lunas';
            sppEl.classList.add('text-emerald-600');
        } else {
            sppEl.textContent = spp || 'Belum Lunas';
            sppEl.classList.add('text-red-600');
        }
    }).catch(() => {});

    fetch('/api/orangtua/pengumuman', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const items = data.data || data || [];
        const container = document.getElementById('pengumumanList');
        if (!Array.isArray(items) || items.length === 0) {
            container.innerHTML = '<div class="text-center text-slate-400 py-4">Belum ada pengumuman</div>';
            return;
        }
        container.innerHTML = items.slice(0, 5).map(p => `
            <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-lg">
                <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 shrink-0"></div>
                <div class="min-w-0">
                    <p class="font-medium text-slate-800 text-sm">${p.judul || ''}</p>
                    <p class="text-xs text-slate-400 mt-1">${p.tgl_publikasi || ''}</p>
                </div>
            </div>
        `).join('');
    }).catch(() => {});
});
</script>
@endpush
