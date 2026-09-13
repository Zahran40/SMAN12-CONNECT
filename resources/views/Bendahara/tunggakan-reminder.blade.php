@extends('layouts.bendahara.app')

@section('title', 'Tunggakan & Reminder')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tunggakan & Reminder</h1>
            <p class="text-slate-500 text-sm mt-1">Lihat daftar tunggakan siswa dan kirim pengingat pembayaran</p>
        </div>
        <button onclick="sendBulkReminder()" id="btnBulk" class="hidden items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition font-medium text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            Kirim Reminder Terpilih (<span id="selectedCount">0</span>)
        </button>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Siswa Menunggak</p>
            <p class="text-2xl font-bold text-red-600 mt-1" id="totalSiswa">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Nominal Tunggakan</p>
            <p class="text-2xl font-bold text-amber-600 mt-1" id="totalNominal">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase">Rata-rata Bulan Tunggak</p>
            <p class="text-2xl font-bold text-blue-600 mt-1" id="avgBulan">-</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-4 py-3"><input type="checkbox" id="checkAll" onchange="toggleAll()"></th>
                        <th class="text-left px-4 py-3 font-semibold">Siswa</th>
                        <th class="text-left px-4 py-3 font-semibold">NIS</th>
                        <th class="text-left px-4 py-3 font-semibold">Kelas</th>
                        <th class="text-center px-4 py-3 font-semibold">Bulan Tunggak</th>
                        <th class="text-right px-4 py-3 font-semibold">Total Tunggakan</th>
                        <th class="text-center px-4 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataBody" class="divide-y divide-slate-100">
                    <tr><td colspan="7" class="text-center py-10 text-slate-400">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Reminder --}}
<div id="modalReminder" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative">
            <div class="flex items-center justify-between p-5 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800">Kirim Reminder</h3>
                <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 text-xl">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div id="reminderInfo" class="bg-slate-50 rounded-lg p-4 text-sm"></div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Jenis Reminder</label>
                    <select id="jenisReminder" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="Tagihan">Tagihan</option>
                        <option value="Peringatan">Peringatan</option>
                        <option value="Teguran">Teguran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Channel</label>
                    <select id="channelReminder" class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Email">Email</option>
                        <option value="SMS">SMS</option>
                    </select>
                </div>
                <div id="reminderError" class="text-red-600 text-sm hidden"></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-600 hover:bg-slate-50">Batal</button>
                    <button onclick="submitReminder()" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">Kirim Reminder</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
let tunggakanData = [];
let selectedSiswaIds = [];

function loadData() {
    fetch('/api/bendahara/tunggakan', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
        tunggakanData = res.data || [];
        document.getElementById('totalSiswa').textContent = tunggakanData.length;
        const totalNom = tunggakanData.reduce((s, t) => s + (t.total_tunggakan || 0), 0);
        document.getElementById('totalNominal').textContent = 'Rp ' + totalNom.toLocaleString('id-ID');
        const avgBulan = tunggakanData.length > 0 ? (tunggakanData.reduce((s, t) => s + t.jumlah_bulan_tunggak, 0) / tunggakanData.length).toFixed(1) : 0;
        document.getElementById('avgBulan').textContent = avgBulan + ' bulan';

        const tbody = document.getElementById('dataBody');
        if (tunggakanData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-10 text-blue-500 font-medium">Tidak ada tunggakan 🎉</td></tr>';
            return;
        }
        tbody.innerHTML = tunggakanData.map(t => {
            const severity = t.jumlah_bulan_tunggak >= 3 ? 'bg-red-100 text-red-700' : t.jumlah_bulan_tunggak >= 2 ? 'bg-amber-100 text-amber-700' : 'bg-yellow-100 text-yellow-700';
            return `<tr class="hover:bg-slate-50">
                <td class="px-4 py-3 text-center"><input type="checkbox" class="siswa-check" value="${t.siswa_id}" onchange="updateSelected()"></td>
                <td class="px-4 py-3 font-medium">${t.nama_siswa}</td>
                <td class="px-4 py-3">${t.nis || '-'}</td>
                <td class="px-4 py-3">${t.kelas || '-'}</td>
                <td class="px-4 py-3 text-center"><span class="px-2 py-1 rounded-full text-xs font-medium ${severity}">${t.jumlah_bulan_tunggak} bulan</span></td>
                <td class="px-4 py-3 text-right font-mono font-semibold text-red-600">Rp ${Number(t.total_tunggakan).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-center">
                    <button onclick='openReminder([${t.siswa_id}], "${t.nama_siswa}", ${t.total_tunggakan})' class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs hover:bg-red-700">Kirim Reminder</button>
                </td>
            </tr>`;
        }).join('');
    }).catch(() => {
        document.getElementById('dataBody').innerHTML = '<tr><td colspan="7" class="text-center py-10 text-red-500">Gagal memuat data</td></tr>';
    });
}

function toggleAll() {
    const checked = document.getElementById('checkAll').checked;
    document.querySelectorAll('.siswa-check').forEach(c => c.checked = checked);
    updateSelected();
}

function updateSelected() {
    selectedSiswaIds = [...document.querySelectorAll('.siswa-check:checked')].map(c => parseInt(c.value));
    const btn = document.getElementById('btnBulk');
    document.getElementById('selectedCount').textContent = selectedSiswaIds.length;
    btn.classList.toggle('hidden', selectedSiswaIds.length === 0);
    btn.classList.toggle('inline-flex', selectedSiswaIds.length > 0);
}

function sendBulkReminder() {
    if (selectedSiswaIds.length === 0) return;
    openReminder(selectedSiswaIds, `${selectedSiswaIds.length} siswa terpilih`, null);
}

let pendingSiswaIds = [];
function openReminder(ids, label, total) {
    pendingSiswaIds = ids;
    const info = total !== null
        ? `<p><strong>Siswa:</strong> ${label}</p><p><strong>Tunggakan:</strong> Rp ${Number(total).toLocaleString('id-ID')}</p>`
        : `<p><strong>Target:</strong> ${label}</p>`;
    document.getElementById('reminderInfo').innerHTML = info;
    document.getElementById('modalReminder').classList.remove('hidden');
}
function closeModal() { document.getElementById('modalReminder').classList.add('hidden'); document.getElementById('reminderError').classList.add('hidden'); }

function submitReminder() {
    const body = {
        siswa_ids: pendingSiswaIds,
        jenis_reminder: document.getElementById('jenisReminder').value,
        channel: document.getElementById('channelReminder').value
    };
    fetch('/api/bendahara/send-reminder', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: JSON.stringify(body)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            closeModal();
            alert(res.message || 'Reminder berhasil dikirim!');
        } else {
            document.getElementById('reminderError').textContent = res.message || 'Gagal mengirim reminder';
            document.getElementById('reminderError').classList.remove('hidden');
        }
    }).catch(() => {
        document.getElementById('reminderError').textContent = 'Terjadi kesalahan';
        document.getElementById('reminderError').classList.remove('hidden');
    });
}

document.addEventListener('DOMContentLoaded', () => loadData());
</script>
@endpush
