@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-4 sm:space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Detail Siswa</h1>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm flex justify-between items-center">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-24 h-24 bg-blue-100 rounded-2xl flex items-center justify-center">
                <img src="{{ asset('images/Frame 50.png') }}" alt="Icon Tahun Ajaran" class="w-20 h-20">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-slate-500 text-sm mb-2">NIS {{ $siswa->nis }}</p>
                <span class="border border-yellow-400 text-yellow-600 text-xs font-semibold px-4 py-1 rounded-full">
                    {{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}
                </span>
            </div>
        </div>
        <form action="{{ route('admin.data-master.siswa.destroy', $siswa->id_siswa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
            @csrf
            @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-lg flex items-center space-x-2 transition-colors font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>Hapus</span>
        </button>
        </form>
    </div>

    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Data Diri Siswa</h3>
            <a href="{{ route('admin.data-master.siswa.edit', $siswa->id_siswa) }}" class="bg-green-400 hover:bg-green-500 text-white px-4 py-1.5 rounded-full flex items-center space-x-2 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                <span>Edit</span>
            </a>
        </div>

        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm">
            <div class="space-y-6 max-w-4xl">
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Nama</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->nama_lengkap }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Tanggal lahir</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Tempat lahir</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->tempat_lahir }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Alamat</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->alamat }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Jenis Kelamin</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">NIS</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->nis }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">NISN</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->nisn }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">No handphone</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->no_telepon ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Email</span>
                    <a href="mailto:{{ $siswa->user->email ?? $siswa->email }}" class="col-span-2 text-slate-600 font-medium underline decoration-slate-400">{{ $siswa->user->email ?? $siswa->email ?? '-' }}</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Agama</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->agama }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 pb-3">
                    <span class="font-bold text-slate-800">Kelas Saat Ini</span>
                    <span class="col-span-2 text-slate-600 font-medium">
                        @php
                            $kelasAktif = $siswa->siswaKelas->where('status', 'Aktif')->first();
                        @endphp
                        {{ $kelasAktif ? $kelasAktif->kelas->nama_kelas . ' (' . $kelasAktif->tahunAjaran->tahun_mulai . '/' . $kelasAktif->tahunAjaran->tahun_selesai . ' ' . $kelasAktif->tahunAjaran->semester . ')' : 'Belum ada kelas aktif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Kelas Siswa --}}
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Riwayat Kelas Per Tahun Ajaran</h3>
            <button onclick="document.getElementById('modalTambahKelas').classList.remove('hidden')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors text-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tambah Kelas</span>
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            @if($siswa->siswaKelas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Tahun Ajaran</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Kelas</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Status</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Tanggal Masuk</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswa->siswaKelas as $sk)
                        <tr class="border-b border-slate-100 hover:bg-blue-50">
                            <td class="py-3 px-4">
                                <span class="font-medium {{ $sk->tahunAjaran->status == 'Aktif' ? 'text-green-600' : 'text-slate-600' }}">
                                    {{ $sk->tahunAjaran->tahun_mulai }}/{{ $sk->tahunAjaran->tahun_selesai }} {{ $sk->tahunAjaran->semester }}
                                    @if($sk->tahunAjaran->status == 'Aktif')
                                        <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Aktif</span>
                                    @endif
                                </span>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ $sk->kelas->nama_kelas }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $sk->status == 'Aktif' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $sk->status == 'Pindah' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $sk->status == 'Lulus' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $sk->status == 'Keluar' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ $sk->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600">{{ $sk->tanggal_masuk ? \Carbon\Carbon::parse($sk->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                <button onclick="openEditKelasModal({{ $sk->id }}, {{ $sk->kelas_id }}, '{{ $sk->status }}')" class="text-blue-500 hover:text-blue-700 font-medium mr-2">Edit</button>
                                <form action="{{ route('admin.data-master.siswa-kelas.destroy', $sk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data kelas ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-center text-slate-500 py-8">Belum ada riwayat kelas untuk siswa ini</p>
            @endif
        </div>
    </div>

</div>

{{-- Modal Tambah Kelas --}}
<div id="modalTambahKelas" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Tambah Kelas Baru</h3>
            <button onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="text-slate-500 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.data-master.siswa.assign-kelas', $siswa->id_siswa) }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $ta->status == 'Aktif' ? 'selected' : '' }}>
                            {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} {{ $ta->semester }} {{ $ta->status == 'Aktif' ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Kelas</label>
                <select name="kelas_id" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select name="status" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="Aktif">Aktif</option>
                    <option value="Pindah">Pindah</option>
                    <option value="Lulus">Lulus</option>
                    <option value="Keluar">Keluar</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="document.getElementById('modalTambahKelas').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Kelas --}}
<div id="modalEditKelas" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Edit Data Kelas</h3>
            <button onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="text-slate-500 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formEditKelas" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Kelas</label>
                <select id="edit_kelas_id" name="kelas_id" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status</label>
                <select id="edit_status" name="status" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="Aktif">Aktif</option>
                    <option value="Pindah">Pindah</option>
                    <option value="Lulus">Lulus</option>
                    <option value="Keluar">Keluar</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="document.getElementById('modalEditKelas').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditKelasModal(id, kelasId, status) {
    document.getElementById('formEditKelas').action = '/admin/data-master/siswa-kelas/' + id;
    document.getElementById('edit_kelas_id').value = kelasId;
    document.getElementById('edit_status').value = status;
    document.getElementById('modalEditKelas').classList.remove('hidden');
}
</script>
@endsection


