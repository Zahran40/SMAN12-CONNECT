@extends('layouts.bendahara.app')

@section('title', 'Manajemen Tagihan SPP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Tagihan SPP</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola tagihan pembayaran siswa</p>
        </div>
        <button onclick="openModalCreate()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Tagihan Baru
        </button>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Cari Siswa</label>
                <input type="text" id="filterSearch" placeholder="Nama / NIS..." class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                <select id="filterStatus" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadTagihan()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Filter
                </button>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">No</th>
                        <th class="text-left px-4 py-3 font-semibold">Siswa</th>
                        <th class="text-left px-4 py-3 font-semibold">NIS</th>
                        <th class="text-left px-4 py-3 font-semibold">Kelas</th>
                        <th class="text-left px-4 py-3 font-semibold">Bulan</th>
                        <th class="text-right px-4 py-3 font-semibold">Jumlah</th>
                        <th class="text-left px-4 py-3 font-semibold">Jatuh Tempo</th>
                        <th class="text-center px-4 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody id="tagihanBody" class="divide-y divide-slate-100">
                    <tr><td colspan="8" class="text-center py-10 text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
        <div id="pagination" class="px-4 py-3 border-t border-slate-200 flex justify-between items-center text-sm text-slate-500"></div>
    </div>
</div>

{{-- Modal Buat Tagihan --}}
<div id="modalCreate" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModalCreate()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Buat Tagihan Baru</h3>
                <button onclick="closeModalCreate()" class="text-slate-400 hover:text-slate-600">&times;</button>
            </div>
            <form id="formCreate" class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Siswa ID</label>
                    <input type="number" name="siswa_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Tahun Ajaran ID</label>
                    <input type="number" name="tahun_ajaran_id" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Bulan</label>
                        <select name="bulan" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="Januari">Januari</option><option value="Februari">Februari</option><option value="Maret">Maret</option>
                            <option value="April">April</option><option value="Mei">Mei</option><option value="Juni">Juni</option>
                            <option value="Juli">Juli</option><option value="Agustus">Agustus</option><option value="September">September</option>
                            <option value="Oktober">Oktober</option><option value="November">November</option><option value="Desember">Desember</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Jumlah (Rp)</label>
                        <input type="number" name="jumlah_bayar" required min="0" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Tanggal Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div id="formError" class="text-red-600 text-sm hidden"></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModalCreate()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

function loadTagihan(page = 1) {
    const search = document.getElementById('filterSearch').value;
    const status = document.getElementById('filterStatus').value;
    let url = `/api/bendahara/tagihan?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (status) url += `&status=${encodeURIComponent(status)}`;

    document.getElementById('tagihanBody').innerHTML = '<tr><td colspan="8" class="text-center py-10 text-slate-400">Memuat...</td></tr>';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        const items = res.data?.data || [];
        const tbody = document.getElementById('tagihanBody');
        if (items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center py-10 text-slate-400">Tidak ada data tagihan</td></tr>';
            document.getElementById('pagination').innerHTML = '';
            return;
        }
        tbody.innerHTML = items.map((t, i) => {
            const statusClass = t.status === 'Lunas' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700';
            return `<tr class="hover:bg-slate-50">
                <td class="px-4 py-3">${i+1}</td>
                <td class="px-4 py-3 font-medium">${t.siswa?.nama_lengkap || '-'}</td>
                <td class="px-4 py-3">${t.siswa?.nis || '-'}</td>
                <td class="px-4 py-3">${t.siswa?.kelas?.nama_kelas || '-'}</td>
                <td class="px-4 py-3">${t.bulan || '-'}</td>
                <td class="px-4 py-3 text-right font-mono">Rp ${Number(t.jumlah_bayar).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3">${t.tanggal_jatuh_tempo || '-'}</td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium ${statusClass}">${t.status}</span></td>
            </tr>`;
        }).join('');

        // Pagination
        const meta = res.data;
        const pag = document.getElementById('pagination');
        if (meta.last_page > 1) {
            pag.innerHTML = `<span>Hal ${meta.current_page} / ${meta.last_page}</span>
                <div class="flex gap-2">
                    ${meta.current_page > 1 ? `<button onclick="loadTagihan(${meta.current_page-1})" class="px-3 py-1 bg-slate-100 rounded hover:bg-slate-200">&laquo;</button>` : ''}
                    ${meta.current_page < meta.last_page ? `<button onclick="loadTagihan(${meta.current_page+1})" class="px-3 py-1 bg-slate-100 rounded hover:bg-slate-200">&raquo;</button>` : ''}
                </div>`;
        } else { pag.innerHTML = ''; }
    }).catch(() => {
        document.getElementById('tagihanBody').innerHTML = '<tr><td colspan="8" class="text-center py-10 text-red-500">Gagal memuat data</td></tr>';
    });
}

function openModalCreate() { document.getElementById('modalCreate').classList.remove('hidden'); }
function closeModalCreate() { document.getElementById('modalCreate').classList.add('hidden'); document.getElementById('formError').classList.add('hidden'); }

document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v,k) => body[k] = v);

    fetch('/api/bendahara/tagihan', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModalCreate();
            this.reset();
            loadTagihan();
            alert('Tagihan berhasil dibuat!');
        } else {
            document.getElementById('formError').textContent = res.message || 'Gagal membuat tagihan';
            document.getElementById('formError').classList.remove('hidden');
        }
    }).catch(() => {
        document.getElementById('formError').textContent = 'Terjadi kesalahan';
        document.getElementById('formError').classList.remove('hidden');
    });
});

document.addEventListener('DOMContentLoaded', () => loadTagihan());
</script>
@endpush
