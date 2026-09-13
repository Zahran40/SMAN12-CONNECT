@extends('layouts.bendahara.app')

@section('title', 'Rekonsiliasi Bank')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rekonsiliasi Bank</h1>
            <p class="text-slate-500 text-sm mt-1">Cocokkan saldo bank dengan data sistem</p>
        </div>
        <button onclick="openModalCreate()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Rekonsiliasi Baru
        </button>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select id="filterStatus" onchange="loadData()" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="Match">Match</option>
                    <option value="Selisih">Selisih</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadData()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">Refresh Data</button>
            </div>
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="rekonList">
        <div class="col-span-full text-center py-10 text-slate-400">Memuat data...</div>
    </div>
</div>

{{-- Modal Create --}}
<div id="modalCreate" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Buat Rekonsiliasi Baru</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form id="formCreate" class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Bank</label>
                        <input type="text" name="nama_bank" required placeholder="BCA, BNI, dll" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">No. Rekening</label>
                        <input type="text" name="nomor_rekening" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Saldo Awal (Rp)</label>
                    <input type="number" name="saldo_awal" required min="0" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Total Pemasukan (Rp)</label>
                        <input type="number" name="total_pemasukan" required min="0" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Total Pengeluaran (Rp)</label>
                        <input type="number" name="total_pengeluaran" required min="0" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div id="formError" class="text-red-600 text-sm hidden"></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Proses Rekonsiliasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

function formatRp(v) { return 'Rp ' + Number(v).toLocaleString('id-ID'); }

function loadData() {
    const status = document.getElementById('filterStatus').value;
    let url = '/api/bendahara/rekonsiliasi';
    if (status) url += `?status=${encodeURIComponent(status)}`;

    document.getElementById('rekonList').innerHTML = '<div class="col-span-full text-center py-10 text-slate-400">Memuat...</div>';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        const items = res.data || [];
        const container = document.getElementById('rekonList');
        if (items.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-slate-400">Belum ada data rekonsiliasi</div>';
            return;
        }
        container.innerHTML = items.map(r => {
            const isMatch = r.status === 'Match';
            const statusBg = isMatch ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700';
            const borderColor = isMatch ? 'border-blue-200' : 'border-red-200';
            return `<div class="bg-white rounded-xl shadow-sm border ${borderColor} p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-slate-800">${r.nama_bank}</h4>
                        <p class="text-xs text-slate-400">${r.nomor_rekening}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${statusBg}">${r.status}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div><span class="text-slate-400">Saldo Awal:</span><br><span class="font-mono">${formatRp(r.saldo_awal)}</span></div>
                    <div><span class="text-slate-400">Saldo Akhir:</span><br><span class="font-mono">${formatRp(r.saldo_akhir)}</span></div>
                    <div><span class="text-slate-400">Pemasukan:</span><br><span class="font-mono text-blue-600">${formatRp(r.total_pemasukan)}</span></div>
                    <div><span class="text-slate-400">Pengeluaran:</span><br><span class="font-mono text-red-600">${formatRp(r.total_pengeluaran)}</span></div>
                </div>
                <div class="pt-2 border-t border-slate-100 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Saldo Sistem:</span>
                        <span class="font-mono font-semibold">${formatRp(r.saldo_sistem)}</span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-slate-400">Selisih:</span>
                        <span class="font-mono font-semibold ${r.selisih == 0 ? 'text-blue-600' : 'text-red-600'}">${formatRp(Math.abs(r.selisih))}</span>
                    </div>
                </div>
                <p class="text-xs text-slate-400">${r.tanggal_rekonsiliasi ? new Date(r.tanggal_rekonsiliasi).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'}) : '-'}</p>
            </div>`;
        }).join('');
    }).catch(() => {
        document.getElementById('rekonList').innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Gagal memuat data</div>';
    });
}

function openModalCreate() { document.getElementById('modalCreate').classList.remove('hidden'); }
function closeModal() { document.getElementById('modalCreate').classList.add('hidden'); document.getElementById('formError').classList.add('hidden'); }

document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v,k) => body[k] = v);

    fetch('/api/bendahara/rekonsiliasi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            this.reset();
            loadData();
            const statusMsg = Math.abs(res.data.selisih) < 1 ? 'Status: MATCH ✓' : `Status: SELISIH (Rp ${Number(Math.abs(res.data.selisih)).toLocaleString('id-ID')})`;
            alert(`Rekonsiliasi berhasil dibuat!\n${statusMsg}`);
        } else {
            document.getElementById('formError').textContent = res.message || 'Gagal membuat rekonsiliasi';
            document.getElementById('formError').classList.remove('hidden');
        }
    }).catch(() => {
        document.getElementById('formError').textContent = 'Terjadi kesalahan';
        document.getElementById('formError').classList.remove('hidden');
    });
});

document.addEventListener('DOMContentLoaded', () => loadData());
</script>
@endpush
