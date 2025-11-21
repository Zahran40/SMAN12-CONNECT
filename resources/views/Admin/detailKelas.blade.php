@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.kelas.index', $tahunAjaran->id_tahun_ajaran) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Detail Kelas</h1>
            <p class="text-slate-500 text-sm">Tahun Ajaran {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Info Kelas dengan Logo --}}
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-2xl shadow-sm border border-blue-200">
        <div class="flex items-center space-x-6">
            {{-- Logo Kelas --}}
            <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <img src="{{ asset('images/school.png') }}" alt="Logo Kelas" class="w-16 h-16">
            </div>
            
            {{-- Info Kelas --}}
            <div class="flex-1">
                <h2 class="text-3xl font-bold text-blue-800 mb-2">{{ $kelas->nama_kelas }}</h2>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-lg font-semibold">
                        {{ $kelas->siswaAktif->count() }} Siswa
                    </span>
                    <span class="text-blue-700 font-medium">
                        Tingkat {{ $kelas->tingkat }} - {{ $kelas->jurusan ?? 'Umum' }}
                    </span>
                    <span class="px-3 py-1 rounded-lg text-xs font-semibold {{ $tahunAjaran->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $tahunAjaran->tahun_mulai }}/{{ $tahunAjaran->tahun_selesai }}
                    </span>
                </div>
                <div class="mt-3 flex items-center text-sm text-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">Wali Kelas:</span>
                    <span class="ml-2 text-blue-800 font-semibold">{{ $kelas->waliKelas->nama_lengkap ?? 'Belum ditentukan' }}</span>
                </div>
            </div>

            {{-- Tombol Hapus Kelas --}}
            <div>
                <form action="{{ route('admin.kelas.destroy', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini? Semua data terkait akan dihapus!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tabs Navigation --}}
    <div class="bg-white rounded-2xl shadow-sm">
        <div class="flex border-b border-slate-200">
            <button onclick="showTab('siswa')" id="tab-siswa" class="flex-1 px-6 py-4 font-semibold text-blue-600 border-b-2 border-blue-600 transition-colors">
                Siswa
            </button>
            <button onclick="showTab('guru')" id="tab-guru" class="flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                Guru
            </button>
            <button onclick="showTab('mapel')" id="tab-mapel" class="flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                Mata Pelajaran
            </button>
        </div>

        {{-- Tab Content Siswa --}}
        <div id="content-siswa" class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-blue-600">Daftar Siswa ({{ $kelas->siswaAktif->count() }})</h3>
                <button onclick="document.getElementById('modalTambahSiswa').classList.remove('hidden')" class="bg-green-400 hover:bg-green-500 text-white px-4 py-2 rounded-full font-bold flex items-center space-x-2 shadow-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Tambah Siswa</span>
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-blue-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">No</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">NIS</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Nama Lengkap</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Jenis Kelamin</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kelas->siswaAktif as $index => $siswa)
                        <tr class="border-b border-slate-100 hover:bg-blue-50">
                            <td class="py-3 px-4">{{ $index + 1 }}</td>
                            <td class="py-3 px-4">{{ $siswa->nis }}</td>
                            <td class="py-3 px-4 font-medium">{{ $siswa->nama_lengkap }}</td>
                            <td class="py-3 px-4">{{ $siswa->jenis_kelamin }}</td>
                            <td class="py-3 px-4 text-center space-x-2">
                                <a href="{{ route('admin.data-master.siswa.show', $siswa->id_siswa) }}" class="text-blue-500 hover:text-blue-700 font-medium">Detail</a>
                                <form action="{{ route('admin.kelas.remove-siswa', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas, $siswa->id_siswa]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa dari kelas ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-slate-500">Belum ada siswa di kelas ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab Content Guru --}}
        <div id="content-guru" class="p-6 hidden">
            <h3 class="text-xl font-bold text-blue-600 mb-4">Update Wali Kelas</h3>
            <form action="{{ route('admin.kelas.update-wali', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas]) }}" method="POST" class="flex items-end gap-4">
                @csrf
                @method('PUT')
                <div class="flex-1">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Wali Kelas</label>
                    <select name="wali_kelas_id" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                        <option value="">Tidak Ada Wali Kelas</option>
                        @foreach($guruList as $guru)
                            <option value="{{ $guru->id_guru }}" {{ $kelas->wali_kelas_id == $guru->id_guru ? 'selected' : '' }}>
                                {{ $guru->nama_lengkap }} ({{ $guru->nip }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-2.5 rounded-lg font-bold transition-colors">
                    Update
                </button>
            </form>
        </div>

        {{-- Tab Content Mata Pelajaran --}}
        <div id="content-mapel" class="p-6 hidden">
            <div class="mb-4">
                <h3 class="text-xl font-bold text-blue-600">Mata Pelajaran Kelas ({{ $jadwalMapel->count() }})</h3>
                <p class="text-sm text-slate-500 mt-1">Jadwal mata pelajaran dikelola di <a href="{{ route('admin.akademik.jadwal.index') }}" class="text-blue-600 font-semibold hover:underline">Manajemen Akademik</a></p>
            </div>

            @if($jadwalMapel->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($jadwalMapel as $jadwal)
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-bold text-blue-800 text-lg">{{ $jadwal->nama_mapel }}</h4>
                                <p class="text-sm text-blue-600">{{ $jadwal->kode_mapel }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="bg-blue-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                    {{ $jadwal->hari }}
                                </span>
                                <form action="{{ route('admin.kelas.remove-mapel', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas, $jadwal->id_jadwal]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran dari kelas ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 text-sm">
                            <div class="flex items-center text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                            </div>
                            <div class="flex items-center text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                {{ $jadwal->nama_guru }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h4 class="text-lg font-semibold text-slate-600 mb-2">Belum Ada Jadwal Mata Pelajaran</h4>
                    <p class="text-slate-500">Jadwal mata pelajaran untuk kelas ini belum diatur</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Semua modal dan fitur tambah mata pelajaran dihapus --}}
{{-- Jadwal mata pelajaran dikelola di Manajemen Akademik --}}

{{-- Modal Tambah Siswa --}}
<div id="modalTambahSiswa" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Tambah Siswa ke Kelas</h3>
            <button onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')" class="text-slate-500 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.kelas.add-siswa', [$tahunAjaran->id_tahun_ajaran, $kelas->id_kelas]) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Siswa</option>
                    @foreach($siswaAvailable as $siswa)
                        <option value="{{ $siswa->id_siswa }}">
                            {{ $siswa->nama_lengkap }} ({{ $siswa->nis }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="document.getElementById('modalTambahSiswa').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                    Tambah
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.getElementById('content-siswa').classList.add('hidden');
        document.getElementById('content-guru').classList.add('hidden');
        document.getElementById('content-mapel').classList.add('hidden');
        
        // Reset all tab buttons
        document.getElementById('tab-siswa').classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        document.getElementById('tab-siswa').classList.add('text-slate-500');
        document.getElementById('tab-guru').classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        document.getElementById('tab-guru').classList.add('text-slate-500');
        document.getElementById('tab-mapel').classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
        document.getElementById('tab-mapel').classList.add('text-slate-500');
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Highlight selected tab button
        document.getElementById('tab-' + tabName).classList.remove('text-slate-500');
        document.getElementById('tab-' + tabName).classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
    }
</script>

@endsection
