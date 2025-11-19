@extends('layouts.admin.app')

@section('content')
<div class="content-wrapper p-6">

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Membuat Tahun Ajaran Baru</h1>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <div class="font-bold mb-2">Terjadi kesalahan:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-xl font-bold text-blue-700 mb-6">Tahun Ajaran Baru</h1>

        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
            @csrf
        
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-blue-700 mb-4">Tahun & Semester</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-gray-700 font-medium mb-1">Tahun Ajaran</label>
                    <select name="tahun_ajaran" required class="border {{ $errors->has('tahun_ajaran') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-400">
                        @php
                            $currentYear = date('Y');
                            $selectedYear = old('tahun_ajaran', $currentYear . '/' . ($currentYear + 1));
                        @endphp
                        @for ($year = $currentYear - 1; $year <= $currentYear + 5; $year++)
                            <option value="{{ $year }}/{{ $year + 1 }}" {{ $selectedYear == $year . '/' . ($year + 1) ? 'selected' : '' }}>
                                {{ $year }}/{{ $year + 1 }}
                            </option>
                        @endfor
                    </select>
                    @error('tahun_ajaran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

               
                <div class="mt-6">
                    <label class="block text-gray-700 font-medium mb-1">Semester</label>
                    <select name="semester" required class="border {{ $errors->has('semester') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-2 w-full focus:ring focus:ring-blue-200 focus:border-blue-400">
                        <option value="Ganjil" {{ old('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester', 'Genap') == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-gray-700 font-medium mb-1">Status</label>
                <select name="status" required class="border {{ $errors->has('status') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-2 w-full md:w-1/2 focus:ring focus:ring-blue-200 focus:border-blue-400">
                    <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        
        <div class="mt-10 text-right">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-2 rounded-xl shadow transition">
                <div class="flex items-center gap-0.2">
                    <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6  ">
                    <i class="fa-solid fa-upload mr-2"></i> Simpan Tahun Ajaran
                </div>

            </button>
        </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection