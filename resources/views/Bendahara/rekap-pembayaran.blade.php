@extends('layouts.bendahara.app')

@section('title', 'Rekap Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Rekap Pembayaran</h1>
        <p class="text-slate-500 text-sm mt-1">Laporan rekap pembayaran harian, bulanan, dan tahunan</p>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Tipe Rekap</label>
                <select id="tipeRekap" onchange="toggleFilter()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="bulanan">Bulanan</option>
                    <option value="harian">Harian</option>
                    <option value="tahunan">Tahunan</option>
                </select>
            </div>
            <div id="fieldTahun">
                <label class="block text-sm font-medium text-slate-600 mb-1">Tahun</label>
                <input type="number" id="filterTahun" value="{{ date('Y') }}" min="2020" max="2030" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="fieldBulan">
                <label class="block text-sm font-medium text-slate-600 mb-1">Bulan</label>
                <select id="filterBulan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $i == date('m') ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div id="fieldTanggal" class="hidden">
                <label class="block text-sm font-medium text-slate-600 mb-1">Tanggal</label>
                <input type="date" id="filterTanggal" value="{{ date('Y-m-d') }}" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button onclick="loadData()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">Tampilkan Rekap</button>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Transaksi</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="totalTransaksi">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Nominal</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="totalNominal">-</p>
        </div>
    </div>

    {{-- Chart (Tahunan) --}}
    <div id="chartContainer" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hidden">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Grafik Pemasukan</h3>
        <div class="h-72"><canvas id="chartRekap"></canvas></div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr id="tableHeader"></tr>
                </thead>
                <tbody id="dataBody" class="divide-y divide-slate-100">
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">Pilih filter lalu klik "Tampilkan Rekap"</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let chartInstance = null;

function toggleFilter() {
    const tipe = document.getElementById('tipeRekap').value;
    document.getElementById('fieldTahun').classList.toggle('hidden', tipe === 'harian');
    document.getElementById('fieldBulan').classList.toggle('hidden', tipe !== 'bulanan');
    document.getElementById('fieldTanggal').classList.toggle('hidden', tipe !== 'harian');
}

function loadData() {
    const tipe = document.getElementById('tipeRekap').value;
    let url = `/api/bendahara/rekap-pembayaran?tipe=${tipe}`;
    if (tipe === 'harian') { url += `&tanggal=${document.getElementById('filterTanggal').value}`; }
    else if (tipe === 'bulanan') { url += `&tahun=${document.getElementById('filterTahun').value}&bulan=${document.getElementById('filterBulan').value}`; }
    else { url += `&tahun=${document.getElementById('filterTahun').value}`; }

    document.getElementById('dataBody').innerHTML = '<tr><td colspan="6" class="text-center py-10 text-slate-400">Memuat...</td></tr>';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        const items = res.data || [];
        const summary = res.summary || {};
        document.getElementById('totalTransaksi').textContent = (summary.total_transaksi || 0).toLocaleString('id-ID');
        document.getElementById('totalNominal').textContent = 'Rp ' + (summary.total_nominal || 0).toLocaleString('id-ID');

        const header = document.getElementById('tableHeader');
        const tbody = document.getElementById('dataBody');
        const chartCont = document.getElementById('chartContainer');

        if (tipe === 'tahunan') {
            header.innerHTML = '<th class="text-left px-4 py-3 font-semibold">Bulan</th><th class="text-right px-4 py-3 font-semibold">Jumlah Transaksi</th><th class="text-right px-4 py-3 font-semibold">Total Nominal</th>';
            if (items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center py-10 text-slate-400">Tidak ada data</td></tr>';
            } else {
                tbody.innerHTML = items.map(i => `<tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">${i.bulan}</td>
                    <td class="px-4 py-3 text-right">${i.jumlah_transaksi}</td>
                    <td class="px-4 py-3 text-right font-mono">Rp ${Number(i.total_nominal).toLocaleString('id-ID')}</td>
                </tr>`).join('');
            }
            // Chart
            chartCont.classList.remove('hidden');
            if (chartInstance) chartInstance.destroy();
            chartInstance = new Chart(document.getElementById('chartRekap').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: items.map(i => i.bulan),
                    datasets: [{ label: 'Pemasukan', data: items.map(i => i.total_nominal), backgroundColor: '#10b981', borderRadius: 6 }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt' } } }, plugins: { legend: { display: false } } }
            });
        } else {
            chartCont.classList.add('hidden');
            header.innerHTML = '<th class="text-left px-4 py-3 font-semibold">Siswa</th><th class="text-left px-4 py-3 font-semibold">NIS</th><th class="text-left px-4 py-3 font-semibold">Bulan</th><th class="text-left px-4 py-3 font-semibold">Metode</th><th class="text-right px-4 py-3 font-semibold">Jumlah</th><th class="text-left px-4 py-3 font-semibold">Tgl Bayar</th>';
            if (items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-slate-400">Tidak ada data</td></tr>';
            } else {
                tbody.innerHTML = items.map(i => `<tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">${i.siswa?.nama_lengkap || '-'}</td>
                    <td class="px-4 py-3">${i.siswa?.nis || '-'}</td>
                    <td class="px-4 py-3">${i.bulan || '-'}</td>
                    <td class="px-4 py-3">${i.metode_pembayaran || '-'}</td>
                    <td class="px-4 py-3 text-right font-mono">Rp ${Number(i.jumlah_bayar).toLocaleString('id-ID')}</td>
                    <td class="px-4 py-3">${i.tgl_bayar || '-'}</td>
                </tr>`).join('');
            }
        }
    }).catch(() => {
        document.getElementById('dataBody').innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal memuat data</td></tr>';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    toggleFilter();
    loadData();
});
</script>
@endpush
