@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
             <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Laporan Nilai Raport</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg border-2 border-blue-200 p-6">
        
        <div class="grid grid-cols-12 gap-4 mb-4 px-2">
            <div class="col-span-1 text-sm font-semibold text-blue-700">No</div>
            <div class="col-span-7 text-sm font-semibold text-blue-700">Nama</div>
            <div class="col-span-2 text-sm font-semibold text-blue-700">Nilai</div>
            <div class="col-span-2 text-sm font-semibold text-blue-700">Bobot Nilai</div>
        </div>

        <div class="space-y-4">

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        1
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        2
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        3
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        4
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        5
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        6
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        7
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        8
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        9
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-3 grid grid-cols-12 items-center gap-4 border border-slate-100">
                <div class="col-span-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-lg font-bold text-base">
                        10
                    </div>
                </div>
                <div class="col-span-7 text-sm font-medium text-slate-800">Nama Mapel</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">90</div>
                <div class="col-span-2 text-sm font-bold text-slate-900">A</div>
            </div>

        </div>

    </div> <div class="mt-6 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <span class="w-8 h-3 bg-blue-700 rounded-sm"></span>
            <span class="font-semibold text-slate-700">Rata-Rata Nilai :</span>
            <span class="font-bold text-slate-900">90</span>
        </div>
        
        <button class="flex items-center space-x-2 bg-blue-400 text-white font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <img src="{{ asset('images/download.png') }}" fill="none" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
              <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.5A1.75 1.75 0 0 1 13.25 8H6.75A1.75 1.75 0 0 1 5 6.25v-3.5ZM11.5 4a.5.5 0 0 0 0-1H8.5a.5.5 0 0 0 0 1h3Z" clip-rule="evenodd" />
              <path fill-rule="evenodd" d="M1.5 8.75A1.75 1.75 0 0 1 3.25 7h13.5A1.75 1.75 0 0 1 18.5 8.75v5.75c0 .59-.224 1.135-.59 1.562l-2.148 2.148A1.75 1.75 0 0 1 14.22 19H5.78a1.75 1.75 0 0 1-1.543-.89l-2.148-2.148A1.75 1.75 0 0 1 1.5 14.5v-5.75ZM3.25 8.5a.25.25 0 0 0-.25.25v5.75c0 .1.03.192.08.27l2.148 2.148c.078.078.17.13.27.13h8.44a.25.25 0 0 0 .19-.08l2.148-2.148a.25.25 0 0 0 .08-.27v-5.75a.25.25 0 0 0-.25-.25H3.25Z" clip-rule="evenodd" />
            </img>
            <span>Cetak Nilai</span>
        </button>
    </div>

@endsection