@extends('layouts.orangtua.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Jadwal Pelajaran</h1>
        <p class="text-slate-500 text-sm mt-1">Jadwal kelas {{ $siswa->kelas->nama_kelas ?? '-' }} - {{ $siswa->nama_lengkap }}</p>
    </div>

    {{-- Hari Ini --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-md p-6 text-white">
        <h3 class="font-semibold text-lg mb-1">Jadwal Hari Ini</h3>
        <p class="text-blue-200 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
        <div id="jadwalHariIni" class="mt-4 space-y-2">
            <div class="animate-pulse text-blue-200">Memuat jadwal...</div>
        </div>
    </div>

    {{-- Jadwal Mingguan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Jadwal Mingguan</h3>
        </div>

        {{-- Day tabs --}}
        <div class="flex border-b border-slate-200 overflow-x-auto">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $i => $hari)
            <button onclick="filterHari('{{ $hari }}')" class="px-6 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition day-tab {{ $i === 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}" data-hari="{{ $hari }}">
                {{ $hari }}
            </button>
            @endforeach
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jam</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Mata Pelajaran</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Guru</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Ruangan</th>
                    </tr>
                </thead>
                <tbody id="tabelJadwal">
                    <tr><td colspan="4" class="px-6 py-8 text-center text-slate-400 animate-pulse">Memuat jadwal...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let allJadwal = [];

document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/orangtua/jadwal', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        allJadwal = data.data || data.jadwal || data || [];

        // Jadwal hari ini
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const today = days[new Date().getDay()];
        const todayJadwal = allJadwal.filter(j => j.hari === today);

        const container = document.getElementById('jadwalHariIni');
        if (todayJadwal.length === 0) {
            container.innerHTML = '<p class="text-blue-200">Tidak ada jadwal hari ini</p>';
        } else {
            container.innerHTML = todayJadwal.map(j => `
                <div class="flex items-center gap-3 bg-white/10 rounded-lg p-3">
                    <span class="text-sm font-mono font-bold">${j.jam_mulai || '-'} - ${j.jam_selesai || '-'}</span>
                    <span class="text-sm">${j.nama_mapel || j.mata_pelajaran || '-'}</span>
                    <span class="text-xs text-blue-200 ml-auto">${j.nama_guru || j.guru || ''}</span>
                </div>
            `).join('');
        }

        filterHari('Senin');
    })
    .catch(() => {
        document.getElementById('tabelJadwal').innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-red-500">Gagal memuat jadwal</td></tr>';
    });
});

function filterHari(hari) {
    document.querySelectorAll('.day-tab').forEach(tab => {
        tab.classList.toggle('border-blue-600', tab.dataset.hari === hari);
        tab.classList.toggle('text-blue-600', tab.dataset.hari === hari);
        tab.classList.toggle('border-transparent', tab.dataset.hari !== hari);
        tab.classList.toggle('text-slate-500', tab.dataset.hari !== hari);
    });

    const items = allJadwal.filter(j => j.hari === hari);
    const tbody = document.getElementById('tabelJadwal');

    if (items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-8 text-center text-slate-400">Tidak ada jadwal untuk hari ini</td></tr>';
        return;
    }

    tbody.innerHTML = items.map(j => `<tr class="border-t border-slate-100 hover:bg-slate-50">
        <td class="px-6 py-3 font-mono text-sm font-medium">${j.jam_mulai || '-'} - ${j.jam_selesai || '-'}</td>
        <td class="px-6 py-3 font-medium">${j.nama_mapel || j.mata_pelajaran || '-'}</td>
        <td class="px-6 py-3">${j.nama_guru || j.guru || '-'}</td>
        <td class="px-6 py-3 text-slate-500">${j.ruangan || '-'}</td>
    </tr>`).join('');
}
</script>
@endpush
