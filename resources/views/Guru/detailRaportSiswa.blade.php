@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
         <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Nama Mata Pelajaran</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 border-2 border-blue-400"> <div class="mb-8">
            <h3 class="text-2xl font-bold text-slate-900 mb-1">Daftar Siswa Anda</h3>
            <p class="text-sm text-slate-500">Pilih siswa yang ingin anda isi nilainya</p>
        </div>

        <div>
            <div class="grid grid-cols-12 gap-4 px-6 mb-4 text-sm font-bold text-blue-600 uppercase tracking-wider">
                <div class="col-span-1">No</div>
                <div class="col-span-5">Nama</div>
                <div class="col-span-4">NIS</div>
                <div class="col-span-2 text-right">Detail Raport</div>
            </div>

            <div class="space-y-3"> <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 border-2 border-blue-200 rounded-full py-5 px-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="col-span-1">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-slate-800 font-bold rounded-lg text-sm">1</span>
                    </div>
                    <div class="col-span-5 font-semibold text-slate-800">Nama Siswa</div>
                    <div class="col-span-4 font-semibold text-slate-800">1029813911041</div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.chart_raport_siswa_s1') }}" class="text-blue-600 hover:text-blue-800">
                            <img src="{{ asset('images/solar_book-2-bold.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                <path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                            </img>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 border-2 border-blue-200 rounded-full py-5 px-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="col-span-1">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-slate-800 font-bold rounded-lg text-sm">2</span>
                    </div>
                    <div class="col-span-5 font-semibold text-slate-800">Nama Siswa</div>
                    <div class="col-span-4 font-semibold text-slate-800">1029813911041</div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.chart_raport_siswa_s1') }}" class="text-blue-600 hover:text-blue-800">
                            <img src="{{ asset('images/solar_book-2-bold.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                <path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                            </img>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-4 items-center bg-slate-50 border-2 border-blue-200 rounded-full py-5 px-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="col-span-1">
                        <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-slate-800 font-bold rounded-lg text-sm">3</span>
                    </div>
                    <div class="col-span-5 font-semibold text-slate-800">Nama Siswa</div>
                    <div class="col-span-4 font-semibold text-slate-800">1029813911041</div>
                    <div class="col-span-2 text-right flex justify-end">
                        <a href="{{ route('guru.chart_raport_siswa_s1') }}" class="text-blue-600 hover:text-blue-800">
                            <img src="{{ asset('images/solar_book-2-bold.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
                                <path d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                            </img>
                        </a>
                    </div>
                </div>

                </div>
        </div>
    </div>

@endsection