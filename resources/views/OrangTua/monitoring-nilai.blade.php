@extends('layouts.orangtua.app')

@section('title', 'Monitoring Nilai')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Monitoring Nilai</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau perkembangan nilai {{ $siswa->nama_lengkap }}</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Rata-rata Nilai</p>
            <p class="text-3xl font-bold text-blue-600 mt-1" id="rataRata">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Nilai Tertinggi</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1" id="nilaiTertinggi">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Nilai Terendah</p>
            <p class="text-3xl font-bold text-red-600 mt-1" id="nilaiTerendah">-</p>
        </div>
    </div>

    {{-- Tabel Nilai --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Nilai Per Mata Pelajaran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">No</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Tugas</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">UTS</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">UAS</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Nilai Akhir</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Predikat</th>
                    </tr>
                </thead>
                <tbody id="tabelNilai">
                    <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400 animate-pulse">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Grafik Perkembangan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Grafik Perkembangan Nilai</h3>
        <div class="h-72">
            <canvas id="chartNilai"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/orangtua/nilai', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        const items = d.nilai || d.items || d || [];

        if (d.rata_rata) document.getElementById('rataRata').textContent = parseFloat(d.rata_rata).toFixed(1);
        if (d.tertinggi) document.getElementById('nilaiTertinggi').textContent = d.tertinggi;
        if (d.terendah) document.getElementById('nilaiTerendah').textContent = d.terendah;

        const tbody = document.getElementById('tabelNilai');
        if (!Array.isArray(items) || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Belum ada data nilai</td></tr>';
            return;
        }

        let totalNilai = 0;
        let maxNilai = 0;
        let minNilai = 100;

        const labels = [];
        const values = [];

        tbody.innerHTML = items.map((item, i) => {
            const akhir = parseFloat(item.nilai_akhir || 0);
            totalNilai += akhir;
            if (akhir > maxNilai) maxNilai = akhir;
            if (akhir < minNilai && akhir > 0) minNilai = akhir;

            labels.push(item.nama_mapel || item.mata_pelajaran || '');
            values.push(akhir);

            let predikat = 'D';
            let cls = 'bg-red-100 text-red-700';
            if (akhir >= 90) { predikat = 'A'; cls = 'bg-emerald-100 text-emerald-700'; }
            else if (akhir >= 80) { predikat = 'B'; cls = 'bg-blue-100 text-blue-700'; }
            else if (akhir >= 70) { predikat = 'C'; cls = 'bg-amber-100 text-amber-700'; }

            return `<tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="px-6 py-3">${i + 1}</td>
                <td class="px-6 py-3 font-medium">${item.nama_mapel || item.mata_pelajaran || '-'}</td>
                <td class="px-6 py-3 text-center">${item.nilai_tugas || '-'}</td>
                <td class="px-6 py-3 text-center">${item.nilai_uts || '-'}</td>
                <td class="px-6 py-3 text-center">${item.nilai_uas || '-'}</td>
                <td class="px-6 py-3 text-center font-bold">${akhir.toFixed(1)}</td>
                <td class="px-6 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium ${cls}">${predikat}</span></td>
            </tr>`;
        }).join('');

        if (items.length > 0) {
            document.getElementById('rataRata').textContent = (totalNilai / items.length).toFixed(1);
            document.getElementById('nilaiTertinggi').textContent = maxNilai.toFixed(1);
            document.getElementById('nilaiTerendah').textContent = minNilai.toFixed(1);
        }

        new Chart(document.getElementById('chartNilai').getContext('2d'), {
            type: 'radar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Akhir',
                    data: values,
                    backgroundColor: 'rgba(147, 51, 234, 0.2)',
                    borderColor: 'rgba(147, 51, 234, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(147, 51, 234, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { r: { beginAtZero: true, max: 100 } },
                plugins: { legend: { display: false } }
            }
        });
    })
    .catch(() => {
        document.getElementById('tabelNilai').innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-red-500">Gagal memuat data</td></tr>';
    });
});
</script>
@endpush
