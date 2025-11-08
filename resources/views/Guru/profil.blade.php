@extends('layouts.guru.app')

@section('content')

    <h2 class="text-3xl font-bold text-slate-800 mb-6">Profil</h2>

    <!-- Profile Section -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center space-x-4">
        <div class="rounded-full overflow-hidden w-24 h-24 ring-4 ring-blue-100">
            <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Guru" class="w-full h-full object-cover" />
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">Nama Guru</h3>
            <p class="text-sm text-slate-500">NIP: 1773832119</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">Wali Kelas</span>
        </div>
    </div>

    <!-- Data Diri Guru Section -->
    <section class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-slate-800 mb-4">Data Diri Guru</h3>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Nama</span>
                <span class="text-slate-500">Nama Guru</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Tanggal Lahir</span>
                <span class="text-slate-500">DD/MM/YYYY</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Tempat Lahir</span>
                <span class="text-slate-500">Tempat Lahir Guru</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Alamat</span>
                <span class="text-slate-500">Alamat Guru</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Jenis Kelamin</span>
                <span class="text-slate-500">Laki-laki/Perempuan</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">NIS</span>
                <span class="text-slate-500">1773832119</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">No Handphone</span>
                <span class="text-slate-500">+62xxxxxxxxxxx</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Email</span>
                <span class="text-slate-500">example@gmail.com</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Agama</span>
                <span class="text-slate-500">Agama Guru</span>
            </div>
            <div class="flex justify-between">
                <span class="font-medium text-slate-700">Golongan Darah</span>
                <span class="text-slate-500">Golongan darah Guru</span>
            </div>
        </div>
    </section>

@endsection
