@extends('layouts.admin.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="content-wrapper p-6">

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4 mb-6">
        <img src="{{ asset('images/clarity_administrator-solid.png') }}" alt="Operator Icon" class="w-16 h-16">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Operator</h2>
            
        </div>
    </div>


    <div class="flex justify-between items-center mb-5">
        <h1 class="text-xl font-bold text-blue-700">Tahun Ajaran</h1>
        <a href="{{ route('admin.tahun-ajaran.create') }}" class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full font-bold flex items-center space-x-2 shadow-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            <span>Tahun ajaran</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif


    @forelse($tahunAjaran as $index => $ta)
    <div class="bg-white rounded-2xl shadow p-5 {{ $index < count($tahunAjaran) - 1 ? 'mb-6' : '' }}">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/school.png') }}" alt="Icon Tahun Ajaran" class="w-7 h-7">
                <i class="fa-solid fa-school text-blue-700 text-xl"></i>
                <h2 class="font-semibold text-gray-700">Tahun Ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}</h2>
            </div>
            <div class="flex items-center gap-3">
                @if($ta->status == 'Aktif')
                    <span class="text-xs bg-green-100 text-green-700 font-medium px-3 py-1 rounded-lg">{{ $ta->status }}</span>
                @else
                    <span class="text-xs bg-red-100 text-red-600 font-medium px-3 py-1 rounded-lg">{{ $ta->status }}</span>
                @endif
                <form action="{{ route('admin.tahun-ajaran.update-status', $ta->id_tahun_ajaran) }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="{{ $ta->status == 'Aktif' ? 'Tidak Aktif' : 'Aktif' }}">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">
                        {{ $ta->status == 'Aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                </form>
            </div>
        </div>

        <h3 class="font-semibold text-blue-700 mb-4">Data Tahun Ajaran</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-school text-3xl text-orange-400 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Kelas</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">{{ $ta->jumlah_kelas }}</p>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <a href="{{ route('admin.kelas.index', $ta->id_tahun_ajaran) }}"
                        class="w-fit bg-blue-400 hover:bg-[#1e4b8b] text-white font-medium text-sm px-4 py-1.5 rounded-full shadow-sm transition">
                        Kelola Kelas
                    </a>
                </div>
            </div>

            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-user-graduate text-3xl text-blue-700 mb-2"></i>
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
                <i class="fa-solid fa-chalkboard-user text-3xl text-green-500 mb-2"></i>
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
                <i class="fa-solid fa-book text-3xl text-teal-500 mb-2"></i>
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
    <div class="bg-white rounded-2xl shadow p-8 text-center">
        <p class="text-slate-500">Belum ada data tahun ajaran</p>
    </div>
    @endforelse
</div>
@endsection
