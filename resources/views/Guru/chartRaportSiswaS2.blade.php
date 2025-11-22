@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-4 sm:mb-6">
        <a href="{{ route('guru.input_nilai', $jadwal->id_jadwal) }}" class="text-blue-400 hover:text-blue-600 p-1 rounded-full hover:bg-blue-50">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Detail Raport - {{ $jadwal->mataPelajaran->nama_mapel }} (Semester 2)</h2>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 flex items-center space-x-4">
           <div class="rounded-full overflow-hidden w-16 h-16 ring-4 ring-blue-100">
             <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">{{ $siswa->nama_lengkap }}</h3>
            <p class="text-sm text-slate-500">NIS: {{ $siswa->nis ?? '-' }}</p>
            <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">{{ $jadwal->kelas->nama_kelas }}</span>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8 border-2 border-blue-100">
        <h3 class="text-xl font-semibold text-blue-600 mb-6 sm:mb-8 text-center">Perkembangan Siswa - Semester 2</h3>
        
        {{-- Chart.js Canvas --}}
        <div class="px-6" style="max-width: 800px; margin: 0 auto;">
            <canvas id="nilaiChart" height="300"></canvas>
        </div>

        <div class="flex justify-center space-x-8 mt-4 sm:mt-6">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded-full bg-purple-600"></div>
                <span class="text-sm text-slate-600 font-medium">Tahun Ajaran {{ $tahunAjaranLabel }}</span>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
        <h3 class="text-xl font-semibold text-slate-800 mb-4 sm:mb-6">Mengisi Nilai - {{ $tahunAjaranLabel }}</h3>
        
        <div class="flex space-x-4 mb-6 sm:mb-8">
            <a href="{{ route('guru.detail_raport_siswa', [$jadwal->id_jadwal, $siswa->id_siswa]) }}" class="px-8 py-2.5 rounded-full bg-white text-slate-600 border-2 border-slate-200 font-semibold text-sm hover:bg-slate-50 transition-colors">Semester 1 (Ganjil)</a>
            <button class="px-8 py-2.5 rounded-full bg-blue-500 text-white font-semibold text-sm shadow-md">Semester 2 (Genap)</button>
        </div>

        <form action="{{ route('guru.simpan_nilai', [$jadwal->id_jadwal, $siswa->id_siswa]) }}" method="POST">
            @csrf
            <input type="hidden" name="semester" value="Genap">
            
            <div class="space-y-6">
                
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-base font-medium text-slate-800">Nilai Tugas</h4>
                        <p class="text-xs text-blue-600 mt-1">✓ Dihitung otomatis dari rata-rata semua tugas siswa</p>
                    </div>
                    <div class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 text-center font-semibold text-slate-400">
                        {{ $averageTugas ? number_format($averageTugas, 2) : '0.00' }}
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <h4 class="text-base font-medium text-slate-800">Nilai Ujian Tengah Semester</h4>
                    <input type="number" min="1" max="100" step="1" inputmode="numeric" pattern="[0-9]*" name="nilai_uts" value="{{ $raport->nilai_uts ?? '' }}"
                           class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 text-center font-semibold focus:border-blue-500 focus:outline-none"
                           placeholder="1-100">
                </div>

                <div class="flex justify-between items-center">
                    <h4 class="text-base font-medium text-slate-800">Nilai Ujian Akhir Semester</h4>
                    <input type="number" min="1" max="100" step="1" inputmode="numeric" pattern="[0-9]*" name="nilai_uas" value="{{ $raport->nilai_uas ?? '' }}"
                           class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 text-center font-semibold focus:border-blue-500 focus:outline-none"
                           placeholder="1-100">
                </div>

                <div class="flex justify-between items-center">
                    <h4 class="text-base font-medium text-slate-800">Deskripsi/Catatan</h4>
                    <textarea name="deskripsi" rows="2" maxlength="250" class="w-2/3 border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none" placeholder="Catatan untuk siswa... (maks 250 karakter)">{{ $raport->deskripsi ?? '' }}</textarea>
                </div>

                <div class="pt-6 mt-10 border-t-[3px] border-black"> 
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-bold text-slate-900">Nilai Akhir</h4>
                        <span class="text-3xl font-bold text-slate-900">{{ $raport && $raport->nilai_akhir ? number_format($raport->nilai_akhir, 2) : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <h4 class="text-lg font-semibold text-slate-700">Grade (Nilai Huruf)</h4>
                        <span class="text-2xl font-bold text-blue-600">{{ $raport->nilai_huruf ?? $raport->grade ?? '-' }}</span>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg shadow-md transition-colors">
                        Simpan Nilai
                    </button>
                </div>

            </div>
        </form>

    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('nilaiChart').getContext('2d');
        
        const nilaiTugas = {{ $averageTugas ?? 0 }};
        const nilaiUTS = {{ $raport->nilai_uts ?? 0 }};
        const nilaiUAS = {{ $raport->nilai_uas ?? 0 }};
        const nilaiAkhir = {{ $raport->nilai_akhir ?? 0 }};
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Tugas', 'UTS', 'UAS', 'Nilai Akhir'],
                datasets: [{
                    label: 'Nilai',
                    data: [nilaiTugas, nilaiUTS, nilaiUAS, nilaiAkhir],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.8)',  // blue-600 untuk semua
                        'rgba(37, 99, 235, 0.8)',
                        'rgba(37, 99, 235, 0.8)',
                        'rgba(147, 51, 234, 0.8)'   // purple-600 untuk Nilai Akhir
                    ],
                    borderColor: [
                        'rgb(37, 99, 235)',
                        'rgb(37, 99, 235)',
                        'rgb(37, 99, 235)',
                        'rgb(147, 51, 234)'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return 'Nilai: ' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

