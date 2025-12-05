@extends('layouts.guru.app')

@section('content')

    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 mb-6">Profil Saya</h2>

    <div class="bg-white rounded-xl shadow-md border border-slate-200 p-6 mb-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">

            <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-4 sm:gap-6 w-full sm:w-auto">
                <div class="shrink-0">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden ring-4 ring-blue-100 mx-auto sm:mx-0">
                        @if($guru && $guru->foto_profil)
                            <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto Guru" class="w-full h-full object-cover" />
                        @else
                            <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Foto Guru" class="w-full h-full object-cover" />
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 break-words">{{ $guru->nama_lengkap ?? 'Nama Guru' }}</h3>
                    <p class="text-slate-500 font-medium mb-2">NIP: {{ $guru->nip ?? '-' }}</p>
                    <span class="inline-flex items-center bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 mr-1">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                        </svg>
                        Guru Pengajar
                    </span>
                </div>
            </div>

            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto flex items-center justify-center px-6 py-3 font-semibold rounded-xl bg-red-500 text-white border border-red-200 hover:bg-red-700 hover:text-white transition-all duration-200 group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2 group-hover:text-white transition-colors">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>


    <div class="mb-4">
        <h3 class="text-xl font-bold text-slate-800">Detail Informasi</h3>
        <p class="text-sm text-slate-500">Informasi lengkap data diri anda</p>
    </div>

    <section class="bg-white rounded-xl shadow-md border border-slate-200 overflow-hidden">
        <div class="divide-y divide-slate-100">
            
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">Nama Lengkap</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">{{ $guru->nama_lengkap ?? '-' }}</span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">NIP</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">{{ $guru->nip ?? '-' }}</span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">Tempat, Tanggal Lahir</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">
                    {{ $guru->tempat_lahir ?? '-' }}, {{ $guru->tgl_lahir ? $guru->tgl_lahir->format('d F Y') : '-' }}
                </span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-start hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0 shrink-0">Alamat Domisili</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right text-left leading-relaxed max-w-lg">
                    {{ $guru->alamat ?? '-' }}
                </span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">Jenis Kelamin</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">{{ $guru->jenis_kelamin ?? '-' }}</span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">No Handphone</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">{{ $guru->no_telepon ?? '-' }}</span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">Email</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right break-all">{{ $guru->email ?? '-' }}</span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">Agama</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">{{ $guru->agama ?? '-' }}</span>
            </div>

            <div class="p-4 sm:p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-slate-50 transition-colors">
                <span class="text-sm font-medium text-slate-500 mb-1 sm:mb-0">Golongan Darah</span>
                <span class="text-base font-semibold text-slate-800 sm:text-right">{{ $guru->golongan_darah ?? '-' }}</span>
            </div>

        </div>
    </section>

@endsection