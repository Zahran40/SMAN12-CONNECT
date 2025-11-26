@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-4 sm:space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>

        <h1 class="text-2xl font-bold text-blue-700">Detail Mata Pelajaran</h1>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-24 h-24 bg-blue-100 rounded-2xl flex items-center justify-center">
                <img src="{{ asset('images/Book.png') }}" alt="Icon Mapel" class="w-16 h-16">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $mapel->nama_mapel }}</h2>
                <p class="text-slate-500 text-sm mb-2">Mata Pelajaran</p>
            </div>
        </div>
        <form action="{{ route('admin.akademik.mapel.destroy', $mapel->id_mapel) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus mata pelajaran ini?')">
            @csrf
            @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg flex items-center justify-center space-x-2 transition-colors font-medium w-full sm:w-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>Hapus</span>
        </button>
        </form>
    </div>

    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Data Mata Pelajaran</h3>
            <a href="{{ route('admin.akademik.mapel.edit', $mapel->id_mapel) }}" class="bg-green-400 hover:bg-green-500 text-white px-4 py-2 sm:py-1.5 rounded-full flex items-center justify-center space-x-2 transition-colors text-sm font-medium w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                </svg>
                <span>Edit</span>
            </a>
        </div>

        <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm">
            <div class="space-y-6 max-w-4xl">
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Nama Mata Pelajaran</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $mapel->nama_mapel }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 pb-3">
                    <span class="font-bold text-slate-800">Kategori</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $mapel->kategori ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Jadwal Per Kelas --}}
    <div class="bg-white p-4 sm:p-6 md:p-8 rounded-2xl shadow-sm">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h3 class="text-xl font-bold text-blue-600">Jadwal Per Kelas</h3>
            <button onclick="document.getElementById('modalTambahJadwal').classList.remove('hidden')" class="bg-blue-400 hover:bg-blue-500 text-white px-4 py-2 rounded-full font-bold flex items-center justify-center space-x-2 shadow-sm transition-colors w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Tambah Jadwal</span>
            </button>
        </div>

        @if($mapel->jadwal->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-blue-200">
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Kelas</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Hari</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Jam</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Guru</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Tahun Ajaran</th>
                        <th class="text-center py-3 px-4 font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mapel->jadwal as $jadwal)
                    <tr class="border-b border-slate-100 hover:bg-blue-50">
                        <td class="py-3 px-4 font-medium">{{ $jadwal->kelas->nama_kelas ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $jadwal->hari }}</td>
                        <td class="py-3 px-4">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td class="py-3 px-4">{{ $jadwal->guru->nama_lengkap ?? '-' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $jadwal->tahunAjaran->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $jadwal->tahunAjaran->tahun_mulai }}/{{ $jadwal->tahunAjaran->tahun_selesai }} {{ $jadwal->tahunAjaran->semester }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center space-x-2">
                            <button onclick="openEditModal({{ $jadwal->id_jadwal }}, '{{ $jadwal->kelas_id }}', '{{ $jadwal->guru_id }}', '{{ $jadwal->hari }}', '{{ $jadwal->jam_mulai }}', '{{ $jadwal->jam_selesai }}', '{{ $jadwal->tahun_ajaran_id }}')" class="text-blue-500 hover:text-blue-700 font-medium">Edit</button>
                            <form action="{{ route('admin.akademik.jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline">
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
        <p class="text-center text-slate-500 py-8">Belum ada jadwal untuk mata pelajaran ini</p>
        @endif
    </div>

</div>

{{-- Modal Tambah Jadwal --}}
<div id="modalTambahJadwal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-4 sm:p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Tambah Jadwal</h3>
            <button onclick="document.getElementById('modalTambahJadwal').classList.add('hidden')" class="text-slate-500 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.akademik.jadwal.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="mapel_id" value="{{ $mapel->id_mapel }}">
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                <select id="tambah_tahun_ajaran_id" name="tahun_ajaran_id" required onchange="filterKelasByTahunAjaran()" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach(\App\Models\TahunAjaran::orderBy('tahun_mulai', 'desc')->get() as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $ta->status == 'Aktif' ? 'selected' : '' }}>
                            {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }} {{ $ta->status == 'Aktif' ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kelas</label>
                <select id="tambah_kelas_id" name="kelas_id" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Tahun Ajaran terlebih dahulu</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id_kelas }}" data-tahun-ajaran="{{ $kelas->tahun_ajaran_id }}" style="display:none;">
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Hari</label>
                <select name="hari" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Hari</option>
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jam Mulai</label>
                    <select name="jam_mulai" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Jam Mulai</option>
                        @for($h = 6; $h <= 20; $h++)
                            @for($m = 0; $m < 60; $m += 30)
                                <option value="{{ sprintf('%02d:%02d', $h, $m) }}">
                                    {{ sprintf('%02d:%02d', $h, $m) }}
                                </option>
                            @endfor
                        @endfor
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Format 24 jam (06:00 - 20:30)</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Jam Selesai</label>
                    <select name="jam_selesai" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Jam Selesai</option>
                        @for($h = 6; $h <= 20; $h++)
                            @for($m = 0; $m < 60; $m += 30)
                                <option value="{{ sprintf('%02d:%02d', $h, $m) }}">
                                    {{ sprintf('%02d:%02d', $h, $m) }}
                                </option>
                            @endfor
                        @endfor
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Format 24 jam (06:00 - 20:30)</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Guru Pengampu</label>
                <select name="guru_id" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Guru</option>
                    @foreach(\App\Models\Guru::all() as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="document.getElementById('modalTambahJadwal').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-bold transition-colors">
                    Batal
                </button>
                <button type="submit" class="bg-green-400 hover:bg-green-500 text-white px-6 py-2 rounded-lg font-bold transition-colors">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Jadwal --}}
<div id="modalEditJadwal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-4 sm:p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-blue-600">Edit Jadwal</h3>
            <button onclick="document.getElementById('modalEditJadwal').classList.add('hidden')" class="text-slate-500 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="formEditJadwal" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="mapel_id" value="{{ $mapel->id_mapel }}">
            
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tahun Ajaran</label>
                <select id="edit_tahun_ajaran_id" name="tahun_ajaran_id" required onchange="filterEditKelasByTahunAjaran()" class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    @foreach(\App\Models\TahunAjaran::orderBy('tahun_mulai', 'desc')->get() as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}">{{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Kelas</label>
                <select id="edit_kelas_id" name="kelas_id" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="">Pilih Tahun Ajaran terlebih dahulu</option>
                    @foreach(\App\Models\Kelas::all() as $kelas)
                        <option value="{{ $kelas->id_kelas }}" data-tahun-ajaran="{{ $kelas->tahun_ajaran_id }}">
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Hari</label>
                <select id="edit_hari" name="hari" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    <option value="Senin">Senin</option>
                    <option value="Selasa">Selasa</option>
                    <option value="Rabu">Rabu</option>
                    <option value="Kamis">Kamis</option>
                    <option value="Jumat">Jumat</option>
                    <option value="Sabtu">Sabtu</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jam Mulai</label>
                    <input type="time" id="edit_jam_mulai" name="jam_mulai" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Jam Selesai</label>
                    <input type="time" id="edit_jam_selesai" name="jam_selesai" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Guru Pengajar</label>
                <select id="edit_guru_id" name="guru_id" required class="w-full border-2 border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-400">
                    @foreach(\App\Models\Guru::all() as $guru)
                        <option value="{{ $guru->id_guru }}">{{ $guru->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" onclick="document.getElementById('modalEditJadwal').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-2 rounded-lg font-bold transition-colors">
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
// Initialize filtering on page load
document.addEventListener('DOMContentLoaded', function() {
    // Trigger filtering untuk Tambah Jadwal form jika tahun ajaran sudah dipilih
    const tambahTaSelect = document.getElementById('tambah_tahun_ajaran_id');
    if (tambahTaSelect.value) {
        filterKelasByTahunAjaran();
    }
});

function openEditModal(id, kelasId, guruId, hari, jamMulai, jamSelesai, tahunAjaranId) {
    document.getElementById('formEditJadwal').action = '/admin/akademik/jadwal/' + id;
    document.getElementById('edit_tahun_ajaran_id').value = tahunAjaranId;
    
    // Filter kelas berdasarkan tahun ajaran yang dipilih
    filterEditKelasByTahunAjaran();
    
    // Set nilai kelas setelah filtering
    document.getElementById('edit_kelas_id').value = kelasId;
    document.getElementById('edit_guru_id').value = guruId;
    document.getElementById('edit_hari').value = hari;
    document.getElementById('edit_jam_mulai').value = jamMulai;
    document.getElementById('edit_jam_selesai').value = jamSelesai;
    
    document.getElementById('modalEditJadwal').classList.remove('hidden');
}

function filterKelasByTahunAjaran() {
    const taId = document.getElementById('tambah_tahun_ajaran_id').value;
    const kelasSelect = document.getElementById('tambah_kelas_id');
    const options = kelasSelect.querySelectorAll('option');
    
    options.forEach(opt => {
        if (opt.value === '') {
            opt.style.display = 'block';
            opt.textContent = taId ? 'Pilih Kelas' : 'Pilih Tahun Ajaran terlebih dahulu';
        } else if (opt.dataset.tahunAjaran == taId) {
            opt.style.display = 'block';
        } else {
            opt.style.display = 'none';
        }
    });
    
    // Reset selection
    kelasSelect.value = '';
}

function filterEditKelasByTahunAjaran() {
    const taId = document.getElementById('edit_tahun_ajaran_id').value;
    const kelasSelect = document.getElementById('edit_kelas_id');
    const options = kelasSelect.querySelectorAll('option');
    
    options.forEach(opt => {
        if (opt.value === '') {
            opt.style.display = 'block';
            opt.textContent = taId ? 'Pilih Kelas' : 'Pilih Tahun Ajaran terlebih dahulu';
        } else if (opt.dataset.tahunAjaran == taId) {
            opt.style.display = 'block';
        } else {
            opt.style.display = 'none';
        }
    });
}
</script>
@endsection

