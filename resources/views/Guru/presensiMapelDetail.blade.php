@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Detail Absensi</h2>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:space-x-32 mb-8 px-2">
        <div class="mb-4 md:mb-0">
            <h3 class="text-lg font-bold text-slate-900 mb-2">Waktu Absensi</h3>
            <div class="flex items-center text-slate-800 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600 mr-2">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                </svg>
                <span>08:00-9:30</span>
            </div>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Tanggal Absensi</h3>
            <div class="text-slate-800 font-medium pl-1">
                DD/MM/YEAR
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8">
        
        <div class="grid grid-cols-12 gap-4 mb-6 px-6 text-lg font-bold text-blue-600">
            <div class="col-span-1">No</div>
            <div class="col-span-4">Nama Siswa</div>
            <div class="col-span-3">NIS</div>
            <div class="col-span-4">Status Absensi</div>
        </div>

        <div class="space-y-4">

            <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-200 rounded-full py-4 px-6">
                <div class="col-span-1">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-900 font-bold rounded-lg text-lg">1</span>
                </div>
                <div class="col-span-4 font-medium text-slate-900">Nama Siswa</div>
                <div class="col-span-3 font-medium text-slate-900">102990132801</div>
                <div class="col-span-4 flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_1" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-slate-900 font-medium">Hadir</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_1" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Sakit</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Izin</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_1" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Absen</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-200 rounded-full py-4 px-6">
                <div class="col-span-1">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-900 font-bold rounded-lg text-lg">2</span>
                </div>
                <div class="col-span-4 font-medium text-slate-900">Nama Siswa</div>
                <div class="col-span-3 font-medium text-slate-900">102990132801</div>
                <div class="col-span-4 flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-slate-900 font-medium">Hadir</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Sakit</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Izin</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Absen</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-200 rounded-full py-4 px-6">
                <div class="col-span-1">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-900 font-bold rounded-lg text-lg">3</span>
                </div>
                <div class="col-span-4 font-medium text-slate-900">Nama Siswa</div>
                <div class="col-span-3 font-medium text-slate-900">102990132801</div>
                <div class="col-span-4 flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_3" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-slate-900 font-medium">Hadir</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_3" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Sakit</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Izin</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_3" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Absen</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-200 rounded-full py-4 px-6">
                <div class="col-span-1">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-900 font-bold rounded-lg text-lg">4</span>
                </div>
                <div class="col-span-4 font-medium text-slate-900">Nama Siswa</div>
                <div class="col-span-3 font-medium text-slate-900">102990132801</div>
                <div class="col-span-4 flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_4" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-slate-900 font-medium">Hadir</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_4" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Sakit</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Izin</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_4" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Absen</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-200 rounded-full py-4 px-6">
                <div class="col-span-1">
                    <span class="flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-900 font-bold rounded-lg text-lg">5</span>
                </div>
                <div class="col-span-4 font-medium text-slate-900">Nama Siswa</div>
                <div class="col-span-3 font-medium text-slate-900">102990132801</div>
                <div class="col-span-4 flex items-center space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_5" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500" checked>
                        <span class="ml-2 text-slate-900 font-medium">Hadir</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_5" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Sakit</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_2" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Izin</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="status_5" class="w-5 h-5 text-blue-600 border-2 border-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-slate-900 font-medium">Absen</span>
                    </label>
                </div>
            </div>
            
        </div> </div> @endsection