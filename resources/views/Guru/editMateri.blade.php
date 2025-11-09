@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Edit Berkas Pertemuan</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="space-y-8">

            <div class="flex justify-between items-start">
                <div>
                    <label for="pertemuan" class="block text-2xl font-bold text-slate-800 mb-4">Pilih Pertemuan</label>
                    <div class="relative w-full max-w-xs">
                        <select id="pertemuan" name="pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            <option>Pertemuan 1</option>
                            <option>Pertemuan 2</option>
                            <option selected>Pertemuan 3</option>
                            <option>Pertemuan 4</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-600">
                            <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
                <button type="button" class="flex items-center space-x-2 bg-red-500 text-white font-bold text-lg px-6 py-3 rounded-full hover:bg-red-600 transition-colors shadow-md shadow-red-200">
                    <img src="{{ asset('images/material-symbols_delete.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.49 1.478l-.56-.095q-.53-.103-1.073-.178c-1.312-.178-2.654-.227-4.005-.227-1.35 0-2.693.05-4.005.227q-.542.075-1.073.178c-.186.036-.375.068-.56.095a.75.75 0 0 1-.49-1.478c1.287-.175 2.594-.346 3.878-.512v-.227c0-1.121.884-2.017 2.005-2.017h3.89c1.12 0 2.005.896 2.005 2.017ZM15.65 7.658a.75.75 0 0 0-.992.123l-1.418 1.419-1.418-1.419a.75.75 0 1 0-1.062 1.062l1.419 1.418-1.419 1.418a.75.75 0 1 0 1.062 1.062l1.418-1.419 1.418 1.419a.75.75 0 1 0 1.062-1.062l-1.419-1.418 1.419-1.418a.75.75 0 0 0-.123-.992Z" clip-rule="evenodd" />
                        <path d="M4.574 9.566c.032-.573.52-1.016 1.095-1.016h12.662c.575 0 1.063.443 1.095 1.016l.333 10.232c.056 1.73-1.324 3.202-3.055 3.202H7.296c-1.73 0-3.111-1.472-3.055-3.202l.333-10.232Z" />
                    </img>
                    <span>Hapus</span>
                </button>
            </div>

            <h3 class="text-2xl font-bold text-blue-600">Edit Berkas Berisi Materi atau Tugas</h3>

            <div>
                <label class="block text-xl font-bold text-slate-800 mb-4">Pilih Berkas</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full border-2 border-blue-400 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <span class="text-slate-700 font-medium text-lg">Materi</span>
                         <input type="radio" name="tipe_berkas" value="materi" class="hidden" checked>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        <span class="text-slate-700 font-medium text-lg">Tugas</span>
                        <input type="radio" name="tipe_berkas" value="tugas" class="hidden">
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xl font-bold text-slate-900 mb-4">Upload Berkas</label>
                
                <div class="border-2 border-blue-300 rounded-full py-3 px-6 flex items-center justify-between mb-4 max-w-lg">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/bxs_file.png') }}" alt="Ikon File" class="w-8 h-8">
                        <span class="text-slate-800 font-medium">Nama Berkas.Zip</span>
                    </div>
                    <button type="button" class="text-red-500 hover:text-red-700 transition-colors">
                        <img src="{{ asset('images/material-symbols_delete (1).png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.49 1.478l-.56-.095q-.53-.103-1.073-.178c-1.312-.178-2.654-.227-4.005-.227-1.35 0-2.693.05-4.005.227q-.542.075-1.073.178c-.186.036-.375.068-.56.095a.75.75 0 0 1-.49-1.478c1.287-.175 2.594-.346 3.878-.512v-.227c0-1.121.884-2.017 2.005-2.017h3.89c1.12 0 2.005.896 2.005 2.017ZM15.65 7.658a.75.75 0 0 0-.992.123l-1.418 1.419-1.418-1.419a.75.75 0 1 0-1.062 1.062l1.419 1.418-1.419 1.418a.75.75 0 1 0 1.062 1.062l1.418-1.419 1.418 1.419a.75.75 0 0 0-.123-.992Z" clip-rule="evenodd" />
                            <path d="M4.574 9.566c.032-.573.52-1.016 1.095-1.016h12.662c.575 0 1.063.443 1.095 1.016l.333 10.232c.056 1.73-1.324 3.202-3.055 3.202H7.296c-1.73 0-3.111-1.472-3.055-3.202l.333-10.232Z" />
                        </img>
                    </button>
                </div>

                <div class="mt-1 flex justify-center px-6 py-12 border-2 border-blue-500 border-dashed rounded-xl bg-slate-50/50 max-w-lg">
                    <div class="space-y-2 text-center">
                      <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10 text-blue-400"  fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </img>
                        <div class="text-sm text-slate-600 font-medium">
                            <label for="file-upload" class="relative cursor-pointer text-slate-600 hover:text-blue-600">
                                <span>Tambah berkas</span>
                                <input id="file-upload" name="file-upload" type="file" class="sr-only">
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label for="nama_berkas" class="block text-xl font-bold text-slate-900 mb-2">Nama Berkas</label>
                <input type="text" name="nama_berkas" id="nama_berkas" class="w-full max-w-lg border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 placeholder:text-slate-500 focus:outline-none focus:border-blue-500" placeholder="Nama Berkas Sebelumnya">
            </div>

            <div>
                <label for="deskripsi" class="block text-xl font-bold text-slate-900 mb-2">Deskripsi Materi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-lg border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 placeholder:text-slate-500 focus:outline-none focus:border-blue-500 resize-none" placeholder="Deskripsi materi atau tugas anda sebelumnya"></textarea>
            </div>

            <div>
                <label class="block text-xl font-bold text-slate-900 mb-4">
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

        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex justify-end">
            <a href="{{ route('guru.detail_tugas') }}" class="flex items-center space-x-2 bg-green-500 text-white font-medium px-6 py-2 rounded-full hover:bg-green-600 transition-colors shadow-md shadow-green-200">
                <img src="{{ asset('images/save.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </img>
                <span>Simpan</span>
            </a>
        </div>
    </div>

@endsection