@extends('layouts.siswa.app')

@section('title', 'Nilai Raport')

@section('content')

    <div class="flex justify-between items-center mb-4 sm:mb-6">
        <h2 class="text-3xl font-bold text-blue-500">Nilai Raport</h2>
        
        @if(isset($allTahunAjaran) && $allTahunAjaran->count() > 1)
        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-slate-600">Filter:</label>
            <select onchange="window.location.href = this.value" 
                    class="border-2 border-blue-300 rounded-lg px-4 py-2 text-sm font-medium text-slate-700 focus:outline-none focus:border-blue-500">
                <option value="{{ route('siswa.nilai') }}" {{ !$selectedTahunAjaranId ? 'selected' : '' }}>
                    Semua Tahun Ajaran ({{ $allTahunAjaran->count() }})
                </option>
                @foreach($allTahunAjaran as $ta)
                    <option value="{{ route('siswa.nilai', ['tahun_ajaran_id' => $ta->id_tahun_ajaran]) }}" 
                            {{ $selectedTahunAjaranId == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                        {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }}
                        @if($ta->status == 'Aktif')  @endif
                    </option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    @if($tahunAjaranWithKelas->count() > 0)
        <!-- Loop untuk setiap tahun ajaran -->
        @foreach($tahunAjaranWithKelas as $ta)
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6">
            <div class="flex justify-between items-center mb-4 sm:mb-6">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-3 bg-yellow-400 rounded"></span>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">
                            {{ $ta->kelas->nama_kelas ?? 'Kelas Tidak Tersedia' }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Tahun Ajaran {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}
                            @if($ta->status == 'Aktif')
                                <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                            @else
                                <span class="ml-2 px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold">History</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                
                <!-- Semester 1 (Ganjil) -->
                <div style="background-image: url('{{ asset('images/Frame 115 (2).png') }}')" 
                     class="bg-cover bg-center rounded-xl shadow-lg px-6 py-10 flex justify-between items-center">
                    
                    <div class="space-y-4">
                        <h4 class="text-2xl font-bold text-white">Semester 1</h4>
                        <p class="text-sm text-blue-100">Nilai Akhir Semester</p>
                        <a href="{{ route('siswa.detail_raport', ['semester' => 'Ganjil', 'tahun_ajaran_id' => $ta->id_tahun_ajaran]) }}" 
                           class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full text-sm hover:bg-slate-100 transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>

                <!-- Semester 2 (Genap) -->
                <div style="background-image: url('{{ asset('images/Frame 115 (2).png') }}')" 
                     class="bg-cover bg-center rounded-xl shadow-lg p-10 flex justify-between items-center">
                    
                    <div class="space-y-4">
                        <h4 class="text-2xl font-bold text-white">Semester 2</h4>
                        <p class="text-sm text-blue-100">Nilai Akhir Semester</p>
                        <a href="{{ route('siswa.detail_raport', ['semester' => 'Genap', 'tahun_ajaran_id' => $ta->id_tahun_ajaran]) }}" 
                           class="inline-block bg-white text-blue-700 font-semibold px-6 py-2 rounded-full text-sm hover:bg-slate-100 transition-colors">
                            Lihat
                        </a>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    @else
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6">
        <div class="text-center py-12">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-slate-500 font-medium">Belum ada data nilai raport</p>
            <p class="text-slate-400 text-sm mt-2">Data kelas atau nilai belum tersedia. Silakan hubungi admin.</p>
        </div>
    </div>
    @endif

@endsection

