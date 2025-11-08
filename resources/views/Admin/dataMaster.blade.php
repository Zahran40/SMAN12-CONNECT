@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Manajemen Data Master</h1>
            <p class="text-slate-500 text-sm">(Manajemen untuk data-data kelas, siswa, guru dan mata pelajaran)</p>
        </div>

        <div class="flex space-x-4">
            <button class="px-8 py-2 bg-blue-400 text-white font-semibold rounded-full shadow-sm hover:bg-blue-500 transition-colors">
                Kelas
            </button>
            <a href="{{route ('admin.data_master_siswa')}}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Siswa
            </a>
            <a href="{{route ('admin.data_master_guru')}}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Guru
            </a>
             <a href="{{route ('admin.data_master_mapel')}}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Mata Pelajaran
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option>2024/2025</option>
                        <option>2025/2026</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
             <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Semester</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option>Genap</option>
                        <option>Ganjil</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
             <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
                <div class="relative">
                    <select class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option>Kelas 2</option>
                        <option>Kelas 1</option>
                        <option>Kelas 3</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="border-2 border-blue-200 rounded-2xl p-6 flex flex-col items-center bg-white">
                    <img src="{{ asset('images/noto_school.png') }}" alt="class" class="w-20 h-20 mb-4">
                    <div class="w-full text-left mb-4">
                        <p class="text-xs text-slate-500 mb-1">2024/2025</p>
                        <h3 class="text-blue-600 font-bold text-lg">Kelas 2A</h3>
                        <div class="flex items-center text-slate-500 text-sm mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            <span>36 Siswa</span>
                        </div>
                    </div>
                    <a href="{{ route ('admin.data_master_siswa1') }}" class="w-full bg-blue-400 hover:bg-blue-500 text-white py-2 rounded-full font-semibold transition-colors text-center">
                        Detail
                    </a>
                </div>

                 <div class="border-2 border-blue-200 rounded-2xl p-6 flex flex-col items-center bg-white">
                    <img src="{{ asset('images/noto_school.png') }}" alt="class" class="w-20 h-20 mb-4">
                    <div class="w-full text-left mb-4">
                        <p class="text-xs text-slate-500 mb-1">2024/2025</p>
                        <h3 class="text-blue-600 font-bold text-lg">Kelas 2B</h3>
                        <div class="flex items-center text-slate-500 text-sm mt-1">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            <span>36 Siswa</span>
                        </div>
                    </div>
                    <a href="{{ route ('admin.data_master_siswa1') }}" class="w-full bg-blue-400 hover:bg-blue-500 text-white py-2 rounded-full font-semibold transition-colors text-center">
                        Detail
                    </a>
                </div>

                 <div class="border-2 border-blue-200 rounded-2xl p-6 flex flex-col items-center bg-white">
                    <img src="{{ asset('images/noto_school.png') }}" alt="class" class="w-20 h-20 mb-4">
                    <div class="w-full text-left mb-4">
                        <p class="text-xs text-slate-500 mb-1">2024/2025</p>
                        <h3 class="text-blue-600 font-bold text-lg">Kelas 2C</h3>
                        <div class="flex items-center text-slate-500 text-sm mt-1">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            <span>36 Siswa</span>
                        </div>
                    </div>
                    <a href="{{ route ('admin.data_master_siswa1') }}" class="w-full bg-blue-400 hover:bg-blue-500 text-white py-2 rounded-full font-semibold transition-colors text-center">
                        Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
