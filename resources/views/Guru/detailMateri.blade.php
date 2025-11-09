@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                {{-- Path di dalam <img> tidak valid, tapi saya biarkan sesuai permintaan Anda --}}
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Nama Mata Pelajaran</h2>
    </div>

    <div class="bg-white rounded-xl border-2 border-blue-200 shadow-lg">
        
        <div class="divide-y divide-slate-200">
            
            <div class="pl-14 pt-4 space-y-4">

                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 font-bold rounded-lg text-base">1</span>
                            <h3 class="text-xl font-semibold text-slate-800">Pertemuan 1</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-slate-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                        </svg>
                    </div>
                    <div class="space-y-4 pl-12"> 
                        <div>
                            <div class="border-2 border-blue-200 rounded-xl p-4 mb-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Icon Folder" class="w-6 h-6">
                                        <span class="font-semibold text-slate-700">Berkas Materi</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</button>
                                        <button class="bg-blue-400 text-white text-sm font-medium px-5 py-1 rounded-full hover:bg-blue-500 transition-colors">Download</button>
                                    </div>
                                </div>
                            </div>
                            <div class="border-2 border-blue-200 rounded-xl p-4">
                                <p class="text-sm font-medium text-slate-600 mb-1">Deskripsi Materi :</p>
                                <p class="text-sm text-slate-500 leading-relaxed">lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                            </div>
                        </div>
                        <div class="pt-4"> 
                            <div class="border-2 border-blue-200 rounded-xl p-4 mb-2">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('images/bxs_file.png') }}" alt="Icon File" class="w-6 h-6">
                                        <span class="font-semibold text-slate-700">Berkas Tugas</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</button>
                                        <button class="bg-blue-400 text-white text-sm font-medium px-9 py-1 rounded-full hover:bg-blue-500 transition-colors">Lihat</button> 
                                    </div>
                                </div>
                            </div>
                            <div class="border-2 border-blue-200 rounded-xl p-4">
                                <p class="text-sm font-medium text-slate-600 mb-1">Deskripsi Tugas :</p>
                                <p class="text-sm text-slate-500 leading-relaxed">lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 font-bold rounded-full">2</span>
                            <h3 class="text-xl font-semibold text-slate-800">Pertemuan 2</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-slate-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    <div class="border border-slate-200 rounded-lg p-4 mt-6">
                        <p class="text-sm text-slate-500 text-center">Anda belum membuat materi pada pertemuan ini</p>
                    </div>
                </div>
                    
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 font-bold rounded-full">3</span>
                            <h3 class="text-xl font-semibold text-slate-800">Pertemuan 3</h3>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-slate-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>
                    <div class="border border-slate-200 rounded-lg p-4 mt-6">
                        <p class="text-sm text-slate-500 text-center">Anda belum membuat materi pada pertemuan ini</p>
                    </div>
                </div>

            </div> </div>
    </div>

@endsection