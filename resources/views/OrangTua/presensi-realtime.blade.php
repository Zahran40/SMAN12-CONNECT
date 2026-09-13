@extends('layouts.orangtua.app')

@section('title', 'Presensi Real-time')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Presensi Real-time</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau kehadiran {{ $siswa->nama_lengkap }} di sekolah</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-4 text-center">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Hadir</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1" id="totalHadir">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-blue-200 p-4 text-center">
            <p class="text-xs font-semibold text-blue-500 uppercase">Izin</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="totalIzin">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-4 text-center">
            <p class="text-xs font-semibold text-amber-500 uppercase">Sakit</p>
            <p class="text-2xl font-bold text-amber-600 mt-1" id="totalSakit">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-4 text-center">
            <p class="text-xs font-semibold text-red-500 uppercase">Alpa</p>
            <p class="text-2xl font-bold text-red-600 mt-1" id="totalAlpa">-</p>
        </div>
    </div>

    {{-- Persentase Kehadiran --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-slate-800">Persentase Kehadiran</h3>
            <span class="text-2xl font-bold text-blue-600" id="pctKehadiran">-%</span>
        </div>
        <div class="w-full bg-slate-200 rounded-full h-4">
            <div class="bg-blue-600 h-4 rounded-full transition-all duration-500" id="barKehadiran" style="width: 0%"></div>
        </div>
    </div>

    {{-- Detail Presensi Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-800">Riwayat Presensi</h3>
            <select id="filterBulan" onchange="loadPresensi()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Bulan</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Tanggal</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody id="tabelPresensi">
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400 animate-pulse">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadPresensi);

function loadPresensi() {
    const bulan = document.getElementById('filterBulan').value;
    const url = `/api/orangtua/presensi${bulan ? '?bulan=' + bulan : ''}`;

    fetch(url, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        const summary = d.summary || d.ringkasan || {};
        const items = d.detail || d.items || d.presensi || [];

        document.getElementById('totalHadir').textContent = summary.hadir || 0;
        document.getElementById('totalIzin').textContent = summary.izin || 0;
        document.getElementById('totalSakit').textContent = summary.sakit || 0;
        document.getElementById('totalAlpa').textContent = summary.alpa || 0;

        const pct = summary.persentase_kehadiran || summary.persentase || 0;
        document.getElementById('pctKehadiran').textContent = pct + '%';
        document.getElementById('barKehadiran').style.width = pct + '%';

        const tbody = document.getElementById('tabelPresensi');
        if (!Array.isArray(items) || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data presensi</td></tr>';
            return;
        }

        const statusClass = {
            'Hadir': 'bg-emerald-100 text-emerald-700',
            'Izin': 'bg-blue-100 text-blue-700',
            'Sakit': 'bg-amber-100 text-amber-700',
            'Alpa': 'bg-red-100 text-red-700'
        };

        tbody.innerHTML = items.map(item => `<tr class="border-t border-slate-100 hover:bg-slate-50">
            <td class="px-6 py-3 font-medium">${item.tanggal || item.tanggal_pertemuan || '-'}</td>
            <td class="px-6 py-3">${item.mata_pelajaran || item.nama_mapel || '-'}</td>
            <td class="px-6 py-3 text-center">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass[item.status_kehadiran] || statusClass[item.status] || 'bg-slate-100 text-slate-700'}">${item.status_kehadiran || item.status || '-'}</span>
            </td>
            <td class="px-6 py-3 text-slate-500">${item.keterangan || '-'}</td>
        </tr>`).join('');
    })
    .catch(() => {
        document.getElementById('tabelPresensi').innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-red-500">Gagal memuat data</td></tr>';
    });
}
</script>
@endpush
