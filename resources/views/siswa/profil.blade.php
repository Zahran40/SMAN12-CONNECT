@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Profil</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center space-x-6 border border-slate-200">
        <div class="text-center shrink-0">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center p-4 mb-2">
                <div class="rounded-full overflow-hidden w-16 h-16 ring-4 ring-blue-100">
            <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
        </div>
            </div>
            <a href="#" class="text-xs text-blue-600 font-medium hover:underline">Edit Foto</a>
        </div>
        <div>
            <h3 class="text-2xl font-bold text-slate-900">Nama Siswa</h3>
            <p class="text-sm text-slate-500 mb-3">NIS: 1018392392</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">Kelas 12</span>
        </div>
    </div>

    <div>
        <h3 class=" text-blue-400 font-semibold text-lg px-6 py-3 rounded-t-xl inline-block">Data Diri Siswa</h3>
        
        <div class="bg-white rounded-b-xl rounded-r-xl shadow-lg p-6 md:p-8">
            <div class="space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Nama</p>
                    <p class="md:col-span-2 text-slate-800">Nama Siswa</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Tanggal lahir</p>
                    <p class="md:col-span-2 text-slate-800">DD/MM/YEAR</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Tempat lahir</p>
                    <p class="md:col-span-2 text-slate-800">Tempat lahir Siswa</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Alamat</p>
                    <p class="md:col-span-2 text-slate-800">Alamat Siswa</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Jenis Kelamin</p>
                    <p class="md:col-span-2 text-slate-800">Laki-laki/Perempuan</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">NIS</p>
                    <p class="md:col-span-2 text-slate-800">1018392392</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">NISN</p>
                    <p class="md:col-span-2 text-slate-800">6092582022</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">No handphone</p>
                    <p class="md:col-span-2 text-slate-800">+62xxxxxxxxxx</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Email</p>
                    <p class="md:col-span-2 text-slate-800">example@gmail.com</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Agama</p>
                    <p class="md:col-span-2 text-slate-800">Agama Siswa</p>
                </div>

              

            </div>
        </div>
    </div>

@endsection