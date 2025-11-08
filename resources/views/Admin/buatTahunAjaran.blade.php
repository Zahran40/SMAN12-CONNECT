@extends('layouts.admin.app')

@section('content')
<div class="content-wrapper p-6">

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Membuat Tahun Ajaran Baru</h1>
    </div>
    
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-xl font-bold text-blue-700 mb-6">Tahun Ajaran Baru</h1>

        
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-blue-700 mb-4">Tahun & Semester</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Tahun Ajaran</label>
                    <p class="text-xs text-gray-500 mb-2">(Otomatis memuat tahun ajaran terbaru)</p>
                    <input type="text" value="2026/2027" class="border border-gray-300 rounded-lg p-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-400">
                </div>

               
                <div class="mt-6">
                    <label class="block text-gray-700 font-medium mb-1">Semester</label>
                    <select class="border border-gray-300 rounded-lg p-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-400">
                        <option>Ganjil</option>
                        <option selected>Genap</option>
                    </select>
                </div>
            </div>
        </div>

      
        <div x-data="{ selected: null }">
            <h2 class="text-lg font-semibold text-blue-700 mb-10">Kelas Yang Digunakan</h2>

            <div class="flex flex-wrap gap-4 mb-6">

                
                @foreach (['1A', '1B', '1C', '3A'] as $index => $kelas)
                <div class="border rounded-xl p-4 text-center w-40 shadow-sm relative cursor-pointer transition hover:shadow-md bg-white">
                    
                    <button type="button" class="absolute top-2 left-2">
                        <img src="{{ asset('images/minus.png') }}" alt="Hapus" class="w-5 h-5 opacity-70 hover:opacity-100 transition">
                    </button>

                    <img src="{{ asset('images/noto_school.png') }}" alt="Icon Kelas" class="w-16 h-16 mx-auto mb-2">
                    <p class="font-semibold text-blue-700">Kelas {{ $kelas }}</p>
                </div>
                @endforeach
            </div>


            <button class="flex items-center gap-2 bg-blue-400 hover:bg-[#1e4b8b] text-white px-4 py-2 rounded-xl shadow transition mt-8">
                <i class="fa-solid fa-plus"></i> Tambah Kelas
            </button>

        </div>

        
        <div class="mt-10 text-right">
            <button class="bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-2 rounded-xl shadow transition">
                <div class="flex items-center gap-0.2">
                    <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6  ">
                    <i class="fa-solid fa-upload mr-2"></i> Terbitkan Tahun Ajaran
                </div>

            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection