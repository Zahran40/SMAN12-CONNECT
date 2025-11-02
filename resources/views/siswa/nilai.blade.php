@extends('layouts.siswa.app')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-6">Nilai Raport</h2>

    <div class="space-y-8">

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-3 bg-yellow-400 rounded"></span>
                    <h3 class="text-lg font-bold text-slate-800">Kelas 12</h3>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-600">Tahun Ajaran 25/26</span>
                    <button class="text-emerald-600 hover:text-emerald-700" title="Cetak Raport">
                        <img src="{{ asset('images/School.png') }}" alt="Cetak" class="w-6 h-6">
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div style="background-image: url('{{ asset('images/Frame 115 (2).png') }}')" 
                     class="bg-cover bg-center rounded-xl shadow-lg px-6 py-10 flex justify-between items-center">
                    
                    <div class="space-y-4">
                        <h4 class="text-2xl font-bold text-white">Semester 1</h4>
                        <p class="text-sm text-blue-100">Nilai Akhir Semester</p>
                        <a href="{{ route('siswa.detail_raport') }}" class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full text-sm hover:bg-slate-100 transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>

                <div style="background-image: url('{{ asset('images/Frame 115 (2).png') }}')" 
                     class="bg-cover bg-center rounded-xl shadow-lg p-10 flex justify-between items-center">
                    
                    <div class="space-y-4">
                        <h4 class="text-2xl font-bold text-white">Semester 2</h4>
                        <p class="text-sm text-blue-100">Nilai Akhir Semester</p>
                        <a href="{{ route('siswa.detail_raport') }}" class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full text-sm hover:bg-slate-100 transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-3 bg-yellow-400 rounded"></span>
                    <h3 class="text-lg font-bold text-slate-800">Kelas 11</h3>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-600">Tahun Ajaran 25/26</span>
                    <button class="text-emerald-600 hover:text-emerald-700" title="Cetak Raport">
                        <img src="{{ asset('images/School.png') }}" alt="Cetak" class="w-6 h-6">
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div style="background-image: url('{{ asset('images/Frame 115 (2).png') }}')" 
                     class="bg-cover bg-center rounded-xl shadow-lg p-10 flex justify-between items-center">
                    
                    <div class="space-y-4">
                        <h4 class="text-2xl font-bold text-white">Semester 1</h4>
                        <p class="text-sm text-blue-100">Nilai Akhir Semester</p>
                        <a href="{{ route('siswa.detail_raport') }}" class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full text-sm hover:bg-slate-100 transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>

                <div style="background-image: url('{{ asset('images/Frame 115 (2).png') }}')" 
                     class="bg-cover bg-center rounded-xl shadow-lg p-6 flex justify-between items-center">
                    
                    <div class="space-y-4">
                        <h4 class="text-2xl font-bold text-white">Semester 2</h4>
                        <p class="text-sm text-blue-100">Nilai Akhir Semester</p>
                        <a href="{{ route('siswa.detail_raport') }}" class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full text-sm hover:bg-slate-100 transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection