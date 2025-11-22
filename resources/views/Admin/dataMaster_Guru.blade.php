@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-4 sm:space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Manajemen Data Master</h1>
            <p class="text-slate-500 text-sm">(Manajemen untuk data-data kelas, siswa, guru dan mata pelajaran)</p>
        </div>

        <div class="flex space-x-4">
            <a href="{{ route('admin.data-master.index') }}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Kelas
            </a>
            <a href="{{ route('admin.data-master.list-siswa') }}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Siswa
            </a>
            <a href="{{ route('admin.data-master.list-guru') }}" class="px-8 py-2 bg-blue-400 text-white font-semibold rounded-full border-2 hover:bg-blue-500 transition-colors">
                Guru
            </a>
             <a href="{{ route('admin.data-master.list-mapel') }}" class="px-8 py-2 bg-white text-slate-700 font-semibold rounded-full border-2 border-blue-200 hover:bg-blue-50 transition-colors">
                Mata Pelajaran
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select name="tahun_ajaran" onchange="window.location.href='{{ route('admin.data-master.list-guru') }}?tahun_ajaran=' + this.value + '&kelas={{ $kelasId }}'" class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" {{ $tahunAjaranId == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }}
                            </option>
                        @endforeach
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
                    <select name="kelas" onchange="window.location.href='{{ route('admin.data-master.list-guru') }}?tahun_ajaran={{ $tahunAjaranId }}&kelas=' + this.value" class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ $kelasId == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm">
            <h3 class="text-xl font-bold text-blue-600 mb-4 sm:mb-6">Daftar Guru</h3>

            <div class="grid grid-cols-12 gap-4 text-blue-600 font-semibold mb-4 px-6">
                <div class="col-span-1">No</div>
                <div class="col-span-4">Nama</div>
                <div class="col-span-3">NIP</div>
                <div class="col-span-2">Jenis Kelamin</div>
                <div class="col-span-2 text-right pr-12">Aksi</div>
            </div>

            <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                @forelse($guruList as $index => $guru)
                <div class="grid grid-cols-12 gap-4 items-center border-2 border-blue-100 rounded-xl px-6 py-4 bg-white">
                    <div class="col-span-1 font-bold text-slate-700 flex items-center justify-center bg-blue-100 w-8 h-8 rounded-lg">{{ $index + 1 }}</div>
                    <div class="col-span-4 font-semibold text-slate-800">{{ $guru->nama_lengkap }}</div>
                    <div class="col-span-3 text-slate-600 font-medium">{{ $guru->nip }}</div>
                    <div class="col-span-2 text-slate-600">{{ $guru->jenis_kelamin }}</div>
                    <div class="col-span-2 flex flex-col items-end space-y-2">
                        <a href="{{ route('admin.data-master.guru.show', $guru->id_guru) }}" class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-1 rounded-full flex items-center space-x-1 transition-colors text-xs w-20 justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                            <span>Lihat</span>
                        </a>
                        <form action="{{ route('admin.data-master.guru.destroy', $guru->id_guru) }}" method="POST" onsubmit="return confirm('Yakin hapus guru ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-full flex items-center space-x-1 transition-colors text-xs w-20 justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-slate-500">
                    Belum ada data guru
                </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection

