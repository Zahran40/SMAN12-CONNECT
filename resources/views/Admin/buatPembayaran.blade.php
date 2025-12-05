@extends('layouts.admin.app')

@section('content')

<div class="flex flex-col space-y-4 sm:space-y-6 max-w-6xl">
    <div>
        <a href="{{ route('admin.pembayaran.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mb-4 inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-blue-700 mt-4">Buat Tagihan SPP </h1>
        <p class="text-slate-500 text-sm"> </p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <p class="font-bold mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
            @if(session('skipped_siswa') && count(session('skipped_siswa')) > 0)
                <div class="mt-3 pt-3 border-t border-red-300">
                    <p class="font-semibold text-sm mb-2">Daftar siswa yang sudah memiliki tagihan:</p>
                    <div class="max-h-40 overflow-y-auto">
                        <ul class="text-sm list-disc list-inside space-y-1 ml-2">
                            @foreach(session('skipped_siswa') as $siswa)
                                <li>{{ $siswa }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <!-- STEP 1: Filter Siswa -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm">
        <h2 class="text-lg font-bold text-slate-700 mb-4">Step 1: Filter Siswa</h2>
        
        <form method="GET" action="{{ route('admin.pembayaran.create') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Tahun Ajaran -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran</label>
                    <select name="tahun_ajaran" id="tahunAjaranSelect" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" 
                                    data-semester="{{ $ta->semester }}"
                                    {{ $tahunAjaranId == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} - {{ $ta->semester }}{{ $ta->status === 'Aktif' ? '  (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                    <select name="bulan" id="bulanSelect" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Pilih Bulan</option>
                        <!-- Ganjil: Juli-Desember (7-12) -->
                        <option value="7" data-semester="Ganjil" {{ $bulan == 7 ? 'selected' : '' }}>Juli</option>
                        <option value="8" data-semester="Ganjil" {{ $bulan == 8 ? 'selected' : '' }}>Agustus</option>
                        <option value="9" data-semester="Ganjil" {{ $bulan == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" data-semester="Ganjil" {{ $bulan == 10 ? 'selected' : '' }}>Oktober</option>
                        <option value="11" data-semester="Ganjil" {{ $bulan == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" data-semester="Ganjil" {{ $bulan == 12 ? 'selected' : '' }}>Desember</option>
                        <!-- Genap: Januari-Juni (1-6) -->
                        <option value="1" data-semester="Genap" {{ $bulan == 1 ? 'selected' : '' }}>Januari</option>
                        <option value="2" data-semester="Genap" {{ $bulan == 2 ? 'selected' : '' }}>Februari</option>
                        <option value="3" data-semester="Genap" {{ $bulan == 3 ? 'selected' : '' }}>Maret</option>
                        <option value="4" data-semester="Genap" {{ $bulan == 4 ? 'selected' : '' }}>April</option>
                        <option value="5" data-semester="Genap" {{ $bulan == 5 ? 'selected' : '' }}>Mei</option>
                        <option value="6" data-semester="Genap" {{ $bulan == 6 ? 'selected' : '' }}>Juni</option>
                    </select>
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                    <input type="number" name="tahun" value="{{ $tahun ?? date('Y') }}" required min="2020" max="2100" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran</label>
                    <select name="status_filter" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        <option value="">Semua</option>
                        <option value="belum" {{ $statusFilter == 'belum' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="sudah" {{ $statusFilter == 'sudah' ? 'selected' : '' }}>Sudah Bayar</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition-colors">
                Filter Siswa
            </button>
        </form>
    </div>

    <!-- STEP 2: Pilih Siswa & Buat Tagihan -->
    @if($bulan && $tahun)
    <form method="POST" action="{{ route('admin.pembayaran.store') }}" class="bg-white p-4 sm:p-6 rounded-xl shadow-sm space-y-6" id="bulkForm">
        @csrf
        
        <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAjaranId }}">
        <input type="hidden" name="bulan" value="{{ $bulan }}">
        <input type="hidden" name="tahun" value="{{ $tahun }}">

        <h2 class="text-lg font-bold text-slate-700">Step 2: Pilih Siswa & Detail Tagihan</h2>

        <!-- Detail Tagihan -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-lg">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Tagihan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_tagihan" value="SPP {{ \Carbon\Carbon::create(null, $bulan)->translatedFormat('F') }} {{ $tahun }}" required class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Bayar <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                    <input type="number" name="jumlah_bayar" value="300000" required min="1" class="w-full border-2 border-blue-200 rounded-lg pl-12 pr-4 py-2 focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi </label>
                <input type="text" name="deskripsi_batch" placeholder="Contoh: Tagihan SPP untuk seluruh siswa kelas X" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>
        </div>

        <!-- Daftar Siswa dengan Checkbox -->
        @if($siswaList->isNotEmpty())
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-700">Daftar Siswa</h3>
                    <p class="text-sm text-slate-500">Total: {{ $siswaList->count() }} siswa • <span id="selectedCount">0</span> dipilih</p>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="selectAll()" class="text-sm bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded-lg transition-colors">Pilih Semua</button>
                    <button type="button" onclick="unselectAll()" class="text-sm bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-1 rounded-lg transition-colors">Hapus Semua</button>
                </div>
            </div>

            <div class="border-2 border-slate-200 rounded-lg max-h-96 overflow-y-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase w-12">
                                <input type="checkbox" id="checkAll" onchange="toggleAll(this)" class="w-4 h-4 text-blue-600">
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">NISN</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Nama Siswa</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Kelas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($siswaList as $siswa)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-4 py-3">
                                <input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id_siswa }}" class="siswa-checkbox w-4 h-4 text-blue-600" onchange="updateCount()">
                            </td>
                            <td class="px-4 py-3 text-sm font-mono">{{ $siswa->nisn }}</td>
                            <td class="px-4 py-3 text-sm font-semibold">{{ $siswa->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-sm">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4 border-t-2 border-slate-200">
            <div class="text-sm text-slate-600">
                <span class="font-bold" id="selectedCountBottom">0</span> siswa dipilih • 
                Total: Rp <span id="totalNominal">0</span>
            </div>
            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg transition-colors flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Buat Tagihan</span>
                </button>
                <a href="{{ route('admin.pembayaran.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-8 py-3 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </div>

        @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mt-0.5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm text-yellow-700 font-semibold">Tidak ada siswa ditemukan</p>
                    <p class="text-sm text-yellow-600 mt-1">Silakan ubah filter di atas untuk menampilkan daftar siswa.</p>
                </div>
            </div>
        </div>
        @endif
    </form>
    @else
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
        <div class="flex items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mt-0.5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <div>
                <p class="text-sm text-blue-700 font-semibold">Panduan Penggunaan:</p>
                <ul class="text-sm text-blue-600 list-disc list-inside space-y-1 mt-2">
                    <li>Pilih bulan dan tahun terlebih dahulu di Step 1</li>
                    <li>Gunakan filter "Status Pembayaran" untuk melihat siswa yang belum/sudah bayar</li>
                    <li>Setelah filter diterapkan, pilih siswa yang akan dibuatkan tagihan</li>
                    <li>Sistem akan membuat batch record untuk audit trail</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.siswa-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateCount();
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.siswa-checkbox');
    checkboxes.forEach(cb => cb.checked = true);
    document.getElementById('checkAll').checked = true;
    updateCount();
}

function unselectAll() {
    const checkboxes = document.querySelectorAll('.siswa-checkbox');
    checkboxes.forEach(cb => cb.checked = false);
    document.getElementById('checkAll').checked = false;
    updateCount();
}

function updateCount() {
    const checked = document.querySelectorAll('.siswa-checkbox:checked').length;
    const jumlahBayar = parseInt(document.querySelector('input[name="jumlah_bayar"]').value) || 0;
    const total = checked * jumlahBayar;
    
    document.getElementById('selectedCount').textContent = checked;
    document.getElementById('selectedCountBottom').textContent = checked;
    document.getElementById('totalNominal').textContent = total.toLocaleString('id-ID');
    
    // Update checkAll status
    const allCheckboxes = document.querySelectorAll('.siswa-checkbox');
    document.getElementById('checkAll').checked = allCheckboxes.length > 0 && checked === allCheckboxes.length;
}

// Update total when jumlah_bayar changes
document.addEventListener('DOMContentLoaded', function() {
    const jumlahBayarInput = document.querySelector('input[name="jumlah_bayar"]');
    if (jumlahBayarInput) {
        jumlahBayarInput.addEventListener('input', updateCount);
    }
    
    // Filter bulan berdasarkan semester
    const tahunAjaranSelect = document.getElementById('tahunAjaranSelect');
    const bulanSelect = document.getElementById('bulanSelect');
    
    function filterBulan() {
        const selectedOption = tahunAjaranSelect.options[tahunAjaranSelect.selectedIndex];
        const semester = selectedOption.getAttribute('data-semester');
        
        // Show/hide options based on semester
        Array.from(bulanSelect.options).forEach(option => {
            if (option.value === '') {
                option.style.display = 'block'; // Always show "Pilih Bulan"
                return;
            }
            
            const bulanSemester = option.getAttribute('data-semester');
            if (bulanSemester === semester) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                // Reset selection if currently selected month doesn't match
                if (option.selected) {
                    bulanSelect.value = '';
                }
            }
        });
    }
    
    // Run filter on page load
    if (tahunAjaranSelect && bulanSelect) {
        filterBulan();
        tahunAjaranSelect.addEventListener('change', filterBulan);
    }
});
</script>

@endsection


