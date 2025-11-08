@extends('layouts.guru.app')

@section('content')

    <h2 class="text-3xl font-bold text-slate-800 mb-6">Nama Mata Pelajaran</h2>

    <!-- Presensi List Section -->
    <section class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-slate-800">Pertemuan</th>
                    <th class="px-4 py-2 text-left font-semibold text-slate-800">Materi</th>
                    <th class="px-4 py-2 text-left font-semibold text-slate-800">Waktu Absensi</th>
                    <th class="px-4 py-2 text-left font-semibold text-slate-800">Detail</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Row 1 -->
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-2 text-slate-700">1</td>
                    <td class="px-4 py-2 text-slate-700">Nama Materi</td>
                    <td class="px-4 py-2 text-slate-500">08:00 - 09:30</td>
                    <td class="px-4 py-2 text-blue-500 text-sm">
                        <a href="#" class="inline-flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Detail</span>
                        </a>
                    </td>
                </tr>
                <!-- Example Row 2 -->
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-2 text-slate-700">2</td>
                    <td class="px-4 py-2 text-slate-700">Nama Materi</td>
                    <td class="px-4 py-2 text-slate-500">08:00 - 09:30</td>
                    <td class="px-4 py-2 text-blue-500 text-sm">
                        <a href="#" class="inline-flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Detail</span>
                        </a>
                    </td>
                </tr>
                <!-- Example Row 3 -->
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-2 text-slate-700">3</td>
                    <td class="px-4 py-2 text-slate-700">Nama Materi</td>
                    <td class="px-4 py-2 text-slate-500">08:00 - 09:30</td>
                    <td class="px-4 py-2 text-blue-500 text-sm">
                        <a href="#" class="inline-flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Detail</span>
                        </a>
                    </td>
                </tr>
                <!-- Example Row 4 -->
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-2 text-slate-700">4</td>
                    <td class="px-4 py-2 text-slate-700">Nama Materi</td>
                    <td class="px-4 py-2 text-slate-500">08:00 - 09:30</td>
                    <td class="px-4 py-2 text-blue-500 text-sm">
                        <a href="#" class="inline-flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Detail</span>
                        </a>
                    </td>
                </tr>
                <!-- Example Row 5 -->
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-2 text-slate-700">5</td>
                    <td class="px-4 py-2 text-slate-700">Nama Materi</td>
                    <td class="px-4 py-2 text-slate-500">08:00 - 09:30</td>
                    <td class="px-4 py-2 text-blue-500 text-sm">
                        <a href="#" class="inline-flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Detail</span>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Buat Absensi Button -->
    <div class="text-right">
        <a href="#" class="bg-blue-400 text-white text-sm font-medium px-5 py-2 rounded-full hover:bg-blue-500 transition-colors inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Buat Absensi
        </a>
    </div>

@endsection
