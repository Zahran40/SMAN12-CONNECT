@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Manajemen Data Master</h1>
            <p class="text-slate-500 text-sm">(Manajemen untuk data-data kelas, siswa, guru dan mata pelajaran)</p>
        </div>

        <div class="flex space-x-4">
            <a href="{{ route('admin.data-master.index', ['tab' => 'kelas']) }}" class="px-8 py-2 {{ $tab == 'kelas' ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border-2 border-blue-200' }} font-semibold rounded-full shadow-sm hover:bg-blue-{{ $tab == 'kelas' ? '500' : '50' }} transition-colors">
                Kelas
            </a>
            <a href="{{ route('admin.data-master.index', ['tab' => 'siswa']) }}" class="px-8 py-2 {{ $tab == 'siswa' ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border-2 border-blue-200' }} font-semibold rounded-full hover:bg-blue-{{ $tab == 'siswa' ? '500' : '50' }} transition-colors">
                Siswa
            </a>
            <a href="{{ route('admin.data-master.index', ['tab' => 'guru']) }}" class="px-8 py-2 {{ $tab == 'guru' ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border-2 border-blue-200' }} font-semibold rounded-full hover:bg-blue-{{ $tab == 'guru' ? '500' : '50' }} transition-colors">
                Guru
            </a>
            <a href="{{ route('admin.data-master.index', ['tab' => 'mapel']) }}" class="px-8 py-2 {{ $tab == 'mapel' ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border-2 border-blue-200' }} font-semibold rounded-full hover:bg-blue-{{ $tab == 'mapel' ? '500' : '50' }} transition-colors">
                Mata Pelajaran
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-3xl">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select name="tahun_ajaran" onchange="window.location.href='{{ route('admin.data-master.index') }}?tab={{ $tab }}&tahun_ajaran=' + this.value + '&semester={{ $semester }}'" class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" {{ $tahunAjaranId == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}
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
                <label class="block text-sm font-semibold text-slate-700 mb-2">Semester</label>
                <div class="relative">
                    <select name="semester" onchange="window.location.href='{{ route('admin.data-master.index') }}?tab={{ $tab }}&tahun_ajaran={{ $tahunAjaranId }}&semester=' + this.value" class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="Genap" {{ $semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                        <option value="Ganjil" {{ $semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
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
            @if($tab == 'kelas' && isset($kelasList))
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($kelasList as $kelas)
                <div class="border-2 border-blue-200 rounded-2xl p-6 flex flex-col items-center bg-white">
                    <img src="{{ asset('images/noto_school.png') }}" alt="class" class="w-20 h-20 mb-4">
                    <div class="w-full text-left mb-4">
                        <p class="text-xs text-slate-500 mb-1">{{ $kelas->tahunAjaran->tahun_mulai }}/{{ $kelas->tahunAjaran->tahun_selesai }}</p>
                        <h3 class="text-blue-600 font-bold text-lg">{{ $kelas->nama_kelas }}</h3>
                        <div class="flex items-center text-slate-500 text-sm mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                            </svg>
                            <span>{{ $kelas->siswa_count }} Siswa</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.data-master.kelas.siswa', $kelas->id_kelas) }}" class="w-full bg-blue-400 hover:bg-blue-500 text-white py-2 rounded-full font-semibold transition-colors text-center">
                        Detail
                    </a>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-slate-500">
                    Belum ada data kelas
                </div>
                @endforelse
            </div>
            @endif

            @if($tab == 'siswa' && isset($siswaList))
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-blue-600">Daftar Siswa</h3>
                <a href="{{ route('admin.data-master.siswa.create') }}" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-full font-semibold transition-colors">
                    + Tambah Siswa
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-blue-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">No</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">NIS</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Nama</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Kelas</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($siswaList as $index => $siswa)
                        <tr class="border-b border-slate-100 hover:bg-blue-50">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $siswa->nis }}</td>
                            <td class="py-3 px-4 font-medium">{{ $siswa->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.data-master.siswa.show', $siswa->id_siswa) }}" class="text-blue-500 hover:text-blue-700 font-medium">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500">Belum ada data siswa</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            @if($tab == 'guru' && isset($guruList))
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-blue-600">Daftar Guru</h3>
                <a href="{{ route('admin.data-master.guru.create') }}" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-full font-semibold transition-colors">
                    + Tambah Guru
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-blue-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">No</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">NIP</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Nama</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Mata Pelajaran</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($guruList as $index => $guru)
                        <tr class="border-b border-slate-100 hover:bg-blue-50">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $guru->nip }}</td>
                            <td class="py-3 px-4 font-medium">{{ $guru->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $guru->mataPelajaran->nama_mapel ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.data-master.guru.show', $guru->id_guru) }}" class="text-blue-500 hover:text-blue-700 font-medium">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500">Belum ada data guru</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif

            @if($tab == 'mapel' && isset($mapelList))
            <div class="mb-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-blue-600">Daftar Mata Pelajaran</h3>
                <a href="{{ route('admin.akademik.mapel.create') }}" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-full font-semibold transition-colors">
                    + Tambah Mapel
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-blue-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">No</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Nama Mata Pelajaran</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Jumlah Guru</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mapelList as $index => $mapel)
                        <tr class="border-b border-slate-100 hover:bg-blue-50">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-medium">{{ $mapel->nama_mapel }}</td>
                            <td class="py-3 px-4">{{ $mapel->guru_count }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.akademik.mapel.show', $mapel->id_mapel) }}" class="text-blue-500 hover:text-blue-700 font-medium">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-500">Belum ada data mata pelajaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
@endsection
