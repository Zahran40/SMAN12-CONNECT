@extends('layouts.bendahara.app')

@section('title', 'Dashboard Bendahara')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Keuangan</h1>
            <p class="text-slate-500 text-sm mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium text-sm">Bulan {{ now()->translatedFormat('F Y') }}</span>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Pemasukan Bulan Ini</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($stats['total_pemasukan_bulan_ini'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Total Tunggakan</p>
                    <p class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($stats['total_tunggakan'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Pembayaran Pending</p>
                    <p class="text-xl font-bold text-amber-600 mt-1">{{ number_format($stats['pembayaran_pending']) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Transaksi Lunas</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($stats['total_lunas_bulan_ini']) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Pemasukan 12 Bulan Terakhir</h3>
            <div class="h-72">
                <canvas id="chartPemasukan"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Transaksi per Metode</h3>
            <div class="h-72 flex items-center justify-center">
                <canvas id="chartMetode"></canvas>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Menu Cepat</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <a href="{{ route('bendahara.manajemen-tagihan-spp') }}" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <span class="text-sm font-medium text-slate-700 text-center">Tagihan SPP</span>
            </a>
            <a href="{{ route('bendahara.verifikasi-pembayaran') }}" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium text-slate-700 text-center">Verifikasi</span>
            </a>
            <a href="{{ route('bendahara.tunggakan-reminder') }}" class="flex flex-col items-center gap-2 p-4 bg-red-50 rounded-lg hover:bg-red-100 transition">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                <span class="text-sm font-medium text-slate-700 text-center">Tunggakan</span>
            </a>
            <a href="{{ route('bendahara.rekap-pembayaran') }}" class="flex flex-col items-center gap-2 p-4 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="text-sm font-medium text-slate-700 text-center">Rekap</span>
            </a>
        </div>
    </div>

    {{-- Pending Tasks --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="pendingTasks">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Diskon Aktif</p>
            <p class="text-xl font-bold text-blue-600 mt-1" id="diskonAktif">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Refund Pending</p>
            <p class="text-xl font-bold text-amber-600 mt-1" id="refundPending">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Rekonsiliasi Pending</p>
            <p class="text-xl font-bold text-blue-600 mt-1" id="rekonPending">-</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/bendahara/dashboard', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;

        // Pending tasks
        if (d.pending_tasks) {
            document.getElementById('diskonAktif').textContent = d.pending_tasks.diskon_aktif || 0;
            document.getElementById('refundPending').textContent = d.pending_tasks.refund_pending || 0;
            document.getElementById('rekonPending').textContent = d.pending_tasks.rekonsiliasi_pending || 0;
        }

        // Chart Pemasukan
        const grafik = d.grafik_pemasukan || [];
        if (grafik.length > 0) {
            new Chart(document.getElementById('chartPemasukan').getContext('2d'), {
                type: 'line',
                data: {
                    labels: grafik.map(g => g.bulan),
                    datasets: [{
                        label: 'Pemasukan',
                        data: grafik.map(g => g.total),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt' } } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Chart Metode
        const metode = d.transaksi_per_metode || [];
        if (metode.length > 0) {
            new Chart(document.getElementById('chartMetode').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: metode.map(m => m.metode_pembayaran || 'Lainnya'),
                    datasets: [{
                        data: metode.map(m => m.total),
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }
    }).catch(() => {});
});
</script>
@endpush
