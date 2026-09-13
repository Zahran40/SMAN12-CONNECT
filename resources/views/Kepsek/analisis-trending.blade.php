@extends('layouts.kepsek.app')

@section('title', 'Analisis Trending')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Analisis Trending</h1>
        <p class="text-slate-500 text-sm mt-1">Grafik perkembangan akademik &amp; kehadiran per periode</p>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Ajaran</label>
                <select id="filterTahunAjaran" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Sem {{ $ta->semester }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                <select id="filterKategori" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="akademik">Akademik (Nilai)</option>
                    <option value="kehadiran">Kehadiran</option>
                    <option value="keuangan">Keuangan</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadTrending()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-4 py-2 text-sm transition">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    Analisis
                </button>
            </div>
        </div>
    </div>

    {{-- Main Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4" id="chartTitle">Tren Perkembangan</h3>
        <div class="h-96">
            <canvas id="chartTrending"></canvas>
        </div>
    </div>

    {{-- Comparison Chart --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Perbandingan Antar Periode</h3>
            <div class="h-72">
                <canvas id="chartComparison"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Ringkasan Statistik</h3>
            <div id="summaryStats" class="space-y-4">
                <div class="bg-slate-50 rounded-lg p-4 text-center text-slate-400">Klik "Analisis" untuk memulai</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let chartTrending = null;
let chartComparison = null;

function loadTrending() {
    const tahunAjaran = document.getElementById('filterTahunAjaran').value;
    const kategori = document.getElementById('filterKategori').value;

    document.getElementById('chartTitle').textContent = `Tren ${kategori.charAt(0).toUpperCase() + kategori.slice(1)}`;

    fetch(`/api/pimpinan/analisis-trending?tahun_ajaran_id=${tahunAjaran}&kategori=${kategori}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        renderTrendingChart(d, kategori);
        renderSummary(d);
    })
    .catch(() => {
        document.getElementById('summaryStats').innerHTML = '<div class="bg-red-50 rounded-lg p-4 text-center text-red-500">Gagal memuat data</div>';
    });
}

function renderTrendingChart(data, kategori) {
    if (chartTrending) chartTrending.destroy();

    const items = data.trend || data.items || [];
    const labels = items.map(i => i.label || i.bulan || i.periode || '');
    const values = items.map(i => i.value || i.nilai || i.jumlah || 0);

    const colors = {
        akademik: { border: '#6366f1', bg: 'rgba(99,102,241,0.1)' },
        kehadiran: { border: '#10b981', bg: 'rgba(16,185,129,0.1)' },
        keuangan: { border: '#f59e0b', bg: 'rgba(245,158,11,0.1)' }
    };

    const c = colors[kategori] || colors.akademik;

    chartTrending = new Chart(document.getElementById('chartTrending').getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: kategori.charAt(0).toUpperCase() + kategori.slice(1),
                data: values,
                borderColor: c.border,
                backgroundColor: c.bg,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointBackgroundColor: c.border
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: kategori !== 'akademik' } },
            plugins: { legend: { display: false } }
        }
    });

    // Comparison bar
    if (chartComparison) chartComparison.destroy();
    const compLabels = items.slice(-6).map(i => i.label || i.bulan || '');
    const compValues = items.slice(-6).map(i => i.value || i.nilai || 0);

    chartComparison = new Chart(document.getElementById('chartComparison').getContext('2d'), {
        type: 'bar',
        data: {
            labels: compLabels,
            datasets: [{
                label: 'Nilai',
                data: compValues,
                backgroundColor: c.border + '99',
                borderColor: c.border,
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
}

function renderSummary(data) {
    const summary = data.summary || {};
    const container = document.getElementById('summaryStats');
    container.innerHTML = `
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">${summary.rata_rata || summary.average || '-'}</p>
                <p class="text-xs text-slate-500 mt-1">Rata-rata</p>
            </div>
            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-emerald-600">${summary.tertinggi || summary.max || '-'}</p>
                <p class="text-xs text-slate-500 mt-1">Tertinggi</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-red-600">${summary.terendah || summary.min || '-'}</p>
                <p class="text-xs text-slate-500 mt-1">Terendah</p>
            </div>
            <div class="bg-amber-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-amber-600">${summary.pertumbuhan || summary.growth || '-'}</p>
                <p class="text-xs text-slate-500 mt-1">Pertumbuhan</p>
            </div>
        </div>
    `;
}
</script>
@endpush
