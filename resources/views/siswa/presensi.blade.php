@extends('layouts.app')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-6">Presensi Kehadiran</h2>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full">
            
            <thead class="bg-white border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider w-1/12">
                        No
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider w-4/12">
                        Mata Pelajaran
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider w-4/12">
                        Guru
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-blue-600 uppercase tracking-wider w-3/12">
                        Detail Absensi
                    </th>
                </tr>
            </thead>
            
            <tbody class="divide-y divide-slate-100">
                
                <tr class="bg-white hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            1
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 1
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center text-white rounded-lg">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>

                <tr class="bg-blue-50/30 hover:bg-blue-50/60">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            2
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 2
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center text-white rounded-lg">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>
                
                <tr class="bg-white hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            3
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 3
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center  text-white rounded-lg">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>
                
                <tr class="bg-blue-50/30 hover:bg-blue-50/60">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            4
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 4
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center  text-white rounded-lg ">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>

                <tr class="bg-white hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            5
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 5
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center  text-white rounded-lg ">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>

                <tr class="bg-blue-50/30 hover:bg-blue-50/60">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            6
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 6
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center  text-white rounded-lg ">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>

                <tr class="bg-white hover:bg-slate-50">
                    <td class="px-6 py-4">
                        <div class="w-8 h-10 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-semibold text-sm">
                            7
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-slate-800">
                        Mapel 7
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">
                        Nama Guru
                    </td>
                    <td class="px-6 py-4">
                        <button class="w-9 h-9 flex items-center justify-center  text-white rounded-lg ">
                            <img src="{{ asset('images/material-symbols_list-alt.png') }}" viewBox="0 0 20 20" fill="currentColor" class="w-14 h-10">
                              <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm0 5.25a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
                            </img>
                        </button>
                    </td>
                </tr>
                
                </tbody>
        </table>
    </div>

@endsection