@extends('layouts.admin.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="content-wrapper p-4 sm:p-6">

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4 mb-4 sm:mb-6">
        <img src="{{ asset('images/clarity_administrator-solid.png') }}" alt="Operator Icon" class="w-16 h-16">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Operator</h2>
            
        </div>
    </div>


    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-5">
        <h1 class="text-xl font-bold text-blue-700">Tahun Ajaran</h1>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
            {{-- Link ke Arsip --}}
            <a href="{{ route('admin.tahun-ajaran.archived') }}" class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center space-x-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                    <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                </svg>
                <span>Arsip</span>
            </a>
            
            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.tahun-ajaran.create') }}" class="w-full sm:w-auto bg-blue-400 hover:bg-blue-500 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-full font-bold flex items-center justify-center space-x-2 shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tahun ajaran</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            {{ session('error') }}
        </div>
    @endif


    @forelse($tahunAjaran as $index => $ta)
    <div class="bg-white rounded-2xl shadow p-5 {{ $index < count($tahunAjaran) - 1 ? 'mb-6' : '' }}">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/school.png') }}" alt="Icon Tahun Ajaran" class="w-7 h-7">
                <h2 class="font-semibold text-gray-700 text-lg">Tahun Ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}</h2>
            </div>

            <div class="flex items-center gap-2">
                @if(isset($ta->can_delete) && $ta->can_delete)
                    <form action="{{ route('admin.tahun-ajaran.destroy-year', [$ta->tahun_mulai, $ta->tahun_selesai]) }}" method="POST" onsubmit="return confirm('Yakin ingin mengarsipkan tahun ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}? Tahun ajaran akan disembunyikan dari daftar, tapi data kelas, siswa, dan raport tetap tersimpan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                            </svg>
                            Arsipkan
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-300 text-gray-500 px-3 py-2 rounded-lg text-sm font-medium cursor-not-allowed" title="Nonaktifkan semua semester terlebih dahulu untuk mengarsipkan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Arsipkan
                    </button>
                @endif
            </div>
        </div>

        {{-- Semester Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 sm:mb-6">
            {{-- Semester Ganjil --}}
            @if($ta->ganjil)
            <div class="border-2 {{ $ta->ganjil->status == 'Aktif' ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-gray-50' }} rounded-xl p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700"> Semester Ganjil</h3>
                    @if($ta->ganjil->status == 'Aktif')
                        <span class="text-xs bg-green-500 text-white font-semibold px-3 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs bg-gray-400 text-white font-semibold px-3 py-1 rounded-full">Tidak Aktif</span>
                    @endif
                </div>
                <p class="text-xs text-gray-600 mb-3">Juli - Desember</p>
                <form action="{{ route('admin.tahun-ajaran.update-status', $ta->ganjil->id_tahun_ajaran) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $ta->ganjil->status == 'Aktif' ? 'Tidak Aktif' : 'Aktif' }}">
                    <button type="submit" class="{{ $ta->ganjil->status == 'Aktif' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full">
                        {{ $ta->ganjil->status == 'Aktif' ? ' Nonaktifkan' : ' Aktifkan' }}
                    </button>
                </form>
            </div>
            @endif

            {{-- Semester Genap --}}
            @if($ta->genap)
            <div class="border-2 {{ $ta->genap->status == 'Aktif' ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-gray-50' }} rounded-xl p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700"> Semester Genap</h3>
                    @if($ta->genap->status == 'Aktif')
                        <span class="text-xs bg-green-500 text-white font-semibold px-3 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="text-xs bg-gray-400 text-white font-semibold px-3 py-1 rounded-full">Tidak Aktif</span>
                    @endif
                </div>
                <p class="text-xs text-gray-600 mb-3">Januari - Juni</p>
                <form action="{{ route('admin.tahun-ajaran.update-status', $ta->genap->id_tahun_ajaran) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $ta->genap->status == 'Aktif' ? 'Tidak Aktif' : 'Aktif' }}">
                    <button type="submit" class="{{ $ta->genap->status == 'Aktif' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full">
                        {{ $ta->genap->status == 'Aktif' ? ' Nonaktifkan' : ' Aktifkan' }}
                    </button>
                </form>
            </div>
            @endif
        </div>

        <h3 class="font-semibold text-blue-700 mb-4">Data Tahun Ajaran (Kedua Semester)</h3>
        <p class="text-xs text-gray-600 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            Kelas dibuat untuk Semester Ganjil dan digunakan bersama untuk Semester Genap dalam tahun ajaran yang sama
        </p>

        @php
            // Cek apakah ada semester aktif
            $hasActiveSemester = ($ta->ganjil && $ta->ganjil->status === 'Aktif') || ($ta->genap && $ta->genap->status === 'Aktif');
            
            // Tentukan ID tahun ajaran mana yang digunakan untuk generate/kelola kelas
            // Prioritas: Ganjil (karena kelas dibuat di Ganjil), baru Genap
            $semesterForAction = $ta->ganjil ?? $ta->genap;
        @endphp

        @if(!$hasActiveSemester)
            {{-- Pesan jika tidak ada semester yang aktif --}}
            <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-6 mb-4">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-yellow-800 mb-1">Semester Belum Diaktifkan</h4>
                        <p class="text-sm text-yellow-700">Aktifkan salah satu semester (Ganjil atau Genap) untuk mengelola kelas, siswa, dan jadwal pelajaran.</p>
                    </div>
                </div>
            </div>
        @elseif($ta->jumlah_kelas == 0 && $semesterForAction)
            {{-- Pesan jika belum ada kelas --}}
            <div class="bg-blue-50 border-2 border-blue-300 rounded-xl p-6 mb-4">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h4 class="font-semibold text-blue-800 mb-2">Belum Ada Kelas</h4>
                    <p class="text-sm text-blue-700 mb-4">
                        @if($ta->ganjil)
                            Generate 30 kelas standar secara otomatis atau buat kelas secara manual
                        @else
                            Semester Ganjil belum dibuat. Buat semester Ganjil terlebih dahulu untuk membuat kelas.
                        @endif
                    </p>
                    <div class="flex gap-3 justify-center flex-wrap">
                        @if($ta->ganjil)
                        <form action="{{ route('admin.kelas.generate', $ta->ganjil->id_tahun_ajaran) }}" method="POST" onsubmit="return confirm('Generate 30 kelas standar?\n\nKelas yang akan dibuat:\n• X-MIPA 1-5, X-IPS 1-5\n• XI-MIPA 1-5, XI-IPS 1-5\n• XII-MIPA 1-5, XII-IPS 1-5\n\nTotal: 30 kelas')" class="inline-block">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-full font-bold transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                                </svg>
                                Generate 30 Kelas Otomatis
                            </button>
                        </form>
                        <a href="{{ route('admin.kelas.index', $ta->ganjil->id_tahun_ajaran) }}" class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-3 rounded-full font-bold transition-colors shadow-sm inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Kelola Kelas
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

            <div class="border rounded-xl shadow-sm text-center p-4">
                <h4 class="font-semibold text-gray-700">Jumlah Kelas</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">{{ $ta->jumlah_kelas }}</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    @if($ta->ganjil)
                    <a href="{{ route('admin.kelas.index', $ta->ganjil->id_tahun_ajaran) }}"
                        class="w-fit bg-blue-400 hover:bg-[#1e4b8b] text-white font-medium text-sm px-4 py-1.5 rounded-full shadow-sm transition">
                        Kelola Kelas
                    </a>
                    @endif
                </div>
            </div>

            <div class="border rounded-xl shadow-sm text-center p-4">
                <h4 class="font-semibold text-gray-700">Jumlah Siswa</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/Frame 50.png') }}" alt="Icon Siswa" class="w-14 h-14">
                    <p class="text-2xl font-bold text-blue-700">{{ $ta->jumlah_siswa }}</p>
                </div>
                <div class="flex flex-col items-center">
                    <a href="{{ route('admin.data-master.index', ['tab' => 'siswa']) }}"
                        class="mt-4 w-fit bg-blue-400 hover:bg-[#1e4b8b] text-white font-medium text-sm px-4 py-1.5 rounded-full shadow-sm transition">
                        Detail
                    </a>
                </div>
            </div>

            <div class="border rounded-xl shadow-sm text-center p-4">
                <h4 class="font-semibold text-gray-700">Jumlah Guru</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Icon Guru" class="w-14 h-14">
                    <p class="text-2xl font-bold text-blue-700">{{ $ta->jumlah_guru }}</p>
                </div>
                <div class="flex flex-col items-center">
                    <a href="{{ route('admin.data-master.index', ['tab' => 'guru']) }}"
                        class="mt-4 w-fit bg-blue-400 hover:bg-[#1e4b8b] text-white font-medium text-sm px-4 py-1.5 rounded-full shadow-sm transition">
                        Detail
                    </a>
                </div>
            </div>

            <div class="border rounded-xl shadow-sm text-center p-4">
                <h4 class="font-semibold text-gray-700">Jumlah Mapel</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/Book.png') }}" alt="Icon Mapel" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">{{ $ta->jumlah_mapel }}</p>
                </div>
                <div class="flex flex-col items-center">
                    <a href="{{ route('admin.akademik.index') }}"
                        class="mt-4 w-fit bg-blue-400 hover:bg-[#1e4b8b] text-white font-medium text-sm px-4 py-1.5 rounded-full shadow-sm transition">
                        Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 md:p-8 text-center">
        @if(request('status') && request('status') !== 'all')
            <p class="text-slate-500">Tidak ada tahun ajaran dengan status <span class="font-bold">{{ request('status') }}</span></p>
            <a href="{{ route('admin.tahun-ajaran.index') }}" class="text-blue-500 hover:underline mt-2 inline-block">Tampilkan semua</a>
        @else
            <p class="text-slate-500">Belum ada data tahun ajaran</p>
        @endif
    </div>
    @endforelse
</div>
@endsection

