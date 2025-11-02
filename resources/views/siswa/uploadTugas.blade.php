@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Upload Tugas</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">

        <div class="flex items-center space-x-3 mb-2">
            <img src="{{ asset('images/bxs_file.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-7 h-7 text-blue-600">
              <path fill-rule="evenodd" d="M4 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.343a1 1 0 0 0-.293-.707l-3.414-3.414A1 1 0 0 0 13.657 4H4Zm6 6a1 1 0 1 0-2 0v3.5a.5.5 0 0 1-1 0V8a1 1 0 1 0-2 0v3.5a.5.5 0 0 1-1 0V8a1 1 0 0 0-1 1v1.5a1.5 1.5 0 0 0 1.5 1.5h1.5a.5.5 0 0 1 1 0V8a1 1 0 0 0-1-1H4.5a.5.5 0 0 1 0-1h3.5a1 1 0 0 1 1 1v1.5a.5.5 0 0 1-1 0V8a1 1 0 1 0-2 0v3.5A2.5 2.5 0 0 0 7.5 14h1A2.5 2.5 0 0 0 11 11.5V8a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
            </img>
            <h3 class="text-2xl font-bold text-slate-900">Nama Tugas</h3>
        </div>

        <div class="flex items-center space-x-2 text-sm text-slate-500 mb-6 ml-1">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
              <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
            </svg>
            <span>Ditutup Senin 09:30</span>
        </div>

         <div class="border-2 border-blue-200 rounded-xl p-4">
                        <p class="text-sm font-medium text-slate-600">Deskripsi Tugas :</p>
                        <p class="text-sm text-slate-500">lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua</p>
                    </div>

        <div class="mt-6">
            <div class="border-2 border-dashed border-blue-400 bg-blue-50/30 rounded-lg p-10 flex flex-col items-center justify-center text-center">
                <img src="{{ asset('images/Vector.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-blue-600 mb-3">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                </img>
                <p class="font-semibold text-slate-700">Tarik atau Upload File</p>
                </div>
        </div>
        
    </div>

@endsection