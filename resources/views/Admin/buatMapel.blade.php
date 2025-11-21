@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col h-full">
    <div class="flex items-center space-x-4 mb-4">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Menambah Mata Pelajaran</h1>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm">
        <h3 class="text-xl font-bold text-blue-600 mb-6">Data Mata Pelajaran</h3>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="font-bold mb-2">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.akademik.mapel.store') }}" method="POST" class="space-y-5 max-w-2xl">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" value="{{ old('nama_mapel') }}" required placeholder="Contoh: Bahasa Indonesia" 
                       class="w-full border-2 {{ $errors->has('nama_mapel') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400">
                @error('nama_mapel')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Mata Pelajaran</label>
                <input type="text" name="kode_mapel" value="{{ old('kode_mapel') }}" required placeholder="Contoh: BIND" 
                       class="w-full border-2 {{ $errors->has('kode_mapel') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400">
                @error('kode_mapel')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                <select name="kategori" class="w-full border-2 {{ $errors->has('kategori') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="Umum" {{ old('kategori') == 'Umum' ? 'selected' : '' }}>Umum (Wajib)</option>
                    <option value="Kelas X" {{ old('kategori') == 'Kelas X' ? 'selected' : '' }}>Kelas X (Fase E)</option>
                    <option value="MIPA" {{ old('kategori') == 'MIPA' ? 'selected' : '' }}>MIPA (Fase F)</option>
                    <option value="IPS" {{ old('kategori') == 'IPS' ? 'selected' : '' }}>IPS (Fase F)</option>
                    <option value="Bahasa" {{ old('kategori') == 'Bahasa' ? 'selected' : '' }}>Bahasa & Budaya</option>
                    <option value="Mulok" {{ old('kategori') == 'Mulok' ? 'selected' : '' }}>Muatan Lokal</option>
                </select>
                @error('kategori')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white px-8 py-3 rounded-full font-bold flex items-center space-x-2 shadow-lg transition-all hover:shadow-xl">
                    <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6">
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection