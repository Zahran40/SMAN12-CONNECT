@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Manajemen Pembayaran SPP</h1>
            <p class="text-slate-500 text-sm">(Manajemen untuk pembayaran per siswa)</p>
        </div>

        <div class="flex space-x-4">
            <button class="px-8 py-2 bg-blue-400 text-white font-semibold rounded-full shadow-sm hover:bg-blue-500 transition-colors">
                Belum Dibayar
            </button>
            <button class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Sudah Dibayarkan
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option>2024/2025</option>
                        <option>2025/2026</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
             <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Semester</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option>Genap</option>
                        <option>Ganjil</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
             <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option>Kelas 2</option>
                        <option>Kelas 1</option>
                        <option>Kelas 3</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @for ($i = 0; $i < 2; $i++)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-blue-100">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-blue-600">Semester Genap 24/25</h3>
                        <p class="text-sm text-slate-500">Deskripsi Siswa</p>
                    </div>
                    <span class="bg-red-100 text-red-600 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider">
                        Belum dibayar
                    </span>
                </div>

                <div class="flex items-start space-x-4 mb-6">
                    <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2">Detail Siswa</h4>
                        <div class="grid grid-cols-3 gap-x-12 gap-y-1 text-sm">
                            <span class="text-slate-500">Nama</span>
                            <span class="text-slate-500">NIS</span>
                            <span class="text-slate-500">Kelas</span>
                            <span class="font-semibold text-slate-700">Nama Siswa</span>
                            <span class="font-semibold text-slate-700">19053839322</span>
                            <span class="font-semibold text-slate-700">Kelas 2-A</span>
                        </div>
                    </div>
                </div>

                 <div class="flex items-start space-x-4 mb-4">
                    <div class="w-1 h-5 bg-yellow-400 rounded-full mt-1"></div>
                    <div>
                        <h4 class="font-bold text-slate-800 mb-2">Detail Tagihan</h4>
                        <div class="grid grid-cols-3 gap-x-12 gap-y-1 text-sm">
                            <span class="text-slate-500">Nama Tagihan</span>
                            <span class="text-slate-500">Waktu Tagihan dibuat</span>
                            <span class="text-slate-500">Nominal</span>
                            <span class="font-semibold text-slate-700">Uang Sekolah Bulanan</span>
                            <span class="font-semibold text-slate-700">01 Desember 2025</span>
                            <span class="font-bold text-blue-600">Rp 200.000</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('admin.detail_pembayaran')}}" class="bg-blue-400 hover:bg-blue-500 text-white font-semibold py-2 px-8 rounded-full transition-colors">
                        Detail
                    </a>
                </div>
            </div>
            @endfor
        </div>
    </div>
@endsection
