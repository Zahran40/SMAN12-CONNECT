@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('siswa.tagihan_sudah_dibayar') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">{{ $tagihan->nama_tagihan }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-2 border-green-300 p-6 md:p-8">
        
        <div class="mb-8">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center space-x-3">
                    <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                    <h3 class="text-lg font-bold text-slate-800">Detail Siswa</h3>
                </div>
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Sudah dibayar
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-4 gap-x-6 pl-5">
                <div>
                    <p class="text-xs text-slate-500">Nama</p>
                    <p class="font-bold text-slate-900">{{ $siswa->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">NIS</p>
                    <p class="font-bold text-slate-900">{{ $siswa->nis }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Kelas</p>
                    <p class="font-bold text-slate-900">{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-4">
                <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                <h3 class="text-lg font-bold text-slate-800">Detail Tagihan</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-5 gap-x-6 pl-5">
                <div>
                    <p class="text-xs text-slate-500">Nama Tagihan</p>
                    <p class="font-bold text-slate-900">{{ $tagihan->nama_tagihan }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Periode</p>
                    @php
                        $bulanText = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    <p class="font-bold text-slate-900">{{ $bulanText[$tagihan->bulan] }} {{ $tagihan->tahun }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Tanggal Pembayaran</p>
                    <p class="font-bold text-slate-900">{{ $tagihan->tgl_bayar ? \Carbon\Carbon::parse($tagihan->tgl_bayar)->isoFormat('DD MMMM Y') : '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode Pembayaran</p>
                    <p class="font-bold text-slate-900">{{ $tagihan->metode_pembayaran }}</p>
                </div>
                @if($tagihan->nomor_va)
                <div>
                    <p class="text-xs text-slate-500">Nomor VA</p>
                    <p class="font-bold text-slate-900">{{ $tagihan->nomor_va }}</p>
                </div>
                @endif
                <div>
                    <p class="text-xs text-slate-500">Nominal</p>
                    <p class="font-bold text-slate-900">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
                </div>
                @if($tagihan->midtrans_transaction_id)
                <div class="md:col-span-2">
                    <p class="text-xs text-slate-500">ID Transaksi</p>
                    <p class="font-bold text-slate-900 text-sm">{{ $tagihan->midtrans_transaction_id }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="font-semibold text-green-800">Pembayaran Berhasil</p>
                    <p class="text-sm text-green-700">Terima kasih, pembayaran Anda telah dikonfirmasi</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-12">
            <button onclick="cetakResi()" class="flex items-center space-x-2 bg-blue-400 text-white font-medium px-6 py-2 rounded-lg hover:bg-blue-500 transition-colors">
                <img src="{{ asset('images/download.png') }}" class="w-5 h-5">
                <span>Cetak Resi</span>
            </button>
        </div>

    </div>

    <script>
        function cetakResi() {
            // Implement print functionality
            window.print();
        }
    </script>

@endsection