@extends('layouts.kepsek.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Keuangan</h1>
        <p class="text-slate-500 text-sm mt-1">Rekap pembayaran SPP tahun {{ date('Y') }}</p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Total Pemasukan</p>
                    <p class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Total Tunggakan</p>
                    <p class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($ringkasan['total_tunggakan'], 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Total Transaksi</p>
                    <p class="text-xl font-bold text-slate-800 mt-1">{{ number_format($ringkasan['total_transaksi']) }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase">Siswa Menunggak</p>
                    <p class="text-xl font-bold text-amber-600 mt-1">{{ number_format($ringkasan['siswa_menunggak']) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Pemasukan Per Bulan</h3>
            <div class="h-72">
                <canvas id="chartPemasukanBulanan"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Rasio Lunas vs Tunggakan</h3>
            <div class="h-72 flex items-center justify-center">
                <canvas id="chartRasio"></canvas>
            </div>
        </div>
    </div>

    {{-- Detail Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Rincian Pemasukan Per Bulan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Bulan</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Pemasukan</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jumlah Transaksi</th>
                    </tr>
                </thead>
                <tbody id="tabelPemasukan">
                    <tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Rasio chart
    const pemasukan = {{ $ringkasan['total_pemasukan'] }};
    const tunggakan = {{ $ringkasan['total_tunggakan'] }};

    new Chart(document.getElementById('chartRasio').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Lunas', 'Tunggakan'],
            datasets: [{
                data: [pemasukan, tunggakan],
                backgroundColor: ['#10b981', '#ef4444'],
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

    loadPemasukanBulanan();
});

function loadPemasukanBulanan() {
    fetch('/api/pimpinan/laporan-keuangan', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const items = data.data?.per_bulan || data.per_bulan || [];
        const namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const labels = items.map(i => namaBulan[(i.bulan || 1) - 1] || i.bulan);
        const values = items.map(i => i.total || i.pemasukan || 0);

        new Chart(document.getElementById('chartPemasukanBulanan').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pemasukan',
                    data: values,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + (v / 1000000).toFixed(0) + 'jt' }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });

        const tbody = document.getElementById('tabelPemasukan');
        if (items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-slate-400">Belum ada data</td></tr>';
            return;
        }
        tbody.innerHTML = items.map(i => `<tr class="border-t border-slate-100 hover:bg-slate-50">
            <td class="px-6 py-3 font-medium">${namaBulan[(i.bulan || 1) - 1] || i.bulan} ${i.tahun || ''}</td>
            <td class="px-6 py-3 text-right font-semibold text-emerald-600">Rp ${(i.total || i.pemasukan || 0).toLocaleString('id-ID')}</td>
            <td class="px-6 py-3 text-center">${i.jumlah_transaksi || i.count || '-'}</td>
        </tr>`).join('');
    })
    .catch(() => {
        document.getElementById('tabelPemasukan').innerHTML = '<tr><td colspan="3" class="px-6 py-8 text-center text-red-500">Gagal memuat data</td></tr>';
    });
}
</script>
@endpush
