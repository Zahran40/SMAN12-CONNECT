@extends('layouts.guru.app')

@section('content')

    <!-- Header Halaman -->
    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Membuat Materi</h2>
    </div>

   
    <div class="bg-white rounded-xl shadow-lg p-8">
        
        <!-- Wrapper Form (tanpa centering) -->
        <div class="space-y-6">

            <!-- Pilih Pertemuan -->
            <div>
                <label for="pertemuan" class="block text-base font-semibold text-slate-800 mb-2">Pilih Pertemuan</label>
                <select id="pertemuan" name="pertemuan" class="block w-full max-w-sm border-blue-300 rounded-xl shadow-sm focus:ring-blue-400 focus:border-blue-400 py-3 px-4">
                    <option>Pertemuan 1</option>
                    <option>Pertemuan 2</option>
                    <option selected>Pertemuan 3</option>
                    <option>Pertemuan 4</option>
                </select>
            </div>

            <h3 class="text-xl font-semibold text-blue-600 !mt-8 mb-6">Upload Berkas Berisi Materi atau Tugas</h3>

            <!-- Pilih Berkas (Radio) -->
            <div>
                <label class="block text-base font-semibold text-slate-800 mb-3">Pilih Berkas</label>
                <div class="flex items-center space-x-6">
                    <label for="tipe_materi" class="flex items-center space-x-3 cursor-pointer">
                        <input id="tipe_materi" name="tipe_berkas" type="radio" class="w-5 h-5 text-blue-500 focus:ring-blue-400" checked>
                        <span class="text-slate-700 font-medium">Materi</span>
                    </label>
                    <label for="tipe_tugas" class="flex items-center space-x-3 cursor-pointer">
                        <input id="tipe_tugas" name="tipe_berkas" type="radio" class="w-5 h-5 text-slate-400 focus:ring-blue-400">
                        <span class="text-slate-700 font-medium">Tugas</span>
                    </label>
                </div>
            </div>

            <!-- Upload Berkas -->
            <div>
                <label class="block text-base font-semibold text-slate-800 mb-2">Upload Berkas</label>
                <div class="mt-1 flex justify-center px-6 py-10 border-2 border-blue-300 border-dashed rounded-xl">
                    <div class="space-y-1 text-center">
                        <!-- Ikon Upload (SVG) -->
                        <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10 text-blue-400"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </img>
                        <div class="flex text-sm text-slate-600">
                            <label for="file-upload" class="relative cursor-pointer font-medium text-blue-500 hover:text-blue-600">
                                <span>Upload berkas anda</span>
                                <input id="file-upload" name="file-upload" type="file" class="sr-only">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nama Berkas -->
            <div>
                <label for="nama_berkas" class="block text-base font-semibold text-slate-800 mb-2">Nama Berkas</label>
                <input type="text" name="nama_berkas" id="nama_berkas" class="mt-1 block w-full border-blue-300 rounded-xl shadow-sm focus:ring-blue-400 focus:border-blue-400 py-3 px-4 placeholder:text-slate-400" placeholder="Maksimal 10 Karakter">
                <!-- <p class="mt-1 text-xs text-slate-500">Maksimal 10 karakter</p> --> <!-- Diubah jadi placeholder -->
            </div>

            <!-- Deskripsi Materi -->
            <div>
                <label for="deskripsi" class="block text-base font-semibold text-slate-800 mb-2">Deskripsi Materi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full border-blue-300 rounded-xl shadow-sm focus:ring-blue-400 focus:border-blue-400 py-3 px-4 placeholder:text-slate-400" placeholder="Deskripsi materi atau tugas anda"></textarea>
            </div>

            <!-- Waktu (Khusus Tugas) -->
           <div>
    <label class="block text-base font-semibold text-slate-800 mb-4">
        Waktu <span class="text-sm text-slate-400 font-medium">(khusus berkas tugas)</span>
    </label>
    
    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-x-4 max-w-lg">

        <div>
            <div class="mb-4 border-b-2 border-transparent inline-block pb-1">
                <span class="text-slate-500 font-bold">Dibuka</span>
            </div>
            <input type="text" value="00:00" class="w-full text-center border-blue-300 rounded-xl shadow-sm focus:ring-blue-400 focus:border-blue-400 py-3 font-medium text-slate-700">
        </div>

        <div class="pt-10"> <span class="text-blue-500 font-bold text-2xl">–</span>
        </div>

        <div>
             <div class="mb-4 pb-1 inline-block border-b-2 border-transparent"> <span class="text-slate-500 font-medium">Ditutup</span>
            </div>
            <input type="text" value="00:00" class="w-full text-center border-blue-300 rounded-xl shadow-sm focus:ring-blue-400 focus:border-blue-400 py-3 font-medium text-slate-700">
        </div>

    </div>
</div>

            <!-- Tombol Tambah Berkas -->
            <div class="!mt-10"> <!-- !mt-10 untuk memberi jarak lebih -->
                <button type="button" class="flex items-center justify-center space-x-3 bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-600 transition-colors shadow-lg shadow-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Berkas</span>
                </button>
            </div>

        </div> <!-- end space-y-6 -->
    </div> <!-- end bg-white -->

    <!-- Tombol Simpan (Footer) -->
    <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex justify-end">
            <button type="submit" class="flex items-center space-x-2 bg-green-500 text-white font-medium px-6 py-2 rounded-full hover:bg-green-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                <span>Simpan</span>
            </button>
        </div>
    </div>

@endsection