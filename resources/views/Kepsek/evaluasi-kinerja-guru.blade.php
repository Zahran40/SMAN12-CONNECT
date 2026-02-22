@extends('layouts.kepsek.app')

@section('title', 'Evaluasi Kinerja Guru')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Evaluasi Kinerja Guru</h1>
        <p class="text-slate-500 text-sm mt-1">Penilaian &amp; ranking performa guru</p>
    </div>

    {{-- Top 5 Guru --}}
    @if(count($topGuru) > 0)
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Top 5 Guru Berprestasi</h3>
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
            @foreach($topGuru as $index => $tg)
            <div class="bg-gradient-to-br {{ $index === 0 ? 'from-yellow-50 to-amber-100 border-amber-300' : 'from-slate-50 to-slate-100 border-slate-200' }} border rounded-xl p-4 text-center relative">
                @if($index === 0)
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                @endif
                <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg mx-auto mb-2">
                    {{ $index + 1 }}
                </div>
                <p class="font-semibold text-slate-800 text-sm truncate">{{ $tg->guru->nama_lengkap ?? 'N/A' }}</p>
                <p class="text-xs text-slate-500 mt-1">Skor: {{ $tg->skor_total }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filter & List --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <h3 class="text-lg font-semibold text-slate-800">Daftar Evaluasi Guru</h3>
            <div class="flex gap-3">
                <select id="filterTahunAjaran" onchange="loadEvaluasi()" class="border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}" {{ $tahunAjaranAktif && $ta->id_tahun_ajaran == $tahunAjaranAktif->id_tahun_ajaran ? 'selected' : '' }}>
                        {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">No</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Nama Guru</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Kehadiran</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Kualitas Mengajar</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Kedisiplinan</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Skor Total</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Predikat</th>
                    </tr>
                </thead>
                <tbody id="tabelEvaluasi">
                    @foreach($daftarGuru as $index => $guru)
                    <tr class="border-t border-slate-100 hover:bg-slate-50">
                        <td class="px-6 py-3">{{ $index + 1 }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-semibold text-blue-600">{{ strtoupper(substr($guru->nama_lengkap, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800">{{ $guru->nama_lengkap }}</p>
                                    <p class="text-xs text-slate-400">NIP: {{ $guru->nip ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-center" id="kehadiran-{{ $guru->id_guru }}">-</td>
                        <td class="px-6 py-3 text-center" id="kualitas-{{ $guru->id_guru }}">-</td>
                        <td class="px-6 py-3 text-center" id="disiplin-{{ $guru->id_guru }}">-</td>
                        <td class="px-6 py-3 text-center font-bold" id="skor-{{ $guru->id_guru }}">-</td>
                        <td class="px-6 py-3 text-center" id="predikat-{{ $guru->id_guru }}">-</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadEvaluasi);

function loadEvaluasi() {
    const tahunAjaran = document.getElementById('filterTahunAjaran').value;
    fetch(`/api/pimpinan/evaluasi-guru?tahun_ajaran_id=${tahunAjaran}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const items = data.data || data || [];
        if (Array.isArray(items)) {
            items.forEach(item => {
                const id = item.guru_id || item.id_guru;
                const el = (key) => document.getElementById(`${key}-${id}`);
                if (el('kehadiran')) el('kehadiran').textContent = item.skor_kehadiran || '-';
                if (el('kualitas')) el('kualitas').textContent = item.skor_kualitas_mengajar || '-';
                if (el('disiplin')) el('disiplin').textContent = item.skor_kedisiplinan || '-';
                if (el('skor')) el('skor').textContent = item.skor_total || '-';
                if (el('predikat')) {
                    const skor = item.skor_total || 0;
                    let predikat = 'Kurang';
                    let cls = 'bg-red-100 text-red-700';
                    if (skor >= 90) { predikat = 'Sangat Baik'; cls = 'bg-emerald-100 text-emerald-700'; }
                    else if (skor >= 80) { predikat = 'Baik'; cls = 'bg-blue-100 text-blue-700'; }
                    else if (skor >= 70) { predikat = 'Cukup'; cls = 'bg-amber-100 text-amber-700'; }
                    el('predikat').innerHTML = `<span class="px-2 py-1 rounded-full text-xs font-medium ${cls}">${predikat}</span>`;
                }
            });
        }
    });
}
</script>
@endpush
