@extends('layouts.orangtua.app')

@section('title', 'Perizinan Online')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Perizinan Online</h1>
            <p class="text-slate-500 text-sm mt-1">Ajukan izin untuk {{ $siswa->nama_lengkap }}</p>
        </div>
        <button onclick="showFormIzin()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-4 py-2 text-sm transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Ajukan Izin
        </button>
    </div>

    {{-- Form Pengajuan --}}
    <div id="formIzin" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hidden">
        <h3 class="text-lg font-semibold text-slate-800 mb-4">Form Pengajuan Izin</h3>
        <form onsubmit="submitIzin(event)" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Izin</label>
                    <select id="jenisIzin" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis</option>
                        <option value="Izin">Izin</option>
                        <option value="Sakit">Sakit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal</label>
                    <input type="date" id="tanggalIzin" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" min="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan</label>
                <textarea id="keteranganIzin" rows="3" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Alasan perizinan..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-6 py-2 text-sm transition" id="btnSubmit">
                    Kirim Pengajuan
                </button>
                <button type="button" onclick="hideFormIzin()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg px-6 py-2 text-sm transition">
                    Batal
                </button>
            </div>
        </form>
    </div>

    {{-- Daftar Perizinan --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Riwayat Perizinan</h3>
        </div>
        <div id="listPerizinan">
            <div class="p-8 text-center text-slate-400 animate-pulse">Memuat data...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', loadPerizinan);

function showFormIzin() { document.getElementById('formIzin').classList.remove('hidden'); }
function hideFormIzin() { document.getElementById('formIzin').classList.add('hidden'); }

function loadPerizinan() {
    fetch('/api/orangtua/perizinan', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const items = data.data || data || [];
        const container = document.getElementById('listPerizinan');

        if (!Array.isArray(items) || items.length === 0) {
            container.innerHTML = '<div class="p-8 text-center text-slate-400">Belum ada perizinan</div>';
            return;
        }

        container.innerHTML = '<div class="divide-y divide-slate-100">' + items.map(item => {
            const statusCls = {
                'Disetujui': 'bg-emerald-100 text-emerald-700',
                'Ditolak': 'bg-red-100 text-red-700',
                'Pending': 'bg-amber-100 text-amber-700',
                'Menunggu': 'bg-amber-100 text-amber-700'
            };
            return `<div class="p-4 hover:bg-slate-50">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800">${item.jenis || item.jenis_izin || '-'}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium ${statusCls[item.status] || 'bg-slate-100 text-slate-700'}">${item.status || '-'}</span>
                        </div>
                        <p class="text-sm text-slate-500 mt-1">${item.keterangan || '-'}</p>
                        <p class="text-xs text-slate-400 mt-1">Tanggal: ${item.tanggal || item.tanggal_izin || '-'}</p>
                    </div>
                </div>
            </div>`;
        }).join('') + '</div>';
    })
    .catch(() => {
        document.getElementById('listPerizinan').innerHTML = '<div class="p-8 text-center text-red-500">Gagal memuat data</div>';
    });
}

function submitIzin(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.textContent = 'Mengirim...';

    fetch('/api/orangtua/perizinan', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        credentials: 'same-origin',
        body: JSON.stringify({
            jenis: document.getElementById('jenisIzin').value,
            tanggal: document.getElementById('tanggalIzin').value,
            keterangan: document.getElementById('keteranganIzin').value
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.textContent = 'Kirim Pengajuan';
        if (data.success || data.message) {
            hideFormIzin();
            loadPerizinan();
            alert('Pengajuan izin berhasil dikirim!');
        } else {
            alert(data.message || 'Gagal mengirim pengajuan');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = 'Kirim Pengajuan';
        alert('Gagal mengirim pengajuan');
    });
}
</script>
@endpush
