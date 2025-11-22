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


    <div class="flex justify-between items-center mb-5">
        <h1 class="text-xl font-bold text-blue-700">Tahun Ajaran</h1>
        <div class="flex items-center space-x-3">
            {{-- Filter Status --}}
            <form method="GET" action="{{ route('admin.tahun-ajaran.index') }}" class="flex items-center space-x-2">
                <select name="status" onchange="this.form.submit()" class="border-2 border-blue-300 rounded-lg px-4 py-2 text-sm font-medium text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </form>

            {{-- Tombol Hapus Semua Tidak Aktif --}}
            @if(\App\Models\TahunAjaran::where('status', 'Tidak Aktif')->count() > 0)
                <form action="{{ route('admin.tahun-ajaran.destroy-inactive') }}" method="POST" onsubmit="return confirm('⚠️ PERHATIAN!\n\nAnda akan menghapus SEMUA tahun ajaran tidak aktif beserta:\n- Semua kelas\n- Semua siswa di kelas tersebut\n- Semua jadwal pelajaran\n\nProses ini TIDAK DAPAT DIBATALKAN!\n\nLanjutkan?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus Semua Tidak Aktif</span>
                    </button>
                </form>
            @endif
            
            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.tahun-ajaran.create') }}" class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full font-bold flex items-center space-x-2 shadow-sm transition-colors">
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
        </div>

        {{-- Semester Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 sm:mb-6">
            {{-- Semester Ganjil --}}
            @if($ta->ganjil)
            <div class="border-2 {{ $ta->ganjil->status == 'Aktif' ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-gray-50' }} rounded-xl p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">📅 Semester Ganjil</h3>
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
                        {{ $ta->ganjil->status == 'Aktif' ? '❌ Nonaktifkan' : '✅ Aktifkan' }}
                    </button>
                </form>
            </div>
            @endif

            {{-- Semester Genap --}}
            @if($ta->genap)
            <div class="border-2 {{ $ta->genap->status == 'Aktif' ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-gray-50' }} rounded-xl p-4">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">📅 Semester Genap</h3>
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
                        {{ $ta->genap->status == 'Aktif' ? '❌ Nonaktifkan' : '✅ Aktifkan' }}
                    </button>
                </form>
            </div>
            @endif
        </div>

        <h3 class="font-semibold text-blue-700 mb-4">Data Tahun Ajaran (Kedua Semester)</h3>
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

