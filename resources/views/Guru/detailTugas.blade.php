@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Tugas Anda</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        
        <div class="flex items-center space-x-3 mb-4">
            <img src="{{ asset('images/bxs_file.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125V6a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
            </img>
            <h3 class="text-2xl font-bold text-slate-800">Nama Tugas</h3>
        </div>

        <div class="flex justify-between items-center mb-5">
            <span class="text-sm font-medium text-slate-600">Pertemuan ke-1</span>
            <div class="flex items-center space-x-1.5 text-sm font-medium text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 2 0v-3a1 1 0 0 0-2 0Z" clip-rule="evenodd" />
                </svg>
                <span>Ditutup Senin 09:30</span>
            </div>
        </div>

        <div class="border border-slate-200 rounded-lg p-4 mb-5">
            <span class="text-base font-semibold text-slate-700">Nama File Tugas</span>
        </div>

        <div class="border border-slate-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-slate-700 mb-1">Deskripsi Tugas :</h4>
            <p class="text-sm text-slate-500">
                lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua
            </p>
        </div>
    </div>


    <div class="bg-white rounded-xl shadow-lg overflow-hidden"> <div class="p-6 pb-4"> <h3 class="text-2xl font-bold text-slate-800">Daftar Siswa</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-y border-slate-200"> <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500">No</th> <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500">Nama</th>
                    <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500">NIS</th>
                    <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500">File Pengumpulan</th>
                    <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500">Nilai Tugas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                
                <tr>
                    <td class="px-6 py-4">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 font-bold rounded-lg text-sm">1</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-700">Nama Siswa</td>
                    <td class="px-6 py-4 text-sm text-slate-500">10210913821891</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-between bg-white border-2 border-blue-200 rounded-lg py-2 px-3 w-48"> <div class="flex items-center space-x-2">
                                <img src="{{ asset('images/bxs_file.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                                    <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm5.845 17.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V12a.75.75 0 00-1.5 0v4.19l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd" />
                                    <path d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                                </img>
                                <span class="text-sm font-medium text-slate-700 truncate">Tugas1.Zip</span>
                            </div>
                            <button class="text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-between bg-white border-2 border-slate-200 rounded-lg py-2 px-3 w-32">
                            <span class="text-sm text-slate-400">Nilai...</span>
                            <button class="text-slate-400 hover:text-slate-600">
                                <img src="{{ asset('images/material-symbols_save-sharp.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M10 2c-1.716 0-3.408.106-5.07.31C3.806 2.45 3 3.414 3 4.517V17.25a.75.75 0 001.075.676L10 15.082l5.925 2.844A.75.75 0 0017 17.25V4.517c0-1.103-.806-2.068-1.93-2.207A41.403 41.403 0 0010 2z" clip-rule="evenodd" />
                                </img> </button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="px-6 py-4">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 font-bold rounded-lg text-sm">1</span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-700">Nama Siswa</td>
                    <td class="px-6 py-4 text-sm text-slate-500">10210913821891</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-between bg-white border-2 border-blue-200 rounded-lg py-2 px-3 w-48">
                            <div class="flex items-center space-x-2">
                                <img src="{{ asset('images/bxs_file.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-blue-600">
                                    <path fill-rule="evenodd" d="M5.625 1.5H9a3.75 3.75 0 013.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 013.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 01-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875zm5.845 17.03a.75.75 0 001.06 0l3-3a.75.75 0 10-1.06-1.06l-1.72 1.72V12a.75.75 0 00-1.5 0v4.19l-1.72-1.72a.75.75 0 00-1.06 1.06l3 3z" clip-rule="evenodd" />
                                    <path d="M14.25 5.25a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963A5.23 5.23 0 0016.5 7.5h-1.875a.375.375 0 01-.375-.375V5.25z" />
                                </img>
                                <span class="text-sm font-medium text-slate-700 truncate">Tugas1.Zip</span>
                            </div>
                            <button class="text-blue-500 hover:text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end space-x-4 w-32 px-3"> <span class="text-lg font-bold text-slate-900">90</span>
                            <button class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32L19.513 8.2z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                </tbody>
        </table>
    </div>
</div>
    
@endsection