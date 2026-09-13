@extends('layouts.bendahara.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Verifikasi Pembayaran</h1>
        <p class="text-slate-500 text-sm mt-1">Verifikasi dan konfirmasi pembayaran siswa secara manual</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" id="summaryCards">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Menunggu Verifikasi</p>
            <p class="text-2xl font-bold text-amber-600 mt-1" id="countPending">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Sudah Diverifikasi</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="countVerified">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Hari Ini</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="countToday">-</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Cari Siswa</label>
                <input type="text" id="filterSearch" placeholder="Nama / NIS..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select id="filterStatus" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="Belum Lunas">Belum Lunas (Pending)</option>
                    <option value="">Semua</option>
                    <option value="Lunas">Lunas</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadData()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">Filter</button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Siswa</th>
                        <th class="text-left px-4 py-3 font-semibold">NIS</th>
                        <th class="text-left px-4 py-3 font-semibold">Bulan</th>
                        <th class="text-right px-4 py-3 font-semibold">Jumlah</th>
                        <th class="text-center px-4 py-3 font-semibold">Status</th>
                        <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataBody" class="divide-y divide-slate-100">
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Verifikasi --}}
<div id="modalVerify" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Verifikasi Pembayaran</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form id="formVerify" class="p-5 space-y-4">
                <input type="hidden" id="verifyId">
                <div id="verifyInfo" class="bg-slate-50 rounded-lg p-4 text-sm space-y-1"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Status Verifikasi</label>
                    <select id="verifyStatus" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="Lunas">Lunas (Terverifikasi)</option>
                        <option value="Belum Lunas">Belum Lunas (Tolak)</option>
                    </select>
                </div>
                <div id="lunasFields">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Tanggal Bayar</label>
                            <input type="date" id="verifyTglBayar" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Metode Pembayaran</label>
                            <select id="verifyMetode" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="Cash">Cash</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                                <option value="E-Wallet">E-Wallet</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-600 mb-1">Bukti Transfer (URL)</label>
                        <input type="text" id="verifyBukti" placeholder="URL bukti transfer opsional" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div id="verifyError" class="text-red-600 text-sm hidden"></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
let allData = [];

document.getElementById('verifyStatus').addEventListener('change', function() {
    document.getElementById('lunasFields').style.display = this.value === 'Lunas' ? 'block' : 'none';
});

function loadData() {
    const search = document.getElementById('filterSearch').value;
    const status = document.getElementById('filterStatus').value;
    let url = '/api/bendahara/tagihan?';
    if (search) url += `search=${encodeURIComponent(search)}&`;
    if (status) url += `status=${encodeURIComponent(status)}&`;

    document.getElementById('dataBody').innerHTML = '<tr><td colspan="6" class="text-center py-10 text-slate-400">Memuat...</td></tr>';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        allData = res.data?.data || [];
        const pending = allData.filter(t => t.status === 'Belum Lunas').length;
        const verified = allData.filter(t => t.status === 'Lunas').length;
        document.getElementById('countPending').textContent = pending;
        document.getElementById('countVerified').textContent = verified;
        document.getElementById('countToday').textContent = allData.length;

        const tbody = document.getElementById('dataBody');
        if (allData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-slate-400">Tidak ada data</td></tr>';
            return;
        }
        tbody.innerHTML = allData.map(t => {
            const sc = t.status === 'Lunas' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700';
            const btn = t.status === 'Belum Lunas'
                ? `<button onclick='openVerify(${JSON.stringify(t).replace(/'/g,"&#39;")})' class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">Verifikasi</button>`
                : `<span class="text-blue-500 text-xs font-medium">✓ Terverifikasi</span>`;
            return `<tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">${t.siswa?.nama_lengkap || '-'}</td>
                <td class="px-4 py-3">${t.siswa?.nis || '-'}</td>
                <td class="px-4 py-3">${t.bulan || '-'}</td>
                <td class="px-4 py-3 text-right font-mono">Rp ${Number(t.jumlah_bayar).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${t.status}</span></td>
                <td class="px-4 py-3 text-center">${btn}</td>
            </tr>`;
        }).join('');
    }).catch(() => {
        document.getElementById('dataBody').innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal memuat data</td></tr>';
    });
}

function openVerify(t) {
    document.getElementById('verifyId').value = t.id_pembayaran;
    document.getElementById('verifyInfo').innerHTML = `
        <p><strong>Siswa:</strong> ${t.siswa?.nama_lengkap || '-'} (${t.siswa?.nis || '-'})</p>
        <p><strong>Bulan:</strong> ${t.bulan}</p>
        <p><strong>Jumlah:</strong> Rp ${Number(t.jumlah_bayar).toLocaleString('id-ID')}</p>
    `;
    document.getElementById('verifyTglBayar').value = new Date().toISOString().split('T')[0];
    document.getElementById('modalVerify').classList.remove('hidden');
}
function closeModal() { document.getElementById('modalVerify').classList.add('hidden'); document.getElementById('verifyError').classList.add('hidden'); }

document.getElementById('formVerify').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('verifyId').value;
    const status = document.getElementById('verifyStatus').value;
    const body = { status };
    if (status === 'Lunas') {
        body.tgl_bayar = document.getElementById('verifyTglBayar').value;
        body.metode_pembayaran = document.getElementById('verifyMetode').value;
        body.bukti_transfer = document.getElementById('verifyBukti').value || null;
    }
    fetch(`/api/bendahara/tagihan/${id}/verify`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            loadData();
            alert('Pembayaran berhasil diverifikasi!');
        } else {
            document.getElementById('verifyError').textContent = res.message || 'Gagal verifikasi';
            document.getElementById('verifyError').classList.remove('hidden');
        }
    }).catch(() => {
        document.getElementById('verifyError').textContent = 'Terjadi kesalahan';
        document.getElementById('verifyError').classList.remove('hidden');
    });
});

document.addEventListener('DOMContentLoaded', () => loadData());
</script>
@endpush
