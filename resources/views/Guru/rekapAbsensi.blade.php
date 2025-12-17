@extends('layouts.guru.app')

@section('title', 'Rekap Absensi Kelas')

@section('content')
<div class="container mx-auto px-4 py-6">
    
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-xl p-6 mb-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-3 text-gray-800">Rekap Absensi Kelas</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-500">Mata Pelajaran</p>
                        <p class="font-semibold text-lg text-gray-800">{{ $jadwal->mataPelajaran->nama_mapel }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Kelas</p>
                        <p class="font-semibold text-lg text-gray-800">{{ $jadwal->kelas->nama_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Guru Pengampu</p>
                        <p class="font-semibold text-gray-800">{{ $jadwal->guru->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Periode Rekap</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('guru.rekap_absensi.export', $jadwal->id_jadwal) }}" class="px-5 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    <span>Export Excel</span>
                </a>
                <a href="{{ route('guru.list_pertemuan', $jadwal->id_jadwal) }}" class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-lg transition-all duration-200">
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <p class="text-sm text-gray-500 mb-1">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-800">{{ count($rekapAbsensi) }}</p>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-4">
            <p class="text-sm text-green-600 mb-1">Kehadiran Tinggi</p>
            <p class="text-2xl font-bold text-green-700">{{ collect($rekapAbsensi)->filter(fn($r) => ($r->persen_hadir ?? 0) >= 80)->count() }}</p>
            <p class="text-xs text-green-600">≥ 80%</p>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-4">
            <p class="text-sm text-yellow-600 mb-1">Kehadiran Sedang</p>
            <p class="text-2xl font-bold text-yellow-700">{{ collect($rekapAbsensi)->filter(fn($r) => ($r->persen_hadir ?? 0) >= 60 && ($r->persen_hadir ?? 0) < 80)->count() }}</p>
            <p class="text-xs text-yellow-600">60-79%</p>
        </div>
        <div class="bg-red-50 rounded-lg shadow p-4">
            <p class="text-sm text-red-600 mb-1">Perlu Perhatian</p>
            <p class="text-2xl font-bold text-red-700">{{ collect($rekapAbsensi)->filter(fn($r) => ($r->persen_hadir ?? 0) < 60)->count() }}</p>
            <p class="text-xs text-red-600">< 60%</p>
        </div>
    </div>

    <!-- Tabel Rekap -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider border-b-2 border-gray-300">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider border-b-2 border-gray-300">NIS</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-800 uppercase tracking-wider border-b-2 border-gray-300">Nama Siswa</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider border-b-2 border-gray-300">Total Pertemuan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-green-500 border-b-2 border-green-500">Hadir</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-yellow-500 border-b-2 border-yellow-500">Izin</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-blue-500 border-b-2 border-blue-500">Sakit</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider bg-red-500 border-b-2 border-red-500">Alfa</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-800 uppercase tracking-wider border-b-2 border-gray-300">Persentase Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rekapAbsensi as $index => $rekap)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rekap->nis }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $rekap->nama_lengkap }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">{{ $rekap->total_pertemuan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-green-700 bg-green-50">{{ $rekap->hadir }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-yellow-700 bg-yellow-50">{{ $rekap->izin }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-blue-700 bg-blue-50">{{ $rekap->sakit }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-red-700 bg-red-50">{{ $rekap->alfa }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @php
                                $persen = $rekap->persen_hadir ?? 0;
                                $badgeColor = $persen >= 80 ? 'bg-green-100 text-green-800' : ($persen >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full {{ $badgeColor }}">
                                {{ number_format($persen, 1) }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="font-medium">Belum ada data absensi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Keterangan -->
    <div class="mt-6 bg-white rounded-lg shadow p-5">
        <h3 class="text-sm font-bold text-gray-800 mb-3 border-b pb-2">Keterangan Perhitungan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-600">
            <div>
                <p class="font-semibold text-gray-700 mb-1">Rumus Persentase Kehadiran:</p>
                <p class="bg-gray-50 p-2 rounded font-mono text-xs">(Total Hadir / Total Pertemuan) × 100</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700 mb-2">Kategori Kehadiran:</p>
                <ul class="space-y-1">
                    <li><span class="inline-block w-3 h-3 bg-green-500 rounded-full mr-2"></span><strong>Sangat Baik:</strong> ≥ 80%</li>
                    <li><span class="inline-block w-3 h-3 bg-yellow-500 rounded-full mr-2"></span><strong>Cukup:</strong> 60-79%</li>
                    <li><span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-2"></span><strong>Perlu Perhatian:</strong> < 60%</li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection
