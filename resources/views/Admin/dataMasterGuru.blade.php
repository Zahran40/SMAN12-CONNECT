@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col h-full space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.data_master') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Detail Kelas</h1>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm flex justify-between items-center">
        <div class="flex items-center space-x-6">
            <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-16 h-16">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Kelas 2A</h2>
                <div class="flex items-center text-slate-500 text-sm mt-1 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                    </svg>
                    <span>36 Siswa</span>
                </div>
                <span class="border border-yellow-400 text-yellow-600 text-xs font-semibold px-3 py-1 rounded-full">
                    2024/2025
                </span>
            </div>
        </div>
        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>Hapus</span>
        </button>
    </div>

    <div class="flex space-x-4">
        <a href="{{route ('admin.data_master_siswa1')}}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
            Siswa
        </a>
        <a href="{{route ('admin.data_master_guru1')}}" class="px-8 py-2 bg-blue-400 text-white font-semibold rounded-full shadow-sm hover:bg-blue-500 transition-colors">
            Guru
        </a>
        <a href="{{route ('admin.data_master_mapel1')}}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
            Mata Pelajaran
        </a>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm flex-grow">
        <h3 class="text-xl font-bold text-blue-600 mb-6">Daftar Guru Mengajar</h3>

        <div class="grid grid-cols-12 gap-4 text-blue-600 font-semibold mb-4 px-6">
            <div class="col-span-1">No</div>
            <div class="col-span-3">Nama</div>
            <div class="col-span-3">NIP</div>
            <div class="col-span-3">Mata Pelajaran</div>
            <div class="col-span-2 text-right pr-12">Aksi</div>
        </div>

        <div class="max-h-96 overflow-y-auto space-y-4">
            @for ($i = 0; $i < 6; $i++)
                <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-100 rounded-xl px-6 py-4">
                <div class="col-span-1 font-bold text-slate-700 flex items-center justify-center bg-blue-100 w-8 h-8 rounded-lg">1</div>
                <div class="col-span-3 font-semibold text-slate-800">Nama Guru</div>
                <div class="col-span-3 text-slate-600 font-medium text-sm">10210913821891</div>
                <div class="col-span-3 text-slate-600 font-medium text-sm">Nama Mapel</div>
                <div class="col-span-2 text-right">
                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg flex items-center space-x-1 ml-auto transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Hapus</span>
                    </button>
                </div>
        </div>
        @endfor
    </div>
</div>

<div class="flex justify-end">
    <button class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-3 rounded-full font-bold flex items-center space-x-2 shadow-lg transition-all hover:shadow-xl">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        <span>Tambah Guru</span>
    </button>
</div>

</div>
@endsection