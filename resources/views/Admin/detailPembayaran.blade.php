@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-6">

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Detail Pembayaran SPP</h1>
    </div>

   
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100 relative space-y-6">

        
        <span class="absolute top-6 right-6 bg-red-100 text-red-600 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
            Belum dibayar
        </span>

       
        <div class="flex items-start space-x-4">
            <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
            <div>
                <h4 class="font-bold text-slate-800 mb-2">Detail Siswa</h4>
                <div class="grid grid-cols-3 gap-x-12 gap-y-1 text-sm">
                    <span class="text-slate-500">Nama</span>
                    <span class="text-slate-500">NIS</span>
                    <span class="text-slate-500">Kelas</span>
                    <span class="font-semibold text-slate-700">Nama Siswa</span>
                    <span class="font-semibold text-slate-700">19013810922</span>
                    <span class="font-semibold text-slate-700">Kelas 12-A</span>
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

                    <span class="font-semibold text-slate-700">Uang Sekolah Bulanan</span>
                    <span class="font-semibold text-slate-700">01 Desember 2025</span>
                    <span class="font-semibold text-slate-700">05 Desember 2025</span>

                    <!-- Baris 2 -->
                    <span class="text-slate-500 mt-2">Metode Pembayaran</span>
                    <span class="text-slate-500 mt-2">Nomor VA</span>
                    <span class="text-slate-500 mt-2">Nominal</span>

                    <span class="font-semibold text-slate-700">E-Wallet</span>
                    <span class="font-semibold text-slate-700">8741888123012948931</span>
                    <span class="font-bold text-blue-600">Rp 200.000</span>
                </div>
            </div>
        </div>

        
        <div class="flex items-start space-x-4">
            <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
            <div class="w-full max-w-xs">
                <h4 class="font-bold text-slate-800 mb-2">Status Tagihan</h4>

                <label class="block text-sm font-semibold text-slate-700 mb-2">Ubah Status</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <!-- Saya menggunakan "Lunas" sebagai opsi yang logis, 'Genjit' pada gambar sepertinya typo -->
                        <option>Belum dibayar</option>
                        <option>Lunas</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    </div> 


    <div class="flex justify-end">
        <button class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors flex items-center space-x-2 shadow-sm">
            <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6  ">
            <span>Konfirmasi</span>
        </button>
    </div>

</div>
@endsection