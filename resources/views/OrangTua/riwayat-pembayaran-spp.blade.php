@extends('layouts.orangtua.app')

@section('title', 'Riwayat Pembayaran SPP')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Riwayat Pembayaran SPP</h1>
        <p class="text-slate-500 text-sm mt-1">Riwayat pembayaran {{ $siswa->nama_lengkap }}</p>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-emerald-200 p-5 text-center">
            <p class="text-xs font-semibold text-emerald-500 uppercase">Sudah Dibayar</p>
            <p class="text-xl font-bold text-emerald-600 mt-1" id="totalLunas">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-red-200 p-5 text-center">
            <p class="text-xs font-semibold text-red-500 uppercase">Belum Dibayar</p>
            <p class="text-xl font-bold text-red-600 mt-1" id="totalBelumLunas">-</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 text-center">
            <p class="text-xs font-semibold text-slate-400 uppercase">Total Pembayaran</p>
            <p class="text-xl font-bold text-slate-800 mt-1" id="totalPembayaran">-</p>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-800">Daftar Pembayaran</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Bulan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Tahun Ajaran</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                        <th class="text-center px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Tanggal Bayar</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase">Metode</th>
                    </tr>
                </thead>
                <tbody id="tabelPembayaran">
                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400 animate-pulse">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/orangtua/pembayaran', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data || data;
        const items = d.pembayaran || d.items || d || [];
        const summary = d.summary || d.ringkasan || {};

        document.getElementById('totalLunas').textContent = 'Rp ' + (summary.total_lunas || 0).toLocaleString('id-ID');
        document.getElementById('totalBelumLunas').textContent = 'Rp ' + (summary.total_belum_lunas || summary.total_tunggakan || 0).toLocaleString('id-ID');
        document.getElementById('totalPembayaran').textContent = 'Rp ' + (summary.total || (summary.total_lunas || 0) + (summary.total_belum_lunas || 0)).toLocaleString('id-ID');

        const tbody = document.getElementById('tabelPembayaran');
        if (!Array.isArray(items) || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada data pembayaran</td></tr>';
            return;
        }

        tbody.innerHTML = items.map(item => {
            const isLunas = item.status === 'Lunas';
            return `<tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="px-6 py-3 font-medium">${item.bulan || '-'}</td>
                <td class="px-6 py-3">${item.tahun_ajaran || '-'}</td>
                <td class="px-6 py-3 text-right font-semibold">Rp ${(item.jumlah_bayar || item.nominal || 0).toLocaleString('id-ID')}</td>
                <td class="px-6 py-3 text-center">
                    <span class="px-2 py-1 rounded-full text-xs font-medium ${isLunas ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'}">${item.status || '-'}</span>
                </td>
                <td class="px-6 py-3">${item.tgl_bayar || '-'}</td>
                <td class="px-6 py-3 text-slate-500">${item.metode_pembayaran || item.metode || '-'}</td>
            </tr>`;
        }).join('');
    })
    .catch(() => {
        document.getElementById('tabelPembayaran').innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-500">Gagal memuat data</td></tr>';
    });
});
</script>
@endpush
