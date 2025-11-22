@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col h-full">
    <div class="flex items-center space-x-4 mb-4">
        <a href="{{ route('admin.kelas.index', $tahunAjaran->id_tahun_ajaran) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" class="w-8 h-8">
        </a>
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Tambah Kelas Baru</h1>
            <p class="text-slate-500 text-sm">Tahun Ajaran {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}</p>
        </div>
    </div>

    <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm">
        <h3 class="text-xl font-bold text-blue-600 mb-4 sm:mb-6">Data Kelas</h3>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
                <div class="font-bold mb-2">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.kelas.store', $tahunAjaran->id_tahun_ajaran) }}" method="POST" class="space-y-5 max-w-2xl">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Kelas</label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" required placeholder="Contoh: X-1, XI IPA 1, XII IPS 2" 
                       class="w-full border-2 {{ $errors->has('nama_kelas') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400">
                @error('nama_kelas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tingkat</label>
                <select name="tingkat" required class="w-full border-2 {{ $errors->has('tingkat') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Tingkat</option>
                    <option value="10" {{ old('tingkat') == '10' ? 'selected' : '' }}>Kelas 10</option>
                    <option value="11" {{ old('tingkat') == '11' ? 'selected' : '' }}>Kelas 11</option>
                    <option value="12" {{ old('tingkat') == '12' ? 'selected' : '' }}>Kelas 12</option>
                </select>
                @error('tingkat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Jurusan (Opsional)</label>
                <select name="jurusan" class="w-full border-2 {{ $errors->has('jurusan') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Tidak Ada Jurusan</option>
                    <option value="MIPA" {{ old('jurusan') == 'MIPA' ? 'selected' : '' }}>MIPA</option>
                    <option value="IPS" {{ old('jurusan') == 'IPS' ? 'selected' : '' }}>IPS</option>
                    <option value="Bahasa" {{ old('jurusan') == 'Bahasa' ? 'selected' : '' }}>Bahasa</option>
                </select>
                @error('jurusan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Wali Kelas (Opsional)</label>
                <select name="wali_kelas_id" class="w-full border-2 {{ $errors->has('wali_kelas_id') ? 'border-red-500' : 'border-blue-200' }} rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Wali Kelas</option>
                    @foreach($guruList as $guru)
                        <option value="{{ $guru->id_guru }}" {{ old('wali_kelas_id') == $guru->id_guru ? 'selected' : '' }}>
                            {{ $guru->nama_lengkap }} ({{ $guru->nip }})
                        </option>
                    @endforeach
                </select>
                @error('wali_kelas_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                <a href="{{ route('admin.kelas.index', $tahunAjaran->id_tahun_ajaran) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 sm:px-8 py-2.5 sm:py-3 rounded-full font-bold transition-colors text-center w-full sm:w-auto">
                    Batal
                </a>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white px-6 sm:px-8 py-2.5 sm:py-3 rounded-full font-bold flex items-center justify-center space-x-2 shadow-lg transition-all hover:shadow-xl w-full sm:w-auto">
                    <img src="{{ asset('images/save.png') }}" alt="save" class="w-5 h-5 sm:w-6 sm:h-6">
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

