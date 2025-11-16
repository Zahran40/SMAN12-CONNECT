@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
         <a href="{{ route('guru.raport_siswa') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">{{ $jadwal->mataPelajaran->nama_mapel }} - {{ $jadwal->kelas->nama_kelas }}</h2>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg p-8 border-2 border-blue-400">
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-slate-900 mb-1">Daftar Siswa Anda</h3>
            <p class="text-sm text-slate-500">Klik ikon buku untuk mengisi nilai raport siswa</p>
        </div>

        <div>
            <div class="grid grid-cols-12 gap-4 px-6 mb-4 text-sm font-bold text-blue-600 uppercase tracking-wider">
                <div class="col-span-1">No</div>
                <div class="col-span-5">Nama</div>
                <div class="col-span-4">NIS</div>
                <div class="col-span-2 text-right">Detail Raport</div>
            </div>

            @if($siswaList && $siswaList->count() > 0)
                <div class="space-y-3">
                    @foreach($siswaList as $index => $siswa)
                        <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 border-2 border-blue-200 rounded-full py-5 px-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="col-span-1">
                                <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-slate-800 font-bold rounded-lg text-sm">{{ $index + 1 }}</span>
                            </div>
                            <div class="col-span-5 font-semibold text-slate-800">{{ $siswa->nama_lengkap }}</div>
                            <div class="col-span-4 font-semibold text-slate-800">{{ $siswa->nis ?? '-' }}</div>
                            <div class="col-span-2 text-right flex justify-end">
                                <a href="{{ route('guru.detail_raport_siswa', [$jadwal->id_jadwal, $siswa->id_siswa]) }}" class="text-blue-600 hover:text-blue-800">
                                    <img src="{{ asset('images/solar_book-2-bold.png') }}" alt="Detail" class="w-8 h-8">
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 text-slate-500">
                    <p>Tidak ada siswa di kelas ini.</p>
                </div>
            @endif
        </div>
    </div>

@endsection