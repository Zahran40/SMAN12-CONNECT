@extends('layouts.orangtua.app')

@section('title', 'Notifikasi & Pengumuman')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Notifikasi & Pengumuman</h1>
        <p class="text-slate-500 text-sm mt-1">Informasi terbaru dari sekolah</p>
    </div>

    {{-- Filter --}}
    <div class="flex gap-2">
        <button onclick="filterPengumuman('semua')" class="px-4 py-2 text-sm font-medium rounded-lg bg-blue-600 text-white filter-btn" data-filter="semua">Semua</button>
        <button onclick="filterPengumuman('belum')" class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 filter-btn" data-filter="belum">Belum Dibaca</button>
        <button onclick="filterPengumuman('sudah')" class="px-4 py-2 text-sm font-medium rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 filter-btn" data-filter="sudah">Sudah Dibaca</button>
    </div>

    {{-- List --}}
    <div id="listPengumuman" class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center text-slate-400 animate-pulse">Memuat pengumuman...</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let allPengumuman = [];
let currentFilter = 'semua';

document.addEventListener('DOMContentLoaded', loadPengumuman);

function loadPengumuman() {
    fetch('/api/orangtua/pengumuman', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        allPengumuman = data.data || data || [];
        renderPengumuman();
    })
    .catch(() => {
        document.getElementById('listPengumuman').innerHTML = '<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center text-red-500">Gagal memuat pengumuman</div>';
    });
}

function filterPengumuman(filter) {
    currentFilter = filter;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        const active = btn.dataset.filter === filter;
        btn.classList.toggle('bg-blue-600', active);
        btn.classList.toggle('text-white', active);
        btn.classList.toggle('bg-slate-100', !active);
        btn.classList.toggle('text-slate-700', !active);
    });
    renderPengumuman();
}

function renderPengumuman() {
    let items = allPengumuman;
    if (currentFilter === 'belum') items = items.filter(p => !p.dibaca && !p.is_read);
    else if (currentFilter === 'sudah') items = items.filter(p => p.dibaca || p.is_read);

    const container = document.getElementById('listPengumuman');
    if (items.length === 0) {
        container.innerHTML = '<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8 text-center text-slate-400">Tidak ada pengumuman</div>';
        return;
    }

    container.innerHTML = items.map(p => {
        const isRead = p.dibaca || p.is_read;
        return `<div class="bg-white rounded-xl shadow-sm border ${isRead ? 'border-slate-200' : 'border-blue-200 bg-blue-50/30'} p-6 hover:shadow-md transition" id="pengumuman-${p.id_pengumuman || p.id}">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center ${isRead ? 'bg-slate-100' : 'bg-blue-100'}">
                    ${isRead 
                        ? '<svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>'
                        : '<svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>'
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <h4 class="font-semibold text-slate-800 ${isRead ? '' : 'text-blue-800'}">${p.judul || '-'}</h4>
                        ${!isRead ? `<button onclick="markRead(${p.id_pengumuman || p.id})" class="text-xs text-blue-600 hover:text-blue-800 font-medium shrink-0">Tandai Dibaca</button>` : ''}
                    </div>
                    <p class="text-sm text-slate-600 mt-2 line-clamp-3">${p.isi || ''}</p>
                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                        <span>${p.tgl_publikasi || ''}</span>
                        <span>Target: ${p.target_role || 'Semua'}</span>
                    </div>
                </div>
            </div>
        </div>`;
    }).join('');
}

function markRead(id) {
    fetch(`/api/orangtua/pengumuman/${id}/read`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin'
    })
    .then(() => {
        const item = allPengumuman.find(p => (p.id_pengumuman || p.id) == id);
        if (item) { item.dibaca = true; item.is_read = true; }
        renderPengumuman();
    });
}
</script>
@endpush
