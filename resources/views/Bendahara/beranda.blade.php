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
            <a href="{{ route('bendahara.pembayaran.index') }}" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-sm font-medium text-slate-700 text-center">Pembayaran</span>
            </a>
            <a href="{{ route('bendahara.rekonsiliasi-bank') }}" class="flex flex-col items-center gap-2 p-4 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z"/></svg>
                <span class="text-sm font-medium text-slate-700 text-center">Rekonsiliasi</span>
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
            <p class="text-xs font-semibold text-slate-400 uppercase">Siswa Menunggak</p>
            <p class="text-xl font-bold text-amber-600 mt-1" id="siswaMenunggak">-</p>
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
            document.getElementById('rekonPending').textContent = d.pending_tasks.rekonsiliasi_pending || 0;
        }
        if (d.tunggakan) {
            document.getElementById('siswaMenunggak').textContent = (d.tunggakan.jumlah_siswa || 0) + ' Siswa';
        }

        // Helpers
        const monthNamesShort = {
            '01': 'Jan', '02': 'Feb', '03': 'Mar', '04': 'Apr', '05': 'Mei', '06': 'Jun',
            '07': 'Jul', '08': 'Agu', '09': 'Sep', '10': 'Okt', '11': 'Nov', '12': 'Des'
        };
        const formatBulanLabel = function(ym) {
            if (!ym) return '';
            const parts = ym.split('-');
            if (parts.length === 2) {
                return (monthNamesShort[parts[1]] || parts[1]) + ' ' + parts[0].substring(2);
            }
            return ym;
        };
        const formatRupiahShort = function(v) {
            if (v >= 1000000000) return 'Rp ' + (v / 1000000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + 'M';
            if (v >= 1000000) return 'Rp ' + (v / 1000000).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + 'jt';
            if (v >= 1000) return 'Rp ' + (v / 1000).toLocaleString('id-ID', { maximumFractionDigits: 0 }) + 'rb';
            return 'Rp ' + Number(v).toLocaleString('id-ID');
        };

        // Chart Pemasukan
        const grafik = d.grafik_pemasukan || [];
        if (grafik.length > 0) {
            new Chart(document.getElementById('chartPemasukan').getContext('2d'), {
                type: 'line',
                data: {
                    labels: grafik.map(g => formatBulanLabel(g.bulan)),
                    datasets: [{
                        label: 'Pemasukan',
                        data: grafik.map(g => g.total),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: formatRupiahShort
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Pemasukan: Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
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
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.raw || 0;
                                    return ' ' + context.label + ': Rp ' + Number(val).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    }).catch(() => {});
});
</script>
@endpush
