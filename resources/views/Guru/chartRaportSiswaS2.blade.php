@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6">
    <a href="{{ route('guru.detail_raport_siswa') }}" class="text-blue-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Detail Raport</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 flex items-center space-x-4">
           <div class="rounded-full overflow-hidden w-16 h-16 ring-4 ring-blue-100">
             <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">Nama Siswa</h3>
            <p class="text-sm text-slate-500">NIS: 1083382392</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">Kelas 12</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border-2 border-blue-100">
        <h3 class="text-xl font-semibold text-blue-600 mb-8 text-center">Perkembangan Siswa</h3>
        
        <div class="relative px-6"> {{-- Container relative untuk referensi posisi garis --}}
            
            <div class="absolute top-48 left-0 right-0 h-0.5 bg-slate-300 mx-6"></div>

            <div class="flex justify-around text-center">

                <div class="flex flex-col items-center z-10"> {{-- z-10 agar bar berada di depan garis jika overlap --}}
                    <div class="h-48 flex items-end space-x-3 pb-0.5"> {{-- h-48 fix height untuk area bar --}}
                        <div class="relative w-12 h-32 bg-purple-600 rounded-t-md flex justify-center pt-2">
                            <span class="text-white font-bold text-sm">80</span>
                        </div>
                        <div class="relative w-12 h-40 bg-blue-600 rounded-t-md flex justify-center pt-2">
                            <span class="text-white font-bold text-sm">90</span>
                        </div>
                    </div>
                    <span class="mt-4 text-sm font-medium text-slate-700">Tugas</span>
                </div>

                <div class="flex flex-col items-center z-10">
                    <div class="h-48 flex items-end space-x-3 pb-0.5">
                        <div class="relative w-12 h-40 bg-purple-600 rounded-t-md flex justify-center pt-2">
                            <span class="text-white font-bold text-sm">90</span>
                        </div>
                        <div class="relative w-12 h-40 bg-blue-600 rounded-t-md flex justify-center pt-2">
                            <span class="text-white font-bold text-sm">90</span>
                        </div>
                    </div>
                    <span class="mt-4 text-sm font-medium text-slate-700">Ujian Tengah Semester</span>
                </div>

                <div class="flex flex-col items-center z-10">
                    <div class="h-48 flex items-end space-x-3 pb-0.5">
                        <div class="relative w-12 h-36 bg-purple-600 rounded-t-md flex justify-center pt-2">
                            <span class="text-white font-bold text-sm">87</span>
                        </div>
                        <div class="relative w-12 h-32 bg-blue-600 rounded-t-md flex justify-center pt-2">
                            <span class="text-white font-bold text-sm">84</span>
                        </div>
                    </div>
                    <span class="mt-4 text-sm font-medium text-slate-700">Ujian Akhir Semester</span>
                </div>

            </div>
        </div>

        <div class="flex justify-center space-x-8 mt-10">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded-full bg-purple-600"></div>
                <span class="text-sm text-slate-600 font-medium">Semester 1</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded-full bg-blue-600"></div>
                <span class="text-sm text-slate-600 font-medium">Semester 2</span>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-xl font-semibold text-slate-800 mb-6">Mengisi Nilai</h3>
        
        <div class="flex space-x-4 mb-8">
            <a href="{{ route('guru.chart_raport_siswa_s1') }}" class="px-8 py-2.5 rounded-full bg-white text-slate-600 font-semibold text-sm shadow-md shadow-blue-200">Semester 1</a>
            <button class="px-8 py-2.5 rounded-full bg-blue-500 text-white border-2 border-slate-200 font-semibold text-sm">Semester 2</button>
        </div>

        <div class="space-y-6">
            
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="text-base font-bold text-slate-800">Nilai Tugas</h4>
                </div>
                <span class="text-2xl font-bold text-slate-800">80</span>
            </div>

            <div class="flex justify-between items-center">
                <h4 class="text-base font-medium text-slate-800">Nilai Ujian Tengah Semester</h4>
                <div class="flex items-center justify-between w-32 border-2 border-slate-300 rounded-lg px-4 py-2">
                    <span class="text-slate-400 text-sm font-medium">Nilai...</span>
                    <img src="{{ asset('images/material-symbols_save-sharp (1).png') }}" class="w-5 h-5 text-slate-400 opacity-60">
                </div>
            </div>

            <div class="flex justify-between items-center">
                <h4 class="text-base font-medium text-slate-800">Nilai Ujian Akhir Semester</h4>
                 <div class="flex items-center justify-between w-32 border-2 border-slate-300 rounded-lg px-4 py-2">
                    <span class="text-slate-400 text-sm font-medium">Nilai...</span>
                    <img src="{{ asset('images/material-symbols_save-sharp (1).png') }}" class="w-5 h-5 text-slate-400 opacity-60">
                </div>
            </div>

            <div class="pt-6 mt-10 border-t-[3px] border-black"> 
                <div class="flex justify-between items-center">
                    <h4 class="text-xl font-bold text-slate-900">Nilai Akhir</h4>
                    <span class="text-3xl font-bold text-slate-900">84,4</span>
                </div>
            </div>

        </div>

    </div>

@endsection