@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-6">

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.pembayaran.index') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Detail Pembayaran SPP</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

   
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 relative space-y-6">

        
        @if($pembayaran->status === 'Lunas')
            <span class="absolute top-6 right-6 bg-green-100 text-green-600 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Lunas
            </span>
        @else
            <span class="absolute top-6 right-6 bg-red-100 text-red-600 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                Belum dibayar
            </span>
        @endif

       
        <div class="flex items-start space-x-4">
            <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
            <div>
                <h4 class="font-bold text-slate-800 mb-2">Detail Siswa</h4>
                <div class="grid grid-cols-3 gap-x-12 gap-y-1 text-sm">
                    <span class="text-slate-500">Nama</span>
                    <span class="text-slate-500">NIS</span>
                    <span class="text-slate-500">Kelas</span>
                    <span class="font-semibold text-slate-700">{{ $pembayaran->siswa->nama_lengkap }}</span>
                    <span class="font-semibold text-slate-700">{{ $pembayaran->siswa->nis }}</span>
                    <span class="font-semibold text-slate-700">{{ $pembayaran->siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>
            </div>
        </div>

       
        <div class="flex items-start space-x-4">
            <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
            <div>
                <h4 class="font-bold text-slate-800 mb-2">Detail Tagihan</h4>
                <div class="grid grid-cols-3 gap-x-12 gap-y-2 text-sm">
                    <!-- Baris 1 -->
                    <span class="text-slate-500">Nama Tagihan</span>
                    <span class="text-slate-500">Waktu Tagihan dibuat</span>
                    <span class="text-slate-500">Waktu Tagihan dibayar</span>

                    <span class="font-semibold text-slate-700">{{ $pembayaran->nama_tagihan }}</span>
                    <span class="font-semibold text-slate-700">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
                    <span class="font-semibold text-slate-700">{{ $pembayaran->tgl_bayar ? $pembayaran->tgl_bayar->format('d M Y') : '-' }}</span>

                    <!-- Baris 2 -->
                    <span class="text-slate-500 mt-2">Metode Pembayaran</span>
                    <span class="text-slate-500 mt-2">Nomor VA / Order ID</span>
                    <span class="text-slate-500 mt-2">Nominal</span>

                    @php
                        $metodeDisplay = $pembayaran->metode_pembayaran;
                        if ($pembayaran->midtrans_payment_type) {
                            $metodeMap = [
                                'bank_transfer' => 'Transfer Bank (' . strtoupper($pembayaran->nomor_va ? 'BCA' : '-') . ')',
                                'gopay' => 'GoPay',
                                'shopeepay' => 'ShopeePay',
                            ];
                            $metodeDisplay = $metodeMap[$pembayaran->midtrans_payment_type] ?? $pembayaran->midtrans_payment_type;
                        }
                    @endphp

                    <span class="font-semibold text-slate-700">{{ $metodeDisplay }}</span>
                    <span class="font-semibold text-slate-700">{{ $pembayaran->nomor_va ?? $pembayaran->midtrans_order_id ?? '-' }}</span>
                    <span class="font-bold text-blue-600">Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        
        <form id="statusForm" method="POST" action="{{ route('admin.pembayaran.update_status', $pembayaran->id_pembayaran) }}" class="flex items-start space-x-4">
            @csrf
            @method('PUT')
            <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
            <div class="w-full max-w-xs">
                <h4 class="font-bold text-slate-800 mb-2">Status Tagihan</h4>

                <label class="block text-sm font-semibold text-slate-700 mb-2">Ubah Status</label>
                <div class="relative">
                    <select name="status" id="statusSelect" class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="Belum Lunas" {{ $pembayaran->status === 'Belum Lunas' ? 'selected' : '' }}>Belum Dibayar</option>
                        <option value="Lunas" {{ $pembayaran->status === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </form>

    </div> 


    <div class="flex justify-between">
        @if($pembayaran->status === 'Belum Lunas')
        <form method="POST" action="{{ route('admin.pembayaran.destroy', $pembayaran->id_pembayaran) }}" onsubmit="return confirm('Yakin ingin menghapus tagihan ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors flex items-center space-x-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span>Hapus Tagihan</span>
            </button>
        </form>
        @else
        <div></div>
        @endif

        <button type="submit" form="statusForm" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors flex items-center space-x-2 shadow-sm">
            <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6  ">
            <span>Konfirmasi</span>
        </button>
    </div>

</div>
@endsection
