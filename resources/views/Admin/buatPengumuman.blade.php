@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col h-full">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Membuat Pengumuman</h1>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm">
        <h3 class="text-xl font-bold text-blue-600 mb-4">Form Pengumuman</h3>

        <div class="space-y-5 max-w-2xl">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Pengumuman</label>
                <input type="text" placeholder="Judul Pengumuman" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Hari</label>
                <div class="relative w-48">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 pr-10 focus:outline-none focus:border-blue-500 bg-white">
                        <option>Senin</option>
                        <option>Selasa</option>
                        <option>Rabu</option>
                        <option>Kamis</option>
                        <option>Jumat</option>
                        <option>Sabtu</option>
                        <option>Minggu</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal</label>
                <div class="relative w-64">
                    <input type="date" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 bg-white" placeholder="DD/MM/YYYY">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Isi Pengumuman</label>
                <textarea placeholder="Isi pengumuman..." rows="6" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400 resize-none"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pembuat Pengumuman</label>
                <input type="text" placeholder="Nama Pembuat" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500 placeholder-slate-400">
            </div>

        </div>
    </div>

    <div class="flex justify-end mt-6">
        <a href="" class="bg-green-400 hover:bg-green-500 text-white px-8 py-3 rounded-full font-bold flex items-center space-x-2 shadow-lg transition-all hover:shadow-xl">
            <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6  ">
            <span>Simpan</span>
        </a>
    </div>
</div>
@endsection