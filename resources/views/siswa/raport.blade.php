@extends('layouts.siswa.app')

@section('title', 'Nilai Raport')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-6">Nilai Raport</h2>

    <div class="space-y-8">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-3 bg-yellow-400 rounded"></span>
                    <h3 class="text-lg font-bold text-slate-800">Tahun Ajaran {{ $tahunAjaranLabel }}</h3>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-slate-600">Rata-rata: <span class="font-bold text-blue-600">{{ number_format($rataRata, 2) }}</span></span>
                </div>
            </div>

            @if($raports->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-blue-700 uppercase tracking-wider">Tugas</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-blue-700 uppercase tracking-wider">UTS</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-blue-700 uppercase tracking-wider">UAS</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-blue-700 uppercase tracking-wider">Nilai Akhir</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-blue-700 uppercase tracking-wider">Grade</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($raports as $index => $raport)
                                @php
                                    // Hitung grade dari nilai akhir
                                    $nilai = $raport->nilai_akhir;
                                    if ($nilai >= 90) $grade = 'A';
                                    elseif ($nilai >= 80) $grade = 'B';
                                    elseif ($nilai >= 70) $grade = 'C';
                                    elseif ($nilai >= 60) $grade = 'D';
                                    else $grade = 'E';
                                @endphp
                                <tr class="hover:bg-blue-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $raport->nama_mapel }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-700">{{ $raport->nilai_tugas ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-700">{{ $raport->nilai_uts ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-slate-700">{{ $raport->nilai_uas ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-blue-600">{{ $raport->nilai_akhir ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($grade == 'A') bg-green-100 text-green-800
                                            @elseif($grade == 'B') bg-blue-100 text-blue-800
                                            @elseif($grade == 'C') bg-yellow-100 text-yellow-800
                                            @elseif($grade == 'D') bg-orange-100 text-orange-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ $grade }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-slate-500">Belum ada nilai untuk tahun ajaran ini</p>
                </div>
            @endif
        </div>
    </div>

@endsection
