@extends('layouts.kepsek.app')

@section('title', 'Monitoring Presensi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Monitoring Presensi</h1>
        <p class="text-slate-500 text-sm mt-1">Statistik kehadiran seluruh sekolah</p>
    </div>

    {{-- Kehadiran Hari Ini --}}
    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Absensi</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $kehadiranHariIni->total ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-5 text-center">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Hadir</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $kehadiranHariIni->hadir ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-blue-200 p-5 text-center">
            <p class="text-xs font-semibold text-blue-500 uppercase">Izin</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $kehadiranHariIni->izin ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-5 text-center">
            <p class="text-xs font-semibold text-amber-500 uppercase">Sakit</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $kehadiranHariIni->sakit ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5 text-center">
            <p class="text-xs font-semibold text-red-500 uppercase">Alpa</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $kehadiranHariIni->alpa ?? 0 }}</p>
        </div>
    </div>

    {{-- Donut Chart Hari Ini --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Distribusi Kehadiran Hari Ini</h3>
            <div class="h-64 flex items-center justify-center">
                <canvas id="chartKehadiranHariIni"></canvas>
            </div>
        </div>

        {{-- Filter Per Kelas --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-semibold text-slate-800 mb-4">Filter Per Kelas</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pilih Kelas</label>
                    <select id="filterKelas" onchange="loadPresensiKelas()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftarKelas as $kelas)
                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" id="filterTanggal" value="{{ date('Y-m-d') }}" onchange="loadPresensiKelas()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div id="kelasStats" class="bg-slate-50 rounded-lg p-4 hidden">
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div><p class="text-lg font-bold text-emerald-600" id="kelasHadir">0</p><p class="text-xs text-slate-500">Hadir</p></div>
                        <div><p class="text-lg font-bold text-red-600" id="kelasAbsen">0</p><p class="text-xs text-slate-500">Tidak Hadir</p></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Presensi Per Kelas --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Rekap Kehadiran Per Kelas</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Kelas</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Total Siswa</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-emerald-500 uppercase">Hadir</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-blue-500 uppercase">Izin</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-amber-500 uppercase">Sakit</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-red-500 uppercase">Alpa</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody id="tabelPresensiKelas">
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Donut chart hari ini
    const hadir = {{ $kehadiranHariIni->hadir ?? 0 }};
    const izin = {{ $kehadiranHariIni->izin ?? 0 }};
    const sakit = {{ $kehadiranHariIni->sakit ?? 0 }};
    const alpa = {{ $kehadiranHariIni->alpa ?? 0 }};

    if (hadir + izin + sakit + alpa > 0) {
        new Chart(document.getElementById('chartKehadiranHariIni').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin', 'Sakit', 'Alpa'],
                datasets: [{
                    data: [hadir, izin, sakit, alpa],
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 15 } }
                }
            }
        });
    }

    loadRekapKelas();
});

function loadRekapKelas() {
    fetch('/api/pimpinan/monitoring-presensi?tanggal=' + document.getElementById('filterTanggal').value, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const items = data.data?.per_kelas || data.per_kelas || [];
        const tbody = document.getElementById('tabelPresensiKelas');
        if (items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada data presensi</td></tr>';
            return;
        }
        tbody.innerHTML = items.map(item => {
            const total = (item.hadir || 0) + (item.izin || 0) + (item.sakit || 0) + (item.alpa || 0);
            const pct = total > 0 ? ((item.hadir || 0) / total * 100).toFixed(1) : 0;
            return `<tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="px-6 py-3 font-medium">${item.kelas || item.nama_kelas || '-'}</td>
                <td class="px-6 py-3 text-center">${item.total_siswa || total}</td>
                <td class="px-6 py-3 text-center text-emerald-600 font-semibold">${item.hadir || 0}</td>
                <td class="px-6 py-3 text-center text-blue-600">${item.izin || 0}</td>
                <td class="px-6 py-3 text-center text-amber-600">${item.sakit || 0}</td>
                <td class="px-6 py-3 text-center text-red-600">${item.alpa || 0}</td>
                <td class="px-6 py-3 text-center">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${pct >= 80 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'}">${pct}%</span>
                </td>
            </tr>`;
        }).join('');
    })
    .catch(() => {
        document.getElementById('tabelPresensiKelas').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">Gagal memuat data</td></tr>';
    });
}

function loadPresensiKelas() {
    const kelasId = document.getElementById('filterKelas').value;
    if (!kelasId) { document.getElementById('kelasStats').classList.add('hidden'); return; }

    const tanggal = document.getElementById('filterTanggal').value;
    fetch(`/api/pimpinan/monitoring-presensi?kelas_id=${kelasId}&tanggal=${tanggal}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        document.getElementById('kelasStats').classList.remove('hidden');
        document.getElementById('kelasHadir').textContent = d.hadir || 0;
        document.getElementById('kelasAbsen').textContent = (d.izin || 0) + (d.sakit || 0) + (d.alpa || 0);
    });
}
</script>
@endpush
