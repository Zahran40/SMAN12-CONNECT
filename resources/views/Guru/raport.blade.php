@extends('layouts.guru.app')

@section('content')

  <h2 class="text-3xl font-bold text-blue-500 mb-6">Nilai Raport Siswa</h2>

    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-blue-400 mb-2">Nilai Raport Mata Pelajaran Anda</h2>
        <p class="text-sm text-slate-500 mb-8">Pilih Mata Pelajaran anda untuk mengisi nilai pada raport siswa</p>
        
        @if($jadwalList && $jadwalList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($jadwalList as $jadwal)
                    @php
                        // Hitung jumlah siswa
                        $jumlahSiswa = DB::table('siswa')->where('kelas_id', $jadwal->kelas_id)->count();
                    @endphp
                    
                    <div class="bg-white rounded-3xl border-2 border-blue-400 p-6 flex flex-col shadow-sm">
                        <div class="flex justify-center mb-6">
                            <img src="{{ asset('images/Quiz.png') }}" alt="Ikon Nilai Raport" class="w-32 h-32 object-contain">
                        </div>
                        <div class="text-left mb-6">
                            <p class="text-slate-600 mb-1">{{ $jadwal->kelas->nama_kelas }}</p>
                            <h3 class="text-xl font-bold text-blue-600 mb-2">{{ $jadwal->mataPelajaran->nama_mapel }}</h3>
                            <div class="flex items-center text-slate-500 text-sm">
                                <img src="{{ asset('images/Student.png') }}" alt="Student Icon" class="w-5 h-5 mr-2">
                                {{ $jumlahSiswa }} Siswa
                            </div>
                        </div>
                        <a href="{{ route('guru.input_nilai', $jadwal->id_jadwal) }}" class="w-full bg-blue-400 text-white text-center font-bold text-lg py-3 rounded-full hover:bg-blue-500 transition-colors mt-auto">
                            Pergi
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-slate-500">
                <p>Tidak ada mata pelajaran yang diajarkan.</p>
            </div>
        @endif
    </div>

@endsection
