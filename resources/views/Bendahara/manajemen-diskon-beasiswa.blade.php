@extends('layouts.bendahara.app')

@section('title', 'Manajemen Diskon & Beasiswa')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Diskon & Beasiswa</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola program diskon dan beasiswa untuk siswa</p>
        </div>
        <button onclick="openModalCreate()" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Program
        </button>
    </div>

    {{-- Filter Tabs --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <div class="flex flex-wrap gap-2">
            <button onclick="loadData('', '')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium filter-btn" data-filter="all">Semua</button>
            <button onclick="loadData('Diskon', '')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 filter-btn" data-filter="Diskon">Diskon</button>
            <button onclick="loadData('Beasiswa', '')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 filter-btn" data-filter="Beasiswa">Beasiswa</button>
            <span class="border-l border-slate-300 mx-2"></span>
            <button onclick="loadData('', 'Aktif')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 filter-btn" data-filter="Aktif">Aktif</button>
            <button onclick="loadData('', 'Nonaktif')" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-200 filter-btn" data-filter="Nonaktif">Nonaktif</button>
        </div>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="diskonList">
        <div class="col-span-full text-center py-10 text-slate-400">Memuat data...</div>
    </div>
</div>

{{-- Modal Create --}}
<div id="modalCreate" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-5 border-b border-slate-200 sticky top-0 bg-white">
                <h3 class="text-lg font-semibold text-slate-800">Tambah Program Diskon/Beasiswa</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <form id="formCreate" class="p-5 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Jenis</label>
                        <select name="jenis" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="Diskon">Diskon</option>
                            <option value="Beasiswa">Beasiswa</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Tipe Potongan</label>
                        <select name="tipe_potongan" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="Persentase">Persentase (%)</option>
                            <option value="Nominal">Nominal (Rp)</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Nama Program</label>
                    <input type="text" name="nama_program" required placeholder="mis. Beasiswa Prestasi Semester 1" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Nilai Potongan</label>
                    <input type="number" name="nilai_potongan" required min="0" step="0.01" placeholder="mis. 50 (%) atau 100000 (Rp)" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Deskripsi atau catatan tambahan" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div id="formError" class="text-red-600 text-sm hidden"></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</button>
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

function loadData(jenis = '', status = '') {
    // Update filter UI
    document.querySelectorAll('.filter-btn').forEach(b => {
        const f = b.dataset.filter;
        const active = (f === 'all' && !jenis && !status) || f === jenis || f === status;
        b.classList.toggle('bg-blue-600', active);
        b.classList.toggle('text-white', active);
        b.classList.toggle('bg-slate-100', !active);
        b.classList.toggle('text-slate-700', !active);
    });

    let url = '/api/bendahara/diskon-beasiswa?';
    if (jenis) url += `jenis=${encodeURIComponent(jenis)}&`;
    if (status) url += `status=${encodeURIComponent(status)}&`;

    document.getElementById('diskonList').innerHTML = '<div class="col-span-full text-center py-10 text-slate-400">Memuat...</div>';

    fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        const items = res.data || [];
        const container = document.getElementById('diskonList');
        if (items.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-slate-400">Belum ada program diskon/beasiswa</div>';
            return;
        }
        container.innerHTML = items.map(d => {
            const isAktif = d.status === 'Aktif';
            const jenisColor = d.jenis === 'Beasiswa' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700';
            const statusColor = isAktif ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500';
            const borderColor = isAktif ? 'border-blue-200' : 'border-slate-200';
            const potonganText = d.tipe_potongan === 'Persentase'
                ? `${d.nilai_potongan}%`
                : `Rp ${Number(d.nilai_potongan).toLocaleString('id-ID')}`;

            return `<div class="bg-white rounded-xl shadow-sm border ${borderColor} p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${jenisColor}">${d.jenis}</span>
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${statusColor}">${d.status}</span>
                </div>
                <h4 class="font-semibold text-slate-800">${d.nama_program}</h4>
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-blue-600">${potonganText}</span>
                    <span class="text-xs text-slate-400">${d.tipe_potongan}</span>
                </div>
                <div class="text-sm text-slate-500 space-y-1">
                    <p>📅 ${d.tanggal_mulai ? new Date(d.tanggal_mulai).toLocaleDateString('id-ID') : '-'} s/d ${d.tanggal_selesai ? new Date(d.tanggal_selesai).toLocaleDateString('id-ID') : '-'}</p>
                    ${d.deskripsi ? `<p class="text-xs text-slate-400">${d.deskripsi}</p>` : ''}
                </div>
                ${d.siswa && d.siswa.length > 0 ? `<div class="pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-400 font-medium mb-1">Penerima (${d.siswa.length} siswa)</p>
                    <div class="flex flex-wrap gap-1">${d.siswa.slice(0,3).map(s => `<span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs">${s.nama_lengkap}</span>`).join('')}${d.siswa.length > 3 ? `<span class="text-xs text-slate-400">+${d.siswa.length-3} lainnya</span>` : ''}</div>
                </div>` : ''}
            </div>`;
        }).join('');
    }).catch(() => {
        document.getElementById('diskonList').innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Gagal memuat data</div>';
    });
}

function openModalCreate() { document.getElementById('modalCreate').classList.remove('hidden'); }
function closeModal() { document.getElementById('modalCreate').classList.add('hidden'); document.getElementById('formError').classList.add('hidden'); }

document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd = new FormData(this);
    const body = {};
    fd.forEach((v,k) => body[k] = v);

    fetch('/api/bendahara/diskon-beasiswa', {
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
            loadData('', '');
            alert('Program diskon/beasiswa berhasil dibuat!');
        } else {
            const errors = res.errors ? Object.values(res.errors).flat().join(', ') : res.message;
            document.getElementById('formError').textContent = errors || 'Gagal menyimpan';
            document.getElementById('formError').classList.remove('hidden');
        }
    }).catch(() => {
        document.getElementById('formError').textContent = 'Terjadi kesalahan';
        document.getElementById('formError').classList.remove('hidden');
    });
});

document.addEventListener('DOMContentLoaded', () => loadData('', ''));
</script>
@endpush
