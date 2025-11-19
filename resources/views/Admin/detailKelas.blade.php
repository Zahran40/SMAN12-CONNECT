@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ route('admin.kelas.index', $tahunAjaran->id_tahun_ajaran) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" class="w-8 h-8">
        </a>
        <div>
            <h1 class="text-2xl font-bold text-blue-700">Detail Kelas {{ $kelas->nama_kelas }}</h1>
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

    {{-- Info Kelas --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <h3 class="text-xl font-bold text-blue-600 mb-4">Informasi Kelas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border rounded-lg p-4">
                <p class="text-slate-600 text-sm">Nama Kelas</p>
                <p class="font-bold text-lg text-slate-800">{{ $kelas->nama_kelas }}</p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-slate-600 text-sm">Tingkat</p>
                <p class="font-bold text-lg text-slate-800">Kelas {{ $kelas->tingkat }}</p>
            </div>
            <div class="border rounded-lg p-4">
                <p class="text-slate-600 text-sm">Jurusan</p>
                <p class="font-bold text-lg text-slate-800">{{ $kelas->jurusan ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Wali Kelas --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <h3 class="text-xl font-bold text-blue-600 mb-4">Wali Kelas</h3>
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

    {{-- Daftar Siswa --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Daftar Siswa ({{ $kelas->siswa->count() }})</h3>
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
                    @forelse($kelas->siswa as $index => $siswa)
                    <tr class="border-b border-slate-100 hover:bg-blue-50">
                        <td class="py-3 px-4">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $siswa->nis }}</td>
                        <td class="py-3 px-4 font-medium">{{ $siswa->nama_lengkap }}</td>
                        <td class="py-3 px-4">{{ $siswa->jenis_kelamin }}</td>
                        <td class="py-3 px-4 text-center">
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
</div>

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
@endsection
