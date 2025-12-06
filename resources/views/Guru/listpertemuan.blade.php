@extends('layouts.guru.app')

@section('title', 'Daftar Pertemuan')

@section('content')

    <div class="flex items-center justify-between mb-4 sm:mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('guru.presensi') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h2 class="text-3xl font-bold text-blue-500">Daftar Semua Pertemuan (1-16)</h2>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $jadwal->mataPelajaran->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}
                </p>
            </div>
        </div>
        
        <!-- Tombol Rekap Absensi -->
        <a href="{{ route('guru.rekap_absensi', $jadwal->id_jadwal) }}" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            <span>Rekap Absensi</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider w-24">
                        #
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                        Pertemuan
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                        Waktu Absensi
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">
                        Materi
                    </th>
                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-slate-100">
                @foreach($slotPertemuan as $nomor => $pertemuan)
                    <tr class="hover:bg-blue-50 transition-colors {{ $pertemuan ? '' : 'bg-slate-50' }}">
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $pertemuan ? 'bg-blue-100 text-blue-700' : 'bg-slate-200 text-slate-500' }} font-bold">
                                {{ $nomor }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-slate-800">Pertemuan {{ $nomor }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($pertemuan && $pertemuan->tanggal_pertemuan)
                                <div class="text-sm font-medium text-slate-700">
                                    {{ \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('DD MMM Y') }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('dddd') }}
                                </div>
                            @else
                                <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($pertemuan && $pertemuan->jam_absen_buka && $pertemuan->jam_absen_tutup)
                                <div class="text-xs">
                                    <div class="text-blue-400 font-medium">
                                         {{ substr($pertemuan->jam_absen_buka, 0, 5) }} -
                                         {{ substr($pertemuan->jam_absen_tutup, 0, 5) }}
                                    </div>
                                </div>
                            @else
                                <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($pertemuan && $pertemuan->topik_bahasan)
                                <div class="text-sm text-blue-500"> {{ $pertemuan->topik_bahasan }}</div>
                            @else
                                <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($pertemuan && $pertemuan->tanggal_pertemuan)
                                @if($pertemuan->is_submitted)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                           Telah Submit
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                         Belum Submit
                                    </span>
                                @endif
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-slate-200 text-slate-500">
                                     Kosong
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($pertemuan && $pertemuan->tanggal_pertemuan)
                                <a href="{{ route('guru.detail_presensi', $pertemuan->id_pertemuan) }}" 
                                   class="inline-block px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg hover:bg-blue-600 transition-colors">
                                    Kelola Presensi
                                </a>
                            @else
                                <button disabled class="inline-block px-4 py-2 bg-slate-300 text-slate-500 text-sm font-semibold rounded-lg cursor-not-allowed">
                                    Belum Terisi
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 sm:p-6">
        <h3 class="text-lg font-bold text-blue-900 mb-3"> Informasi</h3>
        <ul class="space-y-2 text-sm text-blue-800">
            <li>• <strong>16 Slot Pertemuan</strong>: Setiap mata pelajaran memiliki 16 slot pertemuan yang bisa Anda isi sesuai kebutuhan</li>
            <li>• <strong>Pilih Nomor</strong>: Saat membuat pertemuan baru, pilih nomor slot mana yang ingin diisi (1-16)</li>
            <li>• <strong>Tanggal Fleksibel</strong>: Anda menentukan tanggal pertemuan </li>
            <li>• <strong>Status "-" </strong>: Pertemuan yang belum terisi akan ditandai dengan "-" sampai Anda membuatnya</li>
        </ul>
    </div>

@endsection


