@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col h-full">
    <div class="flex items-center space-x-4 mb-4">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Menambah Mata Pelajaran</h1>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm">
        <h3 class="text-xl font-bold text-blue-600 mb-6">Data Mata Pelajaran</h3>

        <div class="space-y-5 max-w-2xl">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Mata Pelajaran</label>
                <input type="text" placeholder="Nama Mapel" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Mata Pelajaran (Otomatis terbuat)</label>
                <input type="text" placeholder="XXXXXXXX" class="w-48 border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 bg-blue-50/50 focus:outline-none cursor-not-allowed" readonly>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                <div class="relative w-64">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 pr-10 focus:outline-none focus:border-blue-500 bg-white">
                        <option>2024/2025</option>
                        <option>2025/2026</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Semester</label>
                <div class="relative w-64">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 pr-10 focus:outline-none focus:border-blue-500 bg-white">
                        <option>Genap</option>
                        <option>Ganjil</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Guru Pengajar</label>
                <div class="relative w-full max-w-md">
                    <input type="text" placeholder="Nama Guru" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 pr-10 focus:outline-none focus:border-blue-500 placeholder-slate-400">
                    <div class="absolute inset-y-0 right-0 flex items-center px-3 text-blue-500 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Jadwal</label>
                <div class="flex items-center space-x-4">
                    <div>
                        <span class="block text-xs text-blue-400 mb-1 ml-1">Masuk</span>
                        <input type="text" placeholder="00:00" class="w-32 border-2 border-blue-200 rounded-lg px-4 py-2.5 text-center text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-300">
                    </div>
                    <span class="text-blue-400 font-bold text-xl mt-5">-</span>
                    <div>
                        <span class="block text-xs text-blue-400 mb-1 ml-1">Keluar</span>
                        <input type="text" placeholder="00:00" class="w-32 border-2 border-blue-200 rounded-lg px-4 py-2.5 text-center text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-300">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="flex justify-end mt-6 px-4">
        <a href="" class="bg-green-400 hover:bg-green-500 text-white px-8 py-3 rounded-full font-bold flex items-center space-x-2 shadow-lg transition-all hover:shadow-xl">
            <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6  ">
            <span>Simpan</span>
        </a>
    </div>
</div>
@endsection