@extends('layouts.bendahara.app')

@section('title', 'Multi Payment Method')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Multi Payment Method</h1>
        <p class="text-slate-500 text-sm mt-1">Analisis metode pembayaran dan generate QR Code</p>
    </div>

    {{-- Payment Method Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="methodCards">
        <div class="col-span-full text-center py-10 text-slate-400">Memuat data metode pembayaran...</div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Distribusi Metode Pembayaran</h3>
            <div class="h-72 flex items-center justify-center"><canvas id="chartMetode"></canvas></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Trend Bulanan</h3>
            <div class="h-72"><canvas id="chartTrend"></canvas></div>
        </div>
    </div>

    {{-- Generate QR --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Generate QR Code Pembayaran</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">ID Pembayaran</label>
                <input type="number" id="qrPembayaranId" placeholder="Masukkan ID pembayaran" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex items-end">
                <button onclick="generateQR()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    Generate QR Code
                </button>
            </div>
        </div>
        <div id="qrResult" class="mt-6 hidden">
            <div class="flex flex-col items-center gap-4 bg-slate-50 rounded-lg p-6">
                <img id="qrImage" src="" alt="QR Code" class="w-48 h-48 rounded-lg shadow-md">
                <div class="text-center text-sm">
                    <p class="font-semibold text-slate-700" id="qrInfo"></p>
                    <p class="text-slate-400 mt-1">Scan QR Code untuk melakukan pembayaran</p>
                </div>
            </div>
        </div>
        <div id="qrError" class="mt-4 text-red-600 text-sm hidden"></div>
    </div>

    {{-- Revenue Analytics --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-slate-800">Revenue per Kelas</h3>
            <select id="tahunAnalytics" onchange="loadRevenueAnalytics()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                @for($y = date('Y'); $y >= date('Y')-2; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Kelas</th>
                        <th class="text-right px-4 py-3 font-semibold">Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody id="kelasBody" class="divide-y divide-slate-100">
                    <tr><td colspan="2" class="text-center py-6 text-slate-400">Memuat...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const colors = { 'Cash': '#10b981', 'Transfer Bank': '#3b82f6', 'QRIS': '#f59e0b', 'E-Wallet': '#8b5cf6', 'Lainnya': '#6b7280' };

function loadDashboard() {
    fetch('/api/bendahara/dashboard', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        const d = res.data || res;
        const metode = d.transaksi_per_metode || [];

        // Cards
        const container = document.getElementById('methodCards');
        if (metode.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-slate-400">Belum ada data pembayaran</div>';
        } else {
            container.innerHTML = metode.map(m => {
                const name = m.metode_pembayaran || 'Lainnya';
                const color = colors[name] || '#6b7280';
                return `<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full" style="background:${color}"></div>
                        <p class="text-xs font-semibold text-slate-400 uppercase">${name}</p>
                    </div>
                    <p class="text-xl font-bold text-slate-800 mt-2">${Number(m.total).toLocaleString('id-ID')} transaksi</p>
                </div>`;
            }).join('');
        }

        // Doughnut chart
        if (metode.length > 0) {
            new Chart(document.getElementById('chartMetode').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: metode.map(m => m.metode_pembayaran || 'Lainnya'),
                    datasets: [{ data: metode.map(m => m.total), backgroundColor: metode.map(m => colors[m.metode_pembayaran] || '#6b7280'), borderWidth: 2, borderColor: '#fff' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
            });
        }

        // Trend chart
        const grafik = d.grafik_pemasukan || [];
        if (grafik.length > 0) {
            new Chart(document.getElementById('chartTrend').getContext('2d'), {
                type: 'line',
                data: {
                    labels: grafik.map(g => g.bulan),
                    datasets: [{ label: 'Pemasukan', data: grafik.map(g => g.total), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4 }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(0) + 'jt' } } }, plugins: { legend: { display: false } } }
            });
        }
    }).catch(() => {
        document.getElementById('methodCards').innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Gagal memuat data</div>';
    });
}

function generateQR() {
    const id = document.getElementById('qrPembayaranId').value;
    if (!id) { alert('Masukkan ID pembayaran'); return; }

    fetch('/api/bendahara/generate-qr', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: JSON.stringify({ pembayaran_id: id })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            document.getElementById('qrImage').src = res.data.qr_code_url;
            document.getElementById('qrInfo').textContent = `Pembayaran #${res.data.pembayaran_id} - Rp ${Number(res.data.jumlah).toLocaleString('id-ID')}`;
            document.getElementById('qrResult').classList.remove('hidden');
            document.getElementById('qrError').classList.add('hidden');
        } else {
            document.getElementById('qrError').textContent = res.message || 'Gagal generate QR';
            document.getElementById('qrError').classList.remove('hidden');
            document.getElementById('qrResult').classList.add('hidden');
        }
    }).catch(() => {
        document.getElementById('qrError').textContent = 'Terjadi kesalahan';
        document.getElementById('qrError').classList.remove('hidden');
    });
}

function loadRevenueAnalytics() {
    const tahun = document.getElementById('tahunAnalytics').value;
    fetch(`/api/bendahara/revenue-analytics?tahun=${tahun}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        const kelas = res.data?.per_kelas || [];
        const tbody = document.getElementById('kelasBody');
        if (kelas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="2" class="text-center py-6 text-slate-400">Tidak ada data</td></tr>';
            return;
        }
        tbody.innerHTML = kelas.map(k => `<tr class="hover:bg-slate-50">
            <td class="px-4 py-3 font-medium">${k.nama_kelas}</td>
            <td class="px-4 py-3 text-right font-mono text-blue-600 font-semibold">Rp ${Number(k.total).toLocaleString('id-ID')}</td>
        </tr>`).join('');
    }).catch(() => {
        document.getElementById('kelasBody').innerHTML = '<tr><td colspan="2" class="text-center py-6 text-red-500">Gagal memuat</td></tr>';
    });
}

document.addEventListener('DOMContentLoaded', () => { loadDashboard(); loadRevenueAnalytics(); });
</script>
@endpush
