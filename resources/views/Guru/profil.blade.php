@extends('layouts.guru.app')

@section('content')

    <h2 class="text-3xl font-bold text-slate-800 mb-6">Profil</h2>

    <!-- Profile Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center space-x-4">
        <div class="rounded-full overflow-hidden w-24 h-24 ring-4 ring-blue-100">
            @if($guru && $guru->foto_profil)
                <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto Guru" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Foto Guru" class="w-full h-full object-cover" />
            @endif
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">{{ $guru->nama_lengkap ?? 'Nama Guru' }}</h3>
            <p class="text-sm text-slate-500">NIP: {{ $guru->nip ?? '-' }}</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">Guru</span>
        </div>
    </div>

    <!-- Data Diri Guru Section -->
    <h3 class="text-xl font-semibold text-slate-800 mb-4">Data Diri Guru</h3>
    <section class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Nama</span>
                <span class="text-slate-500">{{ $guru->nama_lengkap ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Tanggal Lahir</span>
                <span class="text-slate-500">{{ $guru->tgl_lahir ? $guru->tgl_lahir->format('d/m/Y') : '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Tempat Lahir</span>
                <span class="text-slate-500">{{ $guru->tempat_lahir ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Alamat</span>
                <span class="text-slate-500">{{ $guru->alamat ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Jenis Kelamin</span>
                <span class="text-slate-500">{{ $guru->jenis_kelamin ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">NIP</span>
                <span class="text-slate-500">{{ $guru->nip ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">No Handphone</span>
                <span class="text-slate-500">{{ $guru->no_telepon ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Email</span>
                <span class="text-slate-500">{{ $guru->email ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Agama</span>
                <span class="text-slate-500">{{ $guru->agama ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Golongan Darah</span>
                <span class="text-slate-500">{{ $guru->golongan_darah ?? '-' }}</span>
            </div>
        </div>
    </section>

@endsection
