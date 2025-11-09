@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
       <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Membuat Materi</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="space-y-8"> <div>
                <label for="pertemuan" class="block text-xl font-bold text-slate-900 mb-4">Pilih Pertemuan</label>
                <div class="relative w-full max-w-xs">
                    <select id="pertemuan" name="pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                        <option>Pertemuan 1</option>
                        <option>Pertemuan 2</option>
                        <option selected>Pertemuan 3</option>
                        <option>Pertemuan 4</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-600">
                        <svg class="fill-current h-6 w-6" weui_folder-filled.png viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-blue-600">Upload Berkas Berisi Materi atau Tugas</h3>

            <a href="{{ route('guru.edit_materi') }}" class="border-2 border-blue-300 rounded-full p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors max-w-lg">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Ikon Folder" class="w-8 h-8">
                    <span class="text-slate-800 font-medium">Nama Tugas/Materi</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Pilih Berkas</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full border-2 border-blue-400 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <span class="text-slate-800 font-medium text-lg">Materi</span>
                         <input type="radio" name="tipe_berkas" value="materi" class="hidden" checked>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        <span class="text-slate-800 font-medium text-lg">Tugas</span>
                        <input type="radio" name="tipe_berkas" value="tugas" class="hidden">
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Upload Berkas</label>
                <div class="mt-1 w-full max-w-xl px-6 py-10 border-2 border-blue-300 border-dashed rounded-xl bg-white">
                    <div class="space-y-3 text-center">
                        <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10" alt="Icon Upload">
                        <label for="file-upload" class="block text-sm font-medium text-blue-500 hover:text-blue-600 cursor-pointer">
                            Upload berkas anda
                            <input id="file-upload" name="file-upload" type="file" class="sr-only">
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label for="nama_berkas" class="block text-lg font-bold text-slate-900 mb-2">Nama Berkas</label>
                <input type="text" name="nama_berkas" id="nama_berkas" class="w-full max-w-lg border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500" placeholder="Maksimal 10 Karakter">
            </div>

            <div>
                <label for="deskripsi" class="block text-lg font-bold text-slate-900 mb-2">Deskripsi Materi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-lg border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 resize-none" placeholder="Deskripsi materi atau tugas anda"></textarea>
            </div>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">
                    Waktu <span class="text-base font-normal text-slate-500">(khusus berkas tugas)</span>
                </label>
                <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-x-4 max-w-md">
                    <div>
                        <div class="mb-2 text-blue-500 text-sm">Dibuka</div>
                        <input type="text" value="00:00" class="w-full text-center border-2 border-blue-300 rounded-lg py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="pt-7">
                        <div class="w-5 h-1.5 bg-blue-600 rounded-full"></div>
                    </div>
                    <div>
                        <div class="mb-2 text-blue-400 text-sm">Ditutup</div>
                         <input type="text" value="00:00" class="w-full text-center border-2 border-blue-300 rounded-lg py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="mt-12!">
                <a href="{{ route('guru.detail_tugas') }}" class="inline-flex items-center space-x-3 bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-600 transition-colors shadow-lg shadow-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Berkas</span>
                </a>
            </div>

        </div>
    </div>

@endsection