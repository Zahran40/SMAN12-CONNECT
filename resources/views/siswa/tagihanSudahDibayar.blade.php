@extends('layouts.siswa.app')

@section('content')

 <div class="flex justify-between items-center mb-4 sm:mb-6">
        <h2 class="text-3xl font-bold text-blue-500">Tagihan Uang Sekolah</h2>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex space-x-4 mb-4 sm:mb-6">
        <a href="{{ route('siswa.tagihan') }}" class="bg-white text-slate-600 font-medium px-6 py-2 rounded-lg text-sm border border-slate-300 hover:bg-slate-50">
            Belum Dibayar
        </a>
        <a href="{{ route('siswa.tagihan_sudah_dibayar') }}" class="bg-blue-600 text-white font-medium px-6 py-2 rounded-lg text-sm shadow-md">
            Sudah Dibayar
        </a>
    </div>

    <div class="space-y-6">
        
        @forelse($tagihanLunas as $tagihan)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <div class="p-5 flex justify-between items-start border-b border-slate-200">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $tahunAjaranAktif->tahun_mulai }}/{{ $tahunAjaranAktif->tahun_selesai }}</h3>
                    @php
                        $bulanText = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    <p class="text-sm text-slate-500">{{ $bulanText[$tagihan->bulan] }} {{ $tagihan->tahun }}</p>
                </div>
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Sudah dibayar
                </span>
            </div>

            <div class="p-5 space-y-4 sm:space-y-6">
                
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                        <h4 class="text-sm font-semibold text-slate-600 uppercase">Detail Siswa</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-xs text-slate-500">Nama</p>
                            <p class="font-medium text-slate-800">{{ $siswa->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">NIS</p>
                            <p class="font-medium text-slate-800">{{ $siswa->nis }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Kelas</p>
                            <p class="font-medium text-slate-800">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                        <h4 class="text-sm font-semibold text-slate-600 uppercase">Detail Tagihan</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-xs text-slate-500">Nama Tagihan</p>
                            <p class="font-medium text-slate-800">{{ $tagihan->nama_tagihan }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Waktu Tagihan dibuat</p>
                            <p class="font-medium text-slate-800">{{ $tagihan->created_at ? \Carbon\Carbon::parse($tagihan->created_at)->isoFormat('DD MMMM Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Waktu Tagihan dibayar</p>
                            <p class="font-medium text-slate-800">{{ $tagihan->tgl_bayar ? \Carbon\Carbon::parse($tagihan->tgl_bayar)->isoFormat('DD MMMM Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Nominal</p>
                            <p class="font-medium text-slate-800">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-5 flex justify-end border-t border-slate-200 bg-slate-50/50">
                <a href="{{ route('siswa.detail_tagihan_sudah_dibayar', $tagihan->id_pembayaran) }}" class="bg-blue-600 text-white font-medium px-10 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Detail
                </a>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-xl border-2 border-slate-200 p-4 sm:p-6 md:p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-slate-500 text-lg">Belum ada tagihan yang sudah dibayar</p>
        </div>
        @endforelse

    </div>

@endsection

