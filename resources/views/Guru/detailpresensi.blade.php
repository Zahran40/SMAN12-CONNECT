@extends('layouts.guru.app')

@section('title', 'Detail Presensi')

@section('content')

<div x-data="{ 
    showModal: false, 
    currentSiswa: null,
    currentStatus: '',
    currentKeterangan: ''
}">

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('guru.presensi') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold text-blue-500">{{ $pertemuan->jadwal->mataPelajaran->nama_mapel }}</h2>
            <p class="text-sm text-slate-500 mt-1">
                {{ $pertemuan->jadwal->kelas->nama_kelas }} • Pertemuan ke-{{ $pertemuan->nomor_pertemuan }} • 
                {{ \Carbon\Carbon::parse($pertemuan->tanggal_pertemuan)->isoFormat('dddd, D MMMM Y') }}
            </p>
            @if($pertemuan->topik_bahasan)
                <p class="text-sm text-blue-600 mt-1 font-medium">
                     {{ $pertemuan->topik_bahasan }}
                </p>
            @endif
        </div>
    </div>

    {{-- Status Card --}}
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-sm text-slate-500 mb-1">Total Siswa</p>
                <p class="text-2xl font-bold text-blue-400">{{ $siswaList->count() }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-500 mb-1">Hadir</p>
                <p class="text-2xl font-bold text-green-600">{{ $siswaList->where('status_kehadiran', 'Hadir')->count() }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-500 mb-1">Sakit/Izin</p>
                <p class="text-2xl font-bold text-yellow-500">{{ $siswaList->whereIn('status_kehadiran', ['Sakit', 'Izin'])->count() }}</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-slate-500 mb-1">Alfa</p>
                <p class="text-2xl font-bold text-red-600">{{ $siswaList->where('status_kehadiran', 'Alfa')->count() }}</p>
            </div>
        </div>
        
        @if($pertemuan->tanggal_absen_dibuka && $pertemuan->jam_absen_buka && $pertemuan->tanggal_absen_ditutup && $pertemuan->jam_absen_tutup)
            <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    <span class="font-medium">Waktu Absensi:</span>
                    <span class="text-blue-500">
                        {{ \Carbon\Carbon::parse($pertemuan->tanggal_absen_dibuka)->translatedFormat('d M Y') }}, {{ substr($pertemuan->jam_absen_buka, 0, 5) }}
                        - 
                    </span>
                    <span class="text-blue-500">
                        {{ \Carbon\Carbon::parse($pertemuan->tanggal_absen_ditutup)->translatedFormat('d M Y') }}, {{ substr($pertemuan->jam_absen_tutup, 0, 5) }}
                    </span>
                    
                    @if($pertemuan->isAbsensiOpen())
                        <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">● Sedang Berlangsung</span>
                    @else
                        @php
                            $waktuTutup = \Carbon\Carbon::parse($pertemuan->tanggal_absen_ditutup)->setTimeFromTimeString($pertemuan->jam_absen_tutup);
                        @endphp
                        @if(now()->greaterThan($waktuTutup))
                            <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Sudah Ditutup</span>
                        @else
                            <span class="ml-2 text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Belum Dibuka</span>
                        @endif
                    @endif
                </div>
                
                @if($pertemuan->is_submitted)
                    <div class="flex items-center space-x-2">
                        <span class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-full font-medium">
                            ✓ Sudah Di-submit
                        </span>
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('guru.unlock_presensi', $pertemuan->id_pertemuan) }}" method="POST" onsubmit="return confirm('Yakin ingin membuka kembali absensi ini?')">
                                @csrf
                                <button type="submit" class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full hover:bg-yellow-200">
                                     Buka Kembali
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <form action="{{ route('guru.submit_presensi', $pertemuan->id_pertemuan) }}" method="POST" onsubmit="return confirm('Yakin ingin submit? Data akan terkunci.')">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-full hover:bg-blue-600 transition-colors font-medium">
                            Submit & Lock Presensi
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    {{-- Daftar Siswa --}}
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h3 class="text-xl font-bold text-slate-800 mb-6">Daftar Kehadiran Siswa</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-slate-200">
                        <th class="text-left py-4 px-4 font-semibold text-slate-700">No</th>
                        <th class="text-left py-4 px-4 font-semibold text-slate-700">NIS</th>
                        <th class="text-left py-4 px-4 font-semibold text-slate-700">Nama Siswa</th>
                        <th class="text-center py-4 px-4 font-semibold text-slate-700">Status</th>
                        <th class="text-left py-4 px-4 font-semibold text-slate-700">Lokasi</th>
                        <th class="text-left py-4 px-4 font-semibold text-slate-700">Keterangan</th>
                        <th class="text-center py-4 px-4 font-semibold text-slate-700">Waktu</th>
                        <th class="text-center py-4 px-4 font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaList as $index => $siswa)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-4 px-4 text-slate-600">{{ $index + 1 }}</td>
                            <td class="py-4 px-4 text-slate-600">{{ $siswa->nis }}</td>
                            <td class="py-4 px-4">
                                <span class="font-medium text-slate-800">{{ $siswa->nama_lengkap }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($siswa->status_kehadiran)
                                    @php
                                        $badgeColors = [
                                            'Hadir' => 'bg-green-100 text-green-700',
                                            'Sakit' => 'bg-yellow-100 text-yellow-700',
                                            'Izin' => 'bg-blue-100 text-blue-700',
                                            'Alfa' => 'bg-red-100 text-red-700',
                                        ];
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $badgeColors[$siswa->status_kehadiran] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $siswa->status_kehadiran }}
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-500">
                                        Belum Absen
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                @if($siswa->latitude && $siswa->longitude)
                                    <div class="text-xs">
                                        <div class="flex items-start space-x-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                            <div class="flex-1">
                                                @if($siswa->alamat_lengkap)
                                                    <p class="text-slate-700 mb-1 line-clamp-2">{{ $siswa->alamat_lengkap }}</p>
                                                @endif
                                                <p class="text-slate-500 mb-1">{{ $siswa->latitude }}, {{ $siswa->longitude }}</p>
                                                <a href="https://www.google.com/maps?q={{ $siswa->latitude }},{{ $siswa->longitude }}" 
                                                   target="_blank" 
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" />
                                                    </svg>
                                                    Lihat Maps
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-sm text-slate-600">
                                {{ $siswa->keterangan ?? '-' }}
                            </td>
                            <td class="py-4 px-4 text-center text-xs text-slate-500">
                                @if($siswa->dicatat_pada)
                                    {{ \Carbon\Carbon::parse($siswa->dicatat_pada)->isoFormat('HH:mm, DD/MM/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($pertemuan->canEditAbsensi(auth()->user()))
                                    <button 
                                        @click="showModal = true; currentSiswa = {{ $siswa->id_siswa }}; currentStatus = '{{ $siswa->status_kehadiran ?? 'Hadir' }}'; currentKeterangan = '{{ $siswa->keterangan ?? '' }}'"
                                        class="bg-blue-500 text-white px-4 py-1.5 rounded-lg hover:bg-blue-600 transition-colors text-sm font-medium"
                                    >
                                        {{ $siswa->status_kehadiran ? 'Ubah' : 'Set Status' }}
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Update Status --}}
    <div 
        x-show="showModal" 
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.self="showModal = false"
    >
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md" @click.stop>
            <h3 class="text-2xl font-bold text-slate-800 mb-6">Update Status Kehadiran</h3>
            
            <form :action="`{{ route('guru.update_status_presensi', $pertemuan->id_pertemuan) }}`" method="POST">
                @csrf
                <input type="hidden" name="siswa_id" :value="currentSiswa">
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-3">Pilih Status</label>
                    <div class="space-y-2">
                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-green-50 transition-colors" :class="currentStatus === 'Hadir' ? 'border-green-500 bg-green-50' : 'border-slate-200'">
                            <input type="radio" name="status_kehadiran" value="Hadir" x-model="currentStatus" class="w-5 h-5 text-green-600">
                            <span class="ml-3 font-medium text-slate-700">Hadir</span>
                        </label>
                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-yellow-50 transition-colors" :class="currentStatus === 'Sakit' ? 'border-yellow-500 bg-yellow-50' : 'border-slate-200'">
                            <input type="radio" name="status_kehadiran" value="Sakit" x-model="currentStatus" class="w-5 h-5 text-yellow-600">
                            <span class="ml-3 font-medium text-slate-700">Sakit</span>
                        </label>
                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors" :class="currentStatus === 'Izin' ? 'border-blue-500 bg-blue-50' : 'border-slate-200'">
                            <input type="radio" name="status_kehadiran" value="Izin" x-model="currentStatus" class="w-5 h-5 text-blue-600">
                            <span class="ml-3 font-medium text-slate-700">Izin</span>
                        </label>
                        <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-red-50 transition-colors" :class="currentStatus === 'Alfa' ? 'border-red-500 bg-red-50' : 'border-slate-200'">
                            <input type="radio" name="status_kehadiran" value="Alfa" x-model="currentStatus" class="w-5 h-5 text-red-600">
                            <span class="ml-3 font-medium text-slate-700">Alfa (Tanpa Keterangan)</span>
                        </label>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Keterangan (Opsional)</label>
                    <textarea 
                        name="keterangan" 
                        rows="3" 
                        x-model="currentKeterangan"
                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Contoh: Sakit demam, Izin urusan keluarga, dll"
                    ></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button 
                        type="button" 
                        @click="showModal = false"
                        class="flex-1 bg-slate-200 text-slate-700 py-3 rounded-lg hover:bg-slate-300 transition-colors font-medium"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition-colors font-medium"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>

@endsection
