@extends('layouts.admin.app')

@section('content')
<div class="content-wrapper p-6">

    <!-- Profil Operator -->
    <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4 mb-6">
        <img src="{{ asset('images/clarity_administrator-solid.png') }}" alt="Operator Icon" class="w-16 h-16">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Operator</h2>
            <p class="text-sm text-gray-500">ID Operator</p>
        </div>
    </div>

    <!-- Judul Halaman -->
    <div class="flex justify-between items-center mb-5">
        <h1 class="text-xl font-bold text-blue-700">Tahun Ajaran</h1>
        <button class="bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm font-medium px-4 py-2 rounded-xl shadow transition">
            <i class="fa-solid fa-plus mr-2"></i> +
            <i class="fa-solid fa-plus mr-2"></i> Tahun Ajaran
        </button>
    </div>

    <!-- Tahun Ajaran 25/26 -->
    <div class="bg-white rounded-2xl shadow p-5 mb-6">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/school.png') }}" alt="Icon Tahun Ajaran" class="w-7 h-7">
                <i class="fa-solid fa-school text-blue-700 text-xl"></i>
                <h2 class="font-semibold text-gray-700">Tahun Ajaran 25/26</h2>
            </div>
            <span class="text-xs bg-yellow-100 text-yellow-700 font-medium px-3 py-1 rounded-lg">Berlangsung</span>
        </div>

        <h3 class="font-semibold text-blue-700 mb-4">Data Tahun Ajaran</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

            <!-- KELAS -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-school text-3xl text-orange-400 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Kelas</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">18</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>

            <!-- SISWA -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-user-graduate text-3xl text-blue-700 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Siswa</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/Frame 50.png') }}" alt="Icon Siswa" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">323</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>

            <!-- GURU -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-chalkboard-user text-3xl text-green-500 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Guru</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Icon Guru" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">18</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>

            <!-- MAPEL -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-book text-3xl text-teal-500 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Mapel</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/Book.png') }}" alt="Icon Mapel" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">24</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>
        </div>
    </div>

    <!-- Tahun Ajaran 24/25 -->
    <div class="bg-white rounded-2xl shadow p-5">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/school.png') }}" alt="Icon Tahun Ajaran" class="w-7 h-7">
                <i class="fa-solid fa-school text-blue-700 text-xl"></i>
                <h2 class="font-semibold text-gray-700">Tahun Ajaran 24/25</h2>
            </div>
            <span class="text-xs bg-red-100 text-red-600 font-medium px-3 py-1 rounded-lg">Berakhir</span>
        </div>

        <h3 class="font-semibold text-blue-700 mb-4">Data Tahun Ajaran</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">

            <!-- KELAS -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-school text-3xl text-orange-400 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Kelas</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">20</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>

            <!-- SISWA -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-user-graduate text-3xl text-blue-700 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Siswa</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/Frame 50.png') }}" alt="Icon Siswa" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">323</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>

            <!-- GURU -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-chalkboard-user text-3xl text-green-500 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Guru</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Icon Guru" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">20</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>

            <!-- MAPEL -->
            <div class="border rounded-xl shadow-sm text-center p-4">
                <i class="fa-solid fa-book text-3xl text-teal-500 mb-2"></i>
                <h4 class="font-semibold text-gray-700">Jumlah Mapel</h4>
                <div class="flex justify-center items-center gap-2 my-1">
                    <img src="{{ asset('images/Book.png') }}" alt="Icon Mapel" class="w-12 h-12">
                    <p class="text-2xl font-bold text-blue-700">24</p>
                </div>
                <button class="mt-4 bg-blue-400 hover:bg-[#1e4b8b] text-white text-sm px-6 py-2 rounded-full shadow-sm transition">
                    Detail
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
