@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Manajemen Akademik</h1>
            <p class="text-slate-500 text-sm">(Manajemen untuk mengatur mata pelajaran dan juga jadwalnya)</p>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-8">
                <h3 class="text-xl font-bold text-blue-600">Daftar Mata Pelajaran</h3>
                <a href="{{ route('admin.akademik.mapel.create')}}" class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full font-bold flex items-center space-x-2 shadow-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Mata Pelajaran</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($mapelList as $mapel)
                <div class="border-2 border-blue-200 rounded-2xl p-6 flex flex-col bg-white">
                    <div class="flex justify-center">
                        <img src="{{ asset('images/Book.png') }}" alt="Icon Mapel" class="w-16 h-16">
                    </div>
                    <div class="text-left mb-4">
                        <h4 class="text-blue-600 font-bold text-lg mb-1">{{ $mapel->nama_mapel }}</h4>
                        <div class="flex items-center text-slate-600 text-sm font-medium">
                             <span>Kode: {{ $mapel->kode_mapel }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Kategori: {{ $mapel->kategori }}</p>
                    </div>
                    <a href="{{ route('admin.akademik.mapel.show', $mapel->id_mapel) }}" class="bg-blue-400 hover:bg-blue-500 text-white py-2.5 rounded-full font-bold transition-colors mt-auto text-center">
                        Detail
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center py-8 text-slate-500">
                    Belum ada mata pelajaran. <a href="{{ route('admin.akademik.mapel.create') }}" class="text-blue-500 hover:underline">Tambah mata pelajaran</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

