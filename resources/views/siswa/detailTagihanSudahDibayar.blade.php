@extends('layouts.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Nama Tagihan</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-2 border-blue-300 p-6 md:p-8">
        
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
                    <p class="font-bold text-slate-900">Nama Siswa</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">NIS</p>
                    <p class="font-bold text-slate-900">19013810922</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Kelas</p>
                    <p class="font-bold text-slate-900">Kelas 12-A</p>
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
                    <p class="font-bold text-slate-900">Uang Sekolah Bulanan</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Waktu Tagihan dibuat</p>
                    <p class="font-bold text-slate-900">01 November 2025</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Waktu Tagihan dibayar</p>
                    <p class="font-bold text-slate-900">05 November 2025</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode Pembayaran</p>
                    <p class="font-bold text-slate-900">E-Wallet</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Nomor VA</p>
                    <p class="font-bold text-slate-900">8741389123012948931</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Nominal</p>
                    <p class="font-bold text-slate-900">Rp 200.000</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-12">
            <button class="flex items-center space-x-2 bg-blue-400 text-white font-medium px-4 py-2 rounded-lg hover:bg-blue-400 transition-colors">
                <img src="{{ asset('images/download.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                  <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.5A1.75 1.75 0 0 1 13.25 8H6.75A1.75 1.75 0 0 1 5 6.25v-3.5ZM11.5 4a.5.5 0 0 0 0-1H8.5a.5.5 0 0 0 0 1h3Z" clip-rule="evenodd" />
                  <path fill-rule="evenodd" d="M1.5 8.75A1.75 1.75 0 0 1 3.25 7h13.5A1.75 1.75 0 0 1 18.5 8.75v5.75c0 .59-.224 1.135-.59 1.562l-2.148 2.148A1.75 1.75 0 0 1 14.22 19H5.78a1.75 1.75 0 0 1-1.543-.89l-2.148-2.148A1.75 1.75 0 0 1 1.5 14.5v-5.75ZM3.25 8.5a.25.25 0 0 0-.25.25v5.75c0 .1.03.192.08.27l2.148 2.148c.078.078.17.13.27.13h8.44a.25.25 0 0 0 .19-.08l2.148-2.148a.25.25 0 0 0 .08-.27v-5.75a.25.25 0 0 0-.25-.25H3.25Z" clip-rule="evenodd" />
                </img>
                <span>Cetak Resi</span>
            </button>
        </div>

    </div>

@endsection