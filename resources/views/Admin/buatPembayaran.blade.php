@extends('layouts.admin.app')

@section('content')

<div class="flex flex-col space-y-4 sm:space-y-6 max-w-3xl">
    <div>
        <a href="{{ route('admin.pembayaran.index') }}" class="text-blue-600 hover:text-blue-800 font-medium mb-4 inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-blue-700 mt-4">Buat Tagihan SPP</h1>
        <p class="text-slate-500 text-sm">Buat tagihan untuk siswa secara massal</p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.pembayaran.store') }}" class="bg-white p-4 sm:p-6 rounded-xl shadow-sm space-y-4 sm:space-y-6">
        @csrf

        <!-- Tahun Ajaran -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun Ajaran <span class="text-red-500">*</span></label>
            <div class="relative">
                <select name="tahun_ajaran_id" required class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                    <option value="">Pilih Tahun Ajaran</option>
                    @foreach($tahunAjaranList as $ta)
                        <option value="{{ $ta->id_tahun_ajaran }}" {{ $ta->status == 'Aktif' ? 'selected' : '' }}>
                            {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }} {{ $ta->status == 'Aktif' ? '(Aktif)' : '' }}
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

        <!-- Target Siswa -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-3">Target Siswa <span class="text-red-500">*</span></label>
            <div class="space-y-3">
                <label class="flex items-center space-x-3 p-3 border-2 border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                    <input type="radio" name="target_siswa" value="semua" required class="w-5 h-5 text-blue-600" onchange="toggleKelasSelect(false)">
                    <span class="text-slate-700 font-medium">Semua Siswa Aktif</span>
                </label>
                <label class="flex items-center space-x-3 p-3 border-2 border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                    <input type="radio" name="target_siswa" value="kelas_tertentu" required class="w-5 h-5 text-blue-600" onchange="toggleKelasSelect(true)">
                    <span class="text-slate-700 font-medium">Kelas Tertentu</span>
                </label>
            </div>
        </div>

        <!-- Kelas -->
        <div id="kelasSection" style="display: none;">
            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kelas</label>
            <div class="relative">
                <select name="kelas_id" id="kelasSelect" class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Nama Tagihan -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Tagihan <span class="text-red-500">*</span></label>
            <input type="text" name="nama_tagihan" value="{{ old('nama_tagihan') }}" required placeholder="Contoh: SPP Bulan Januari 2025" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
        </div>

        <!-- Bulan dan Tahun -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select name="bulan" required class="w-full appearance-none border-2 border-blue-200 rounded-lg px-4 py-2 text-slate-700 pr-8 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="">Pilih Bulan</option>
                        <option value="1">Januari</option>
                        <option value="2">Februari</option>
                        <option value="3">Maret</option>
                        <option value="4">April</option>
                        <option value="5">Mei</option>
                        <option value="6">Juni</option>
                        <option value="7">Juli</option>
                        <option value="8">Agustus</option>
                        <option value="9">September</option>
                        <option value="10">Oktober</option>
                        <option value="11">November</option>
                        <option value="12">Desember</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                <input type="number" name="tahun" value="{{ date('Y') }}" required min="2020" max="2100" class="w-full border-2 border-blue-200 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
            </div>
        </div>

        <!-- Jumlah Bayar -->
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Bayar <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">Rp</span>
                <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required min="1" placeholder="0" class="w-full border-2 border-blue-200 rounded-lg pl-12 pr-4 py-2 focus:outline-none focus:border-blue-500">
            </div>
        </div>

        <!-- Alert Info -->
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
            <div class="flex items-start">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mt-0.5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm text-blue-700 font-semibold mb-1">Informasi Penting:</p>
                    <ul class="text-sm text-blue-600 list-disc list-inside space-y-1">
                        <li>Tagihan akan dibuat untuk semua siswa yang dipilih</li>
                        <li>Sistem akan mengecek duplikasi (siswa + bulan + tahun)</li>
                        <li>Tagihan yang sudah ada tidak akan dibuat ulang</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center space-x-4 pt-4">
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
    </form>
</div>

<script>
function toggleKelasSelect(show) {
    const kelasSection = document.getElementById('kelasSection');
    const kelasSelect = document.getElementById('kelasSelect');
    
    if (show) {
        kelasSection.style.display = 'block';
        kelasSelect.required = true;
    } else {
        kelasSection.style.display = 'none';
        kelasSelect.required = false;
        kelasSelect.value = '';
    }
}
</script>

@endsection


