@extends('layouts.guru.app')

@section('content')
    
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <a href="#" class="text-blue-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <h2 class="text-3xl font-bold text-slate-800">Nama Mata Pelajaran</h2>
        </div>
        
        <a href="#" class="flex items-center space-x-2 bg-blue-400 text-white font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Buat Materi</span>
        </a>
    </div>

    <div class="space-y-6">

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center space-x-4 mb-6">
                <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 font-bold rounded-full">1</span>
                <h3 class="text-xl font-semibold text-slate-800">Pertemuan 1</h3>
                </div>

            <div class="space-y-4">
                
                <div class="border border-slate-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                            </svg>
                            <h4 class="font-semibold text-slate-700">Berkas Materi</h4>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</button>
                            <button class="bg-blue-100 text-blue-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-blue-200 transition-colors">Download</button>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Deskripsi Materi :</p>
                        <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                    </div>
                </div>

                <div class="border border-slate-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-blue-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                            </svg>
                            <h4 class="font-semibold text-slate-700">Berkas Tugas</h4>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="bg-green-100 text-green-700 text-sm font-medium px-5 py-1 rounded-full hover:bg-green-200 transition-colors">Edit</button>
                            <button class="bg-blue-400 text-white text-sm font-medium px-5 py-1 rounded-full hover:bg-blue-500 transition-colors">Lihat</button>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Deskripsi Tugas :</p>
                        <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
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

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 font-bold rounded-full">4</span>
                    <h3 class="text-xl font-semibold text-slate-800">Pertemuan 4</h3>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 text-slate-500">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
            <div class="border border-slate-200 rounded-lg p-4 mt-6">
                <p class="text-sm text-slate-500 text-center">Anda belum membuat materi pada pertemuan ini</p>
            </div>
        </div>

    </div>

@endsection