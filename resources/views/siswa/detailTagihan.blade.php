@extends('layouts.siswa.app')

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
                <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Belum dibayar
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
                    <p class="font-bold text-slate-900">01 Desember 2025</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Metode Pembayaran</p>
                    <select class="mt-1 block w-full py-1.5 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option>E-Wallet</option>
                        <option>Transfer Bank</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Nominal</p>
                    <p class="font-bold text-slate-900">Rp 200.000</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Nomor VA</p>
                    <p class="font-bold text-slate-900">8741389123012948931</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-12">
            <button class="bg-green-500 text-white font-semibold px-8 py-2.5 rounded-full hover:bg-green-600 transition-colors shadow-lg shadow-green-500/30">
                Cek Status
            </button>
        </div>

    </div>

@endsection