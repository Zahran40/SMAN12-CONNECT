@extends('layouts.orangtua.app')

@section('title', 'Laporan Perilaku')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Perilaku</h1>
        <p class="text-slate-500 text-sm mt-1">Catatan perilaku {{ $siswa->nama_lengkap }}</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-5 text-center">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Positif</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1" id="countPositif">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5 text-center">
            <p class="text-xs font-semibold text-red-500 uppercase">Negatif</p>
            <p class="text-2xl font-bold text-red-600 mt-1" id="countNegatif">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Poin</p>
            <p class="text-2xl font-bold text-slate-800 mt-1" id="totalPoin">-</p>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Catatan Perilaku</h3>
        <div id="timelinePerilaku" class="space-y-4">
            <div class="text-center text-slate-400 py-8 animate-pulse">Memuat data...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/orangtua/perilaku', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        const items = d.laporan || d.items || d || [];
        const summary = d.summary || d.ringkasan || {};

        document.getElementById('countPositif').textContent = summary.positif || 0;
        document.getElementById('countNegatif').textContent = summary.negatif || 0;
        document.getElementById('totalPoin').textContent = summary.total_poin || 0;

        const container = document.getElementById('timelinePerilaku');
        if (!Array.isArray(items) || items.length === 0) {
            container.innerHTML = '<div class="text-center text-slate-400 py-8">Belum ada catatan perilaku</div>';
            return;
        }

        container.innerHTML = items.map(item => {
            const isPositif = item.jenis === 'Positif' || item.jenis === 'positif' || item.poin > 0;
            return `<div class="flex gap-4 p-4 rounded-lg ${isPositif ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200'}">
                <div class="w-10 h-10 shrink-0 rounded-full flex items-center justify-center ${isPositif ? 'bg-emerald-200' : 'bg-red-200'}">
                    ${isPositif 
                        ? '<svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>'
                        : '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>'
                    }
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-semibold text-slate-800">${item.kategori || item.jenis || '-'}</h4>
                        <span class="text-xs font-medium px-2 py-1 rounded-full ${isPositif ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'}">
                            ${isPositif ? '+' : ''}${item.poin || 0} poin
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mt-1">${item.deskripsi || item.keterangan || '-'}</p>
                    <p class="text-xs text-slate-400 mt-2">${item.tanggal || item.created_at || ''} ${item.guru ? '• Oleh: ' + item.guru : ''}</p>
                </div>
            </div>`;
        }).join('');
    })
    .catch(() => {
        document.getElementById('timelinePerilaku').innerHTML = '<div class="text-center text-red-500 py-8">Gagal memuat data</div>';
    });
});
</script>
@endpush
