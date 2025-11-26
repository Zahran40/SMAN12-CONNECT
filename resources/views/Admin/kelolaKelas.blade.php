@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-4 sm:space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.tahun-ajaran.index') }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" class="w-8 h-8">
        </a>
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Kelola Kelas</h1>
            <p class="text-slate-500 text-sm">Tahun Ajaran {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }} - Semester {{ $tahunAjaran->semester }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <div>
                <h3 class="text-xl font-bold text-blue-600">Daftar Kelas</h3>
                @if($tahunAjaran->semester === 'Genap')
                    <p class="text-xs text-gray-600 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        Menampilkan kelas dari Semester Ganjil (kelas yang sama digunakan untuk kedua semester)
                    </p>
                @endif
            </div>
            <a href="{{ route('admin.kelas.create', $tahunAjaran->id_tahun_ajaran) }}" class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full font-bold flex items-center space-x-2 shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tambah Kelas</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            @forelse($kelasList as $kelas)
            <div class="border-2 border-blue-200 rounded-2xl p-4 sm:p-6 bg-white hover:shadow-lg transition-shadow">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-16 h-16">
                </div>
                <div class="text-center mb-4">
                    <h4 class="text-blue-600 font-bold text-lg mb-1">{{ $kelas->nama_kelas }}</h4>
                    <p class="text-slate-600 text-sm">Tingkat {{ $kelas->tingkat }}</p>
                    @if($kelas->jurusan)
                        <p class="text-slate-500 text-xs">{{ $kelas->jurusan }}</p>
                    @endif
                    <p class="text-slate-500 text-sm mt-2">
                        <span class="font-semibold">{{ $kelas->siswa->count() }}</span> Siswa
                    </p>
                    <p class="text-slate-500 text-xs mt-1">
                        Wali: {{ $kelas->waliKelas->nama_lengkap ?? '-' }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.kelas.show', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas]) }}" class="flex-1 bg-blue-400 hover:bg-blue-500 text-white py-2 rounded-full font-bold transition-colors text-center text-sm">
                        Detail
                    </a>
                    <form action="{{ route('admin.kelas.destroy', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-full font-bold transition-colors text-sm">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-8 text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <p class="font-semibold text-gray-700 mb-2">Belum Ada Kelas</p>
                <p class="text-sm text-gray-500 mb-4">Klik tombol "Tambah Kelas" di atas atau kembali ke halaman Tahun Ajaran untuk generate kelas otomatis</p>
                <a href="{{ route('admin.tahun-ajaran.index') }}" class="text-blue-500 hover:underline">Kembali ke Tahun Ajaran</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection


