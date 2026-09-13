@extends('layouts.kepsek.app')

@section('title', 'Laporan Akademik Global')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Akademik Global</h1>
            <p class="text-slate-500 text-sm mt-1">Rekap nilai per kelas &amp; mata pelajaran</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Tahun Ajaran</label>
                <select id="filterTahunAjaran" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}" {{ $tahunAjaranAktif && $ta->id_tahun_ajaran == $tahunAjaranAktif->id_tahun_ajaran ? 'selected' : '' }}>
                        {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Semester {{ $ta->semester }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kelas</label>
                <select id="filterKelas" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($daftarKelas as $kelas)
                    <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadLaporan()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-4 py-2 text-sm transition">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Tampilkan
                </button>
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4" id="statsSummary">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Rata-rata Nilai</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="rataRata">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Nilai Tertinggi</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1" id="nilaiTertinggi">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Nilai Terendah</p>
            <p class="text-2xl font-bold text-red-600 mt-1" id="nilaiTerendah">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Jumlah Siswa</p>
            <p class="text-2xl font-bold text-slate-800 mt-1" id="jumlahSiswa">-</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Distribusi Nilai per Mata Pelajaran</h3>
        <div class="h-80">
            <canvas id="chartNilaiMapel"></canvas>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Detail Rekap Nilai</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">No</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Kelas</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Rata-rata</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Tertinggi</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Terendah</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jml Siswa</th>
                    </tr>
                </thead>
                <tbody id="tabelNilai">
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400">Klik "Tampilkan" untuk memuat data</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let chartNilai = null;

function loadLaporan() {
    const tahunAjaran = document.getElementById('filterTahunAjaran').value;
    const kelas = document.getElementById('filterKelas').value;

    document.getElementById('tabelNilai').innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-slate-400"><div class="animate-pulse">Memuat data...</div></td></tr>';

    fetch(`/api/pimpinan/laporan-akademik?tahun_ajaran_id=${tahunAjaran}&kelas_id=${kelas}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        if (data.data) renderData(data.data);
        else renderData(data);
    })
    .catch(err => {
        document.getElementById('tabelNilai').innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-red-500">Gagal memuat data</td></tr>';
    });
}

function renderData(data) {
    const items = data.rekap || data || [];
    const summary = data.summary || {};

    document.getElementById('rataRata').textContent = summary.rata_rata ? parseFloat(summary.rata_rata).toFixed(1) : '-';
    document.getElementById('nilaiTertinggi').textContent = summary.tertinggi || '-';
    document.getElementById('nilaiTerendah').textContent = summary.terendah || '-';
    document.getElementById('jumlahSiswa').textContent = summary.jumlah_siswa || '-';

    const tbody = document.getElementById('tabelNilai');
    if (!Array.isArray(items) || items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">Tidak ada data</td></tr>';
        return;
    }

    const labels = [];
    const values = [];

    tbody.innerHTML = items.map((item, i) => {
        labels.push(item.mata_pelajaran || item.nama_mapel || '');
        values.push(parseFloat(item.rata_rata || 0));
        return `<tr class="border-t border-slate-100 hover:bg-slate-50">
            <td class="px-6 py-3">${i + 1}</td>
            <td class="px-6 py-3 font-medium">${item.kelas || item.nama_kelas || '-'}</td>
            <td class="px-6 py-3">${item.mata_pelajaran || item.nama_mapel || '-'}</td>
            <td class="px-6 py-3 text-center font-semibold">${parseFloat(item.rata_rata || 0).toFixed(1)}</td>
            <td class="px-6 py-3 text-center text-emerald-600">${item.tertinggi || '-'}</td>
            <td class="px-6 py-3 text-center text-red-600">${item.terendah || '-'}</td>
            <td class="px-6 py-3 text-center">${item.jumlah_siswa || '-'}</td>
        </tr>`;
    }).join('');

    renderChart(labels, values);
}

function renderChart(labels, values) {
    if (chartNilai) chartNilai.destroy();
    const ctx = document.getElementById('chartNilaiMapel').getContext('2d');
    chartNilai = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: values,
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100 }
            },
            plugins: { legend: { display: false } }
        }
    });
}
</script>
@endpush
