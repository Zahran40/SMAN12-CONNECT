@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-blue-700">Pendataan Kelas</h1>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Filter/Info Section --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Semua Data Kelas</h2>
                    <p class="text-slate-500 text-sm">Total {{ $kelasList->count() }} kelas terdaftar</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kelas Grid --}}
    @if($kelasList->count() > 0)
        @php
            $groupedByTahunAjaran = $kelasList->groupBy('tahun_ajaran_id');
        @endphp

        @foreach($groupedByTahunAjaran as $tahunAjaranId => $kelasGroup)
            @php
                $tahunAjaran = $kelasGroup->first()->tahunAjaran;
            @endphp
            
            <div class="bg-white p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg font-bold text-blue-600">Tahun Ajaran {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $tahunAjaran->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $tahunAjaran->status }}
                        </span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-slate-500">{{ $kelasGroup->count() }} kelas</span>
                        <a href="{{ route('admin.kelas.index', $tahunAjaran->id_tahun_ajaran) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Kelola Kelas</span>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($kelasGroup as $kelas)
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-shadow border border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-blue-800">{{ $kelas->nama_kelas }}</h3>
                                <p class="text-sm text-blue-600">Tingkat {{ $kelas->tingkat }} {{ $kelas->jurusan }}</p>
                            </div>
                            <span class="bg-blue-500 text-white px-2 py-1 rounded-lg text-xs font-semibold">
                                {{ $kelas->siswa->count() }} Siswa
                            </span>
                        </div>

                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">Wali Kelas:</span>
                                <span class="ml-1">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum ditentukan' }}</span>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <a href="{{ route('admin.kelas.show', [$kelas->tahun_ajaran_id, $kelas->id_kelas]) }}" 
                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-white p-12 rounded-2xl shadow-sm text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <h3 class="text-xl font-semibold text-slate-600 mb-2">Belum Ada Kelas</h3>
            <p class="text-slate-500">Silakan tambahkan kelas melalui halaman Tahun Ajaran</p>
        </div>
    @endif
</div>
@endsection
