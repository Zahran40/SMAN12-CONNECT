@extends('layouts.bendahara.app')

@section('title', 'Refund Management')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Refund Management</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola pengajuan pengembalian dana pembayaran</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4" id="summaryCards">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Menunggu</p>
            <p class="text-xl font-bold text-amber-600 mt-1" id="countPending">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Diproses</p>
            <p class="text-xl font-bold text-blue-600 mt-1" id="countProses">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Selesai</p>
            <p class="text-xl font-bold text-blue-600 mt-1" id="countSelesai">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Ditolak</p>
            <p class="text-xl font-bold text-red-600 mt-1" id="countDitolak">-</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex flex-wrap gap-2">
            <button onclick="loadData('')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200 transition font-medium filter-btn active-filter" data-status="">Semua</button>
            <button onclick="loadData('Pending')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200 transition font-medium filter-btn" data-status="Pending">Pending</button>
            <button onclick="loadData('Diproses')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200 transition font-medium filter-btn" data-status="Diproses">Diproses</button>
            <button onclick="loadData('Selesai')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200 transition font-medium filter-btn" data-status="Selesai">Selesai</button>
            <button onclick="loadData('Ditolak')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm hover:bg-slate-200 transition font-medium filter-btn" data-status="Ditolak">Ditolak</button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Siswa</th>
                        <th class="text-left px-4 py-3 font-semibold">Tgl Pengajuan</th>
                        <th class="text-right px-4 py-3 font-semibold">Jumlah Refund</th>
                        <th class="text-left px-4 py-3 font-semibold">Alasan</th>
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

{{-- Modal Process --}}
<div id="modalProcess" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Proses Refund</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div id="refundInfo" class="bg-slate-50 rounded-lg p-4 text-sm space-y-1"></div>
                <input type="hidden" id="processId">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                    <select id="processStatus" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="Diproses">Diproses</option>
                        <option value="Selesai">Selesai (Refund Berhasil)</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Catatan</label>
                    <textarea id="processCatatan" rows="3" placeholder="Catatan bendahara (opsional)" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div id="processError" class="text-red-600 text-sm hidden"></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</button>
                    <button onclick="submitProcess()" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Proses</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
let allRefund = [];

const statusColors = {
    'Pending': 'bg-amber-100 text-amber-700',
    'Diproses': 'bg-blue-100 text-blue-700',
    'Selesai': 'bg-blue-100 text-blue-700',
    'Ditolak': 'bg-red-100 text-red-700'
};

function loadData(status = '') {
    // Update active filter
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.toggle('active-filter', b.dataset.status === status);
        b.classList.toggle('bg-blue-600', b.dataset.status === status);
        b.classList.toggle('text-white', b.dataset.status === status);
        b.classList.toggle('bg-slate-100', b.dataset.status !== status);
        b.classList.toggle('text-slate-700', b.dataset.status !== status);
    });

    let url = '/api/bendahara/refund';
    if (status) url += `?status=${encodeURIComponent(status)}`;

    document.getElementById('dataBody').innerHTML = '<tr><td colspan="6" class="text-center py-10 text-slate-400">Memuat...</td></tr>';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        allRefund = res.data || [];

        // Summary counts
        if (!status) {
            document.getElementById('countPending').textContent = allRefund.filter(r => r.status === 'Pending').length;
            document.getElementById('countProses').textContent = allRefund.filter(r => r.status === 'Diproses').length;
            document.getElementById('countSelesai').textContent = allRefund.filter(r => r.status === 'Selesai').length;
            document.getElementById('countDitolak').textContent = allRefund.filter(r => r.status === 'Ditolak').length;
        }

        const tbody = document.getElementById('dataBody');
        if (allRefund.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-10 text-slate-400">Tidak ada data refund</td></tr>';
            return;
        }
        tbody.innerHTML = allRefund.map(r => {
            const sc = statusColors[r.status] || 'bg-slate-100 text-slate-700';
            const canProcess = r.status === 'Pending' || r.status === 'Diproses';
            return `<tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">${r.siswa?.nama_lengkap || '-'}</td>
                <td class="px-4 py-3">${r.tanggal_pengajuan ? new Date(r.tanggal_pengajuan).toLocaleDateString('id-ID') : '-'}</td>
                <td class="px-4 py-3 text-right font-mono font-semibold">Rp ${Number(r.jumlah_refund || 0).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 max-w-xs truncate">${r.alasan || '-'}</td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium ${sc}">${r.status}</span></td>
                <td class="px-4 py-3 text-center">${canProcess
                    ? `<button onclick='openProcess(${JSON.stringify(r).replace(/'/g,"&#39;")})' class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700">Proses</button>`
                    : `<span class="text-slate-400 text-xs">-</span>`
                }</td>
            </tr>`;
        }).join('');
    }).catch(() => {
        document.getElementById('dataBody').innerHTML = '<tr><td colspan="6" class="text-center py-10 text-red-500">Gagal memuat data</td></tr>';
    });
}

function openProcess(r) {
    document.getElementById('processId').value = r.id_refund;
    document.getElementById('refundInfo').innerHTML = `
        <p><strong>Siswa:</strong> ${r.siswa?.nama_lengkap || '-'}</p>
        <p><strong>Jumlah:</strong> Rp ${Number(r.jumlah_refund || 0).toLocaleString('id-ID')}</p>
        <p><strong>Alasan:</strong> ${r.alasan || '-'}</p>
    `;
    document.getElementById('processCatatan').value = '';
    document.getElementById('modalProcess').classList.remove('hidden');
}
function closeModal() { document.getElementById('modalProcess').classList.add('hidden'); document.getElementById('processError').classList.add('hidden'); }

function submitProcess() {
    const id = document.getElementById('processId').value;
    const body = {
        status: document.getElementById('processStatus').value,
        catatan: document.getElementById('processCatatan').value || null
    };
    fetch(`/api/bendahara/refund/${id}/process`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            loadData('');
            alert('Refund berhasil diproses!');
        } else {
            document.getElementById('processError').textContent = res.message || 'Gagal memproses refund';
            document.getElementById('processError').classList.remove('hidden');
        }
    }).catch(() => {
        document.getElementById('processError').textContent = 'Terjadi kesalahan';
        document.getElementById('processError').classList.remove('hidden');
    });
}

document.addEventListener('DOMContentLoaded', () => loadData(''));
</script>
@endpush
