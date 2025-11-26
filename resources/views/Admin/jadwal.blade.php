@extends('layouts.admin.app')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="content-wrapper p-6">

    <div class="bg-white rounded-xl shadow-md p-5 flex items-center gap-4 mb-6">
        <img src="{{ asset('images/clarity_administrator-solid.png') }}" alt="Operator Icon" class="w-16 h-16">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Operator</h2>
        </div>
    </div>

    <div class="flex justify-between items-center mb-5">
        <h1 class="text-xl font-bold text-blue-700">Jadwal Pelajaran</h1>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.akademik.jadwal.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Tahun Ajaran -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                <select name="tahun_ajaran" onchange="this.form.submit()" class="w-full border-2 border-blue-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $tahunAjaranId == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                            {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kelas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="kelas" onchange="this.form.submit()" class="w-full border-2 border-blue-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500" {{ !$tahunAjaranId ? 'disabled' : '' }}>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ $kelasId == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Button Reset -->
            <div class="flex items-end">
                <a href="{{ route('admin.akademik.jadwal.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    @if($kelasId && $jadwalList->count() > 0)
        <!-- Jadwal Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-blue-400">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Hari</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Jam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Guru</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $hariOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $jadwalGrouped = $jadwalList->groupBy('hari');
                        @endphp
                        @foreach($hariOrder as $hari)
                            @if(isset($jadwalGrouped[$hari]))
                                @foreach($jadwalGrouped[$hari] as $jadwal)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $jadwal->hari }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal->guru->nama_lengkap }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $jadwal->kelas->nama_kelas }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($kelasId && $jadwalList->count() == 0)
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500 text-lg">Belum ada jadwal untuk kelas ini</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-md p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-gray-500 text-lg">Pilih Tahun Ajaran dan Kelas untuk melihat jadwal</p>
        </div>
    @endif

</div>
@endsection
