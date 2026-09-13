@extends('layouts.kepsek.app')

@section('title', 'Export Reports')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Export Reports</h1>
        <p class="text-slate-500 text-sm mt-1">Unduh laporan dalam format PDF atau Excel</p>
    </div>

    {{-- Report Types --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Laporan Akademik --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Laporan Akademik</h3>
            <p class="text-sm text-slate-500 mb-4">Rekap nilai per kelas dan mata pelajaran</p>
            <div class="space-y-3">
                <select id="taAkademik" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Sem {{ $ta->semester }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button onclick="exportReport('akademik', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </button>
                    <button onclick="exportReport('akademik', 'excel')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- Laporan Kehadiran --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Laporan Kehadiran</h3>
            <p class="text-sm text-slate-500 mb-4">Rekap presensi harian siswa per kelas</p>
            <div class="space-y-3">
                <select id="taKehadiran" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Sem {{ $ta->semester }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button onclick="exportReport('kehadiran', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </button>
                    <button onclick="exportReport('kehadiran', 'excel')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- Laporan Keuangan --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Laporan Keuangan</h3>
            <p class="text-sm text-slate-500 mb-4">Rekap pembayaran SPP dan tunggakan</p>
            <div class="space-y-3">
                <select id="taKeuangan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Sem {{ $ta->semester }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button onclick="exportReport('keuangan', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </button>
                    <button onclick="exportReport('keuangan', 'excel')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- Laporan Evaluasi Guru --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Evaluasi Guru</h3>
            <p class="text-sm text-slate-500 mb-4">Laporan kinerja dan evaluasi seluruh guru</p>
            <div class="space-y-3">
                <select id="taGuru" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Sem {{ $ta->semester }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button onclick="exportReport('evaluasi-guru', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </button>
                    <button onclick="exportReport('evaluasi-guru', 'excel')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- Laporan Target Sekolah --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Target Sekolah</h3>
            <p class="text-sm text-slate-500 mb-4">Laporan pencapaian target dan sasaran sekolah</p>
            <div class="space-y-3">
                <select id="taTarget" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
                    @foreach($daftarTahunAjaran as $ta)
                    <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - Sem {{ $ta->semester }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button onclick="exportReport('target', 'pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PDF
                    </button>
                    <button onclick="exportReport('target', 'excel')" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg px-3 py-2 transition flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Excel
                    </button>
                </div>
            </div>
        </div>

        {{-- Laporan Lengkap --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-6 text-white hover:shadow-md transition">
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mb-4">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <h3 class="text-lg font-semibold mb-2">Laporan Lengkap</h3>
            <p class="text-sm text-blue-100 mb-4">Semua laporan dalam satu unduhan</p>
            <button onclick="exportReport('lengkap', 'pdf')" class="w-full bg-white text-blue-600 font-medium rounded-lg px-4 py-2 text-sm hover:bg-blue-50 transition">
                Unduh Laporan Lengkap (PDF)
            </button>
        </div>
    </div>

    {{-- Status Export --}}
    <div id="exportStatus" class="hidden bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3">
            <div class="animate-spin w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full"></div>
            <span class="text-sm text-slate-600" id="exportMessage">Memproses export...</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportReport(type, format) {
    const statusEl = document.getElementById('exportStatus');
    const msgEl = document.getElementById('exportMessage');
    statusEl.classList.remove('hidden');
    msgEl.textContent = `Memproses export ${type} ke ${format.toUpperCase()}...`;

    const taMap = { akademik: 'taAkademik', kehadiran: 'taKehadiran', keuangan: 'taKeuangan', 'evaluasi-guru': 'taGuru', target: 'taTarget' };
    const ta = taMap[type] ? document.getElementById(taMap[type]).value : '';

    fetch(`/api/pimpinan/export-reports?type=${type}&format=${format}&tahun_ajaran_id=${ta}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => {
        if (r.ok && r.headers.get('content-type')?.includes('application')) {
            return r.blob().then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `laporan_${type}_${Date.now()}.${format === 'pdf' ? 'pdf' : 'xlsx'}`;
                a.click();
                window.URL.revokeObjectURL(url);
                msgEl.textContent = 'Export berhasil!';
                setTimeout(() => statusEl.classList.add('hidden'), 3000);
            });
        }
        return r.json().then(data => {
            msgEl.textContent = data.message || 'Export diproses. Silakan cek email Anda.';
            setTimeout(() => statusEl.classList.add('hidden'), 5000);
        });
    })
    .catch(() => {
        msgEl.textContent = 'Gagal memproses export. Coba lagi nanti.';
        setTimeout(() => statusEl.classList.add('hidden'), 5000);
    });
}
</script>
@endpush
