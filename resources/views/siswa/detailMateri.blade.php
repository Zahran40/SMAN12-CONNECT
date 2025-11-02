@extends('layouts.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Nama Mata Pelajaran</h2>
    </div>

    <div class="bg-white rounded-xl border-2 border-blue-200 shadow-lg">
        
        <div class="divide-y divide-slate-200">

            <div class="p-6">
                <div class="flex justify-between items-center cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                            1
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Pertemuan 1</h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-500">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div class="pl-14 pt-4 space-y-4">
                    
                    <!-- Card: Berkas Materi -->
                    <div class="border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/weui_folder-filled.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-blue-600">
                                  <path d="M3.5 3.75a.25.25 0 0 1 .25-.25h13.5a.25.25 0 0 1 .25.25v10a.75.75 0 0 0 1.5 0v-10A1.75 1.75 0 0 0 17.25 2H3.75A1.75 1.75 0 0 0 2 3.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0 0 18 14.25v-2.5a.75.75 0 0 0-1.5 0v2.5a.25.25 0 0 1-.25.25H3.75a.25.25 0 0 1-.25-.25V3.75Z" />
                                </img>
                                <span class="font-semibold text-slate-700">Berkas Materi</span>
                            </div>
                            <a href="#" class="bg-blue-600 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-blue-700 transition-colors">
                                Download
                            </a>
                        </div>
                    </div>
                    

                    <!-- Card: Deskripsi Materi -->
                    <div class="border-2 border-blue-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-slate-600">Deskripsi Materi :</p>
                        <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                    </div>
                    
                   <div class="border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/bxs_file.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-blue-600">
                                  <path d="M3.5 3.75a.25.25 0 0 1 .25-.25h13.5a.25.25 0 0 1 .25.25v10a.75.75 0 0 0 1.5 0v-10A1.75 1.75 0 0 0 17.25 2H3.75A1.75 1.75 0 0 0 2 3.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0 0 18 14.25v-2.5a.75.75 0 0 0-1.5 0v2.5a.25.25 0 0 1-.25.25H3.75a.25.25 0 0 1-.25-.25V3.75Z" />
                                </img>
                                <span class="font-semibold text-slate-700">Berkas Tugas</span>
                            </div>
                            <a href="{{ route('siswa.upload_tugas') }}" class="bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-green-600 transition-colors">
                                Upload
                            </a>
                        </div>
                    </div>

                       <div class="border-2 border-blue-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-slate-600">Deskripsi Tugas :</p>
                        <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                    </div>
                    </div>

                </div>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                            2
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Pertemuan 2</h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-500">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div class="pl-14 pt-4 space-y-4">
                    <!-- Card: Berkas Materi (Pertemuan 2) -->
                     <div class="border-2 border-blue-200 rounded-xl p-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <img src="{{ asset('images/weui_folder-filled.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-blue-600">
                                  <path d="M3.5 3.75a.25.25 0 0 1 .25-.25h13.5a.25.25 0 0 1 .25.25v10a.75.75 0 0 0 1.5 0v-10A1.75 1.75 0 0 0 17.25 2H3.75A1.75 1.75 0 0 0 2 3.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0 0 18 14.25v-2.5a.75.75 0 0 0-1.5 0v2.5a.25.25 0 0 1-.25.25H3.75a.25.25 0 0 1-.25-.25V3.75Z" />
                                </img>
                                <span class="font-semibold text-slate-700">Berkas Materi</span>
                            </div>
                            <a href="#" class="bg-blue-600 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-blue-700 transition-colors">
                                Download
                            </a>
                        </div>
                    </div>
                    <!-- Card: Deskripsi Materi (Pertemuan 2) -->
                  <div class="border-2 border-blue-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-slate-600">Deskripsi Materi :</p>
                        <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                    </div>
            
            <div class="p-6">
                <div class="flex justify-between items-center cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                            3
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Pertemuan 3</h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-500">
                      <path fill-rule="evenodd" d="M14.78 11.78a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 12.84a.75.75 0 0 1 1.06-1.06L10 15.94l3.72-3.72a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                      <path fill-rule="evenodd" d="M14.78 4.22a.75.75 0 0 1 0 1.06L10.53 9.53a.75.75 0 0 1-1.06 0L5.22 5.28a.75.75 0 0 1 1.06-1.06L10 8.06l3.72-3.72a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div class="pl-14 pt-4 space-y-4 hidden">
                    </div>
            </div>

            <div class="p-6">
                <div class="flex justify-between items-center cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 flex-shrink-0 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                            3
                        </div>
                        <h3 class="font-bold text-lg text-slate-800">Pertemuan 3</h3>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-slate-500">
                      <path fill-rule="evenodd" d="M14.78 12.28a.75.75 0 0 1-1.06 0L10 8.56l-3.72 3.72a.75.75 0 1 1-1.06-1.06l4.25-4.25a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <div class="pl-14 pt-4 space-y-4 hidden">
                    <div class="border border-slate-200 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-blue-600">
                                  <path d="M3.5 3.75a.25.25 0 0 1 .25-.25h13.5a.25.25 0 0 1 .25.25v10a.75.75 0 0 0 1.5 0v-10A1.75 1.75 0 0 0 17.25 2H3.75A1.75 1.75 0 0 0 2 3.75v10.5c0 .966.784 1.75 1.75 1.75h12.5A1.75 1.75 0 0 0 18 14.25v-2.5a.75.75 0 0 0-1.5 0v2.5a.25.25 0 0 1-.25.25H3.75a.25.25 0 0 1-.25-.25V3.75Z" />
                                </svg>
                                <span class="font-semibold text-slate-700">Berkas Tugas</span>
                            </div>
                            <button class="bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full hover:bg-green-600 transition-colors">
                                Upload
                            </button>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600">Deskripsi Tugas :</p>
                            <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                        </div>
                    </div>
                 </div>
            </div>
            
        </div>
    </div>

@endsection