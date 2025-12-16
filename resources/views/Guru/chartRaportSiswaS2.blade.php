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
            
            <!-- Lock Status Alert -->
            @if($raport && $raport->is_locked)
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-red-800">🔒 Nilai sudah di-LOCK PERMANEN dan tidak dapat diubah lagi.</span>
                    </div>
                </div>
            @endif
            
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
                           class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 text-center font-semibold focus:border-blue-500 focus:outline-none {{ $raport && $raport->is_locked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                           placeholder="1-100" {{ $raport && $raport->is_locked ? 'disabled' : '' }}>
                </div>

                <div class="flex justify-between items-center">
                    <h4 class="text-base font-medium text-slate-800">Nilai Ujian Akhir Semester</h4>
                    <input type="number" min="1" max="100" step="1" inputmode="numeric" pattern="[0-9]*" name="nilai_uas" value="{{ $raport->nilai_uas ?? '' }}"
                           class="w-32 border-2 border-slate-300 rounded-lg px-4 py-2 text-center font-semibold focus:border-blue-500 focus:outline-none {{ $raport && $raport->is_locked ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                           placeholder="1-100" {{ $raport && $raport->is_locked ? 'disabled' : '' }}>
                </div>

                <div class="flex justify-between items-center">
                    <h4 class="text-base font-medium text-slate-800">Deskripsi/Catatan</h4>
                    <textarea name="deskripsi" rows="2" maxlength="250" 
                              class="w-2/3 border-2 border-slate-300 rounded-lg px-4 py-2 focus:border-blue-500 focus:outline-none {{ $raport && $raport->is_locked ? 'bg-gray-100 cursor-not-allowed' : '' }}" 
                              placeholder="Catatan untuk siswa... (maks 250 karakter)" {{ $raport && $raport->is_locked ? 'disabled' : '' }}>{{ $raport->deskripsi ?? '' }}</textarea>
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

                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    @if($raport && $raport->is_locked)
                        <!-- Nilai sudah di-lock permanen -->
                        <div class="px-6 sm:px-8 py-2.5 sm:py-3 bg-red-100 text-red-700 font-bold rounded-lg shadow-md w-full sm:w-auto flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                            </svg>
                            Nilai Terkunci Permanen
                        </div>
                    @else
                        <!-- Tombol Simpan -->
                        <button type="submit" class="px-6 sm:px-8 py-2.5 sm:py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-lg shadow-md transition-colors w-full sm:w-auto">
                            Simpan Nilai
                        </button>
                        <!-- Tombol Lock (hanya muncul jika nilai sudah ada) -->
                        @if($raport && ($raport->nilai_uts || $raport->nilai_uas))
                        <button type="button" onclick="showLockModalS2()" class="px-6 sm:px-8 py-2.5 sm:py-3 bg-blue-500 hover:bg-blue-600 text-white font-bold rounded-lg shadow-md transition-colors w-full sm:w-auto flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                            </svg>
                            Lock Nilai (Permanen)
                        </button>
                        @endif
                    @endif
                </div>

            </div>
        </form>

        <!-- Form Lock (hidden) -->
        @if($raport && !$raport->is_locked && ($raport->nilai_uts || $raport->nilai_uas))
        <form id="lockFormS2" action="{{ route('guru.lock_nilai', [$jadwal->id_jadwal, $siswa->id_siswa]) }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="semester" value="Genap">
        </form>
        @endif

    </div>

    <!-- Modal Konfirmasi Lock Nilai -->
    @if($raport && !$raport->is_locked && ($raport->nilai_uts || $raport->nilai_uas))
    <div id="lock-modal-s2" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-6 md:p-8 max-w-md w-full mx-4">
            <div class="text-center mb-4 sm:mb-6">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">⚠️ PERHATIAN!</h3>
                <p class="text-sm text-slate-600">Setelah di-lock, nilai TIDAK DAPAT diubah lagi</p>
            </div>

            <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-4 sm:mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-red-800 mb-1">Nilai akan dikunci PERMANEN</p>
                        <p class="text-xs text-red-700">Nilai tidak dapat diubah lagi. Pastikan semua nilai sudah benar sebelum melanjutkan.</p>
                    </div>
                </div>
            </div>

            <p class="text-center text-sm text-slate-700 mb-6">Apakah Anda yakin ingin mengunci nilai ini?</p>

            <div class="flex gap-3">
                <button onclick="closeLockModalS2()" class="flex-1 bg-slate-200 text-slate-700 font-semibold py-2.5 rounded-lg hover:bg-slate-300 transition-colors">
                    Batal
                </button>
                <button onclick="confirmLockS2()" class="flex-1 bg-red-500 text-white font-semibold py-2.5 rounded-lg hover:bg-red-600 transition-colors">
                    Ya, Lock Nilai
                </button>
            </div>
        </div>
    </div>
    @endif

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

    // Modal Lock Nilai Functions
    function showLockModalS2() {
        document.getElementById('lock-modal-s2').classList.remove('hidden');
    }

    function closeLockModalS2() {
        document.getElementById('lock-modal-s2').classList.add('hidden');
    }

    function confirmLockS2() {
        document.getElementById('lockFormS2').submit();
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('lock-modal-s2');
        if (event.target === modal) {
            closeLockModalS2();
        }
    });
</script>
@endpush

