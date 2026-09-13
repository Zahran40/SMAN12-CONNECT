@extends('layouts.orangtua.app')

@section('title', 'Detail Tugas & Materi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Tugas & Materi</h1>
        <p class="text-slate-500 text-sm mt-1">Tugas dan materi pelajaran {{ $siswa->nama_lengkap }}</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 p-5 text-center">
            <p class="text-xs font-semibold text-amber-500 uppercase">Belum Dikerjakan</p>
            <p class="text-2xl font-bold text-amber-600 mt-1" id="tugasPending">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-5 text-center">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Sudah Dikerjakan</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1" id="tugasSelesai">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-blue-200 p-5 text-center">
            <p class="text-xs font-semibold text-blue-500 uppercase">Total Materi</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="totalMateri">-</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="flex border-b border-slate-200">
            <button onclick="switchTab('tugas')" class="px-6 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 tab-btn" data-tab="tugas">Tugas</button>
            <button onclick="switchTab('materi')" class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 tab-btn" data-tab="materi">Materi</button>
        </div>

        {{-- Tugas Tab --}}
        <div id="tabTugas" class="p-6">
            <div class="space-y-4" id="listTugas">
                <div class="text-center text-slate-400 py-8 animate-pulse">Memuat data...</div>
            </div>
        </div>

        {{-- Materi Tab --}}
        <div id="tabMateri" class="p-6 hidden">
            <div class="space-y-4" id="listMateri">
                <div class="text-center text-slate-400 py-8 animate-pulse">Memuat data...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadTugasMateri);

function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.toggle('border-blue-600', btn.dataset.tab === tab);
        btn.classList.toggle('text-blue-600', btn.dataset.tab === tab);
        btn.classList.toggle('border-transparent', btn.dataset.tab !== tab);
        btn.classList.toggle('text-slate-500', btn.dataset.tab !== tab);
    });
    document.getElementById('tabTugas').classList.toggle('hidden', tab !== 'tugas');
    document.getElementById('tabMateri').classList.toggle('hidden', tab !== 'materi');
}

function loadTugasMateri() {
    fetch('/api/orangtua/tugas-materi', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        const tugas = d.tugas || [];
        const materi = d.materi || [];

        const pending = tugas.filter(t => t.status === 'Belum Dikerjakan' || t.status === 'belum').length;
        const selesai = tugas.filter(t => t.status === 'Sudah Dikerjakan' || t.status === 'selesai' || t.status === 'Dinilai').length;
        document.getElementById('tugasPending').textContent = pending;
        document.getElementById('tugasSelesai').textContent = selesai;
        document.getElementById('totalMateri').textContent = materi.length;

        // Render Tugas
        const tugasContainer = document.getElementById('listTugas');
        if (tugas.length === 0) {
            tugasContainer.innerHTML = '<div class="text-center text-slate-400 py-8">Belum ada tugas</div>';
        } else {
            tugasContainer.innerHTML = tugas.map(t => {
                const isLate = t.status !== 'Dinilai' && t.status !== 'selesai' && new Date(t.deadline) < new Date();
                return `<div class="border ${isLate ? 'border-red-200 bg-red-50' : 'border-slate-200'} rounded-lg p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <h4 class="font-semibold text-slate-800">${t.judul || t.judul_tugas || '-'}</h4>
                            <p class="text-sm text-slate-500 mt-1">${t.mata_pelajaran || t.nama_mapel || '-'}</p>
                            <div class="flex items-center gap-4 mt-2 text-xs text-slate-400">
                                <span>Deadline: ${t.deadline || t.tgl_deadline || '-'}</span>
                                ${t.nilai ? `<span class="font-medium text-blue-600">Nilai: ${t.nilai}</span>` : ''}
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium shrink-0 ${
                            t.status === 'Dinilai' || t.status === 'selesai' ? 'bg-emerald-100 text-emerald-700' :
                            isLate ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700'
                        }">${isLate && t.status !== 'Dinilai' ? 'Terlambat' : t.status || '-'}</span>
                    </div>
                    ${t.deskripsi ? `<p class="text-sm text-slate-600 mt-3">${t.deskripsi}</p>` : ''}
                </div>`;
            }).join('');
        }

        // Render Materi
        const materiContainer = document.getElementById('listMateri');
        if (materi.length === 0) {
            materiContainer.innerHTML = '<div class="text-center text-slate-400 py-8">Belum ada materi</div>';
        } else {
            materiContainer.innerHTML = materi.map(m => `<div class="border border-slate-200 rounded-lg p-4 hover:bg-slate-50 transition">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-slate-800 truncate">${m.judul || m.judul_materi || '-'}</h4>
                        <p class="text-sm text-slate-500">${m.mata_pelajaran || m.nama_mapel || '-'}</p>
                        <p class="text-xs text-slate-400 mt-1">${m.tanggal || m.created_at || ''}</p>
                    </div>
                    ${m.file_path ? `<a href="${m.file_path}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium shrink-0">Unduh</a>` : ''}
                </div>
            </div>`).join('');
        }
    })
    .catch(() => {
        document.getElementById('listTugas').innerHTML = '<div class="text-center text-red-500 py-8">Gagal memuat data</div>';
    });
}
</script>
@endpush
