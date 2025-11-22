@extends('layouts.admin.app')

@section('content')
<div class="content-wrapper p-4 sm:p-6">

    <div class="flex items-center space-x-4 mb-4 sm:mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-xl sm:text-2lg sm:xl font-bold text-blue-700">Membuat Tahun Ajaran Baru</h1>
    </div>

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            <div class="font-bold mb-2">Terjadi kesalahan:</div>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success Message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-white rounded-2lg sm:xl shadow p-4 sm:p-6">
        <h1 class="text-xl font-bold text-blue-700 mb-4 sm:mb-6">Tahun Ajaran Baru</h1>

        <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST">
            @csrf
        
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-blue-700 mb-4">Informasi Tahun Ajaran</h2>

            {{-- Info Box --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 sm:mb-6 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-800">Sistem akan otomatis membuat 2 semester:</p>
                        <ul class="mt-2 text-sm text-blue-700 space-y-1">
                            <li>✓ <strong>Semester Ganjil</strong> (Juli - Desember) - Status: <span class="text-green-600 font-semibold">Aktif</span></li>
                            <li>✓ <strong>Semester Genap</strong> (Januari - Juni) - Status: Tidak Aktif</li>
                        </ul>
                        <p class="mt-2 text-xs text-blue-600">💡 Anda bisa mengaktifkan Semester Genap nanti di halaman Manajemen Tahun Ajaran</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tahun Mulai <span class="text-red-500">*</span></label>
                    <select name="tahun_mulai" required class="border {{ $errors->has('tahun_mulai') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-3 w-full focus:ring focus:ring-blue-200 focus:border-blue-400 text-base">
                        <option value="">Pilih Tahun Mulai</option>
                        @php
                            $currentYear = date('Y');
                            $defaultTahunMulai = old('tahun_mulai', $currentYear);
                        @endphp
                        @for ($year = $currentYear - 1; $year <= $currentYear + 5; $year++)
                            <option value="{{ $year }}" {{ $defaultTahunMulai == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('tahun_mulai')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-medium mb-2">Tahun Selesai <span class="text-red-500">*</span></label>
                    <select name="tahun_selesai" required class="border {{ $errors->has('tahun_selesai') ? 'border-red-500' : 'border-gray-300' }} rounded-lg p-3 w-full focus:ring focus:ring-blue-200 focus:border-blue-400 text-base">
                        <option value="">Pilih Tahun Selesai</option>
                        @php
                            $defaultTahunSelesai = old('tahun_selesai', $currentYear + 1);
                        @endphp
                        @for ($year = $currentYear; $year <= $currentYear + 6; $year++)
                            <option value="{{ $year }}" {{ $defaultTahunSelesai == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                    @error('tahun_selesai')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Preview Tahun Ajaran --}}
            <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Preview Tahun Ajaran:</p>
                <p class="text-xl sm:text-2lg sm:xl font-bold text-blue-700" id="preview-tahun-ajaran">-</p>
            </div>

            {{-- Warning Duplikasi --}}
            <div id="warning-duplikasi" class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg hidden">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-yellow-800">⚠️ Tahun Ajaran Sudah Terdaftar</p>
                        <p class="text-sm text-yellow-700 mt-1">Tahun ajaran <strong id="duplicate-year"></strong> sudah ada dalam sistem. Silakan pilih tahun yang berbeda.</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="mt-10 text-right">
            <button type="submit" id="submit-button" class="bg-green-500 hover:bg-green-600 text-white font-medium px-6 py-2 rounded-lg sm:xl shadow transition">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/save.png') }}" alt="save" class="w-6 h-6">
                    <span>Buat Tahun Ajaran (2 Semester)</span>
                </div>
            </button>
        </div>
        </form>
    </div>
</div>

<script>
    // Data tahun ajaran yang sudah ada (dari controller)
    const existingYears = @json($existingYears ?? []);

    // Preview tahun ajaran saat user memilih tahun
    document.addEventListener('DOMContentLoaded', function() {
        const tahunMulai = document.querySelector('select[name="tahun_mulai"]');
        const tahunSelesai = document.querySelector('select[name="tahun_selesai"]');
        const preview = document.getElementById('preview-tahun-ajaran');
        const warningBox = document.getElementById('warning-duplikasi');
        const duplicateYear = document.getElementById('duplicate-year');
        const submitButton = document.getElementById('submit-button');
        
        function updatePreview() {
            const mulai = tahunMulai.value;
            const selesai = tahunSelesai.value;
            
            if (mulai && selesai) {
                const tahunAjaran = mulai + '/' + selesai;
                
                // Validasi tahun selesai harus lebih besar
                if (parseInt(selesai) <= parseInt(mulai)) {
                    preview.textContent = '⚠️ Tahun selesai harus lebih besar dari tahun mulai';
                    preview.className = 'text-xl sm:text-2lg sm:xl font-bold text-red-600';
                    warningBox.classList.add('hidden');
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-green-600');
                    return;
                }
                
                // Cek duplikasi
                if (existingYears.includes(tahunAjaran)) {
                    preview.textContent = tahunAjaran;
                    preview.className = 'text-xl sm:text-2lg sm:xl font-bold text-yellow-600';
                    duplicateYear.textContent = tahunAjaran;
                    warningBox.classList.remove('hidden');
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-green-600');
                } else {
                    preview.textContent = tahunAjaran + ' ✓';
                    preview.className = 'text-xl sm:text-2lg sm:xl font-bold text-green-600';
                    warningBox.classList.add('hidden');
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:bg-green-600');
                }
            } else {
                preview.textContent = '-';
                preview.className = 'text-xl sm:text-2lg sm:xl font-bold text-gray-400';
                warningBox.classList.add('hidden');
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.classList.remove('hover:bg-green-600');
            }
        }
        
        // Auto-update tahun selesai saat tahun mulai dipilih (SELALU tahun berikutnya)
        tahunMulai.addEventListener('change', function() {
            if (this.value) {
                const nextYear = parseInt(this.value) + 1;
                tahunSelesai.value = nextYear;
                updatePreview();
            }
        });
        
        // Auto-update tahun mulai saat tahun selesai dipilih (SELALU tahun sebelumnya)
        tahunSelesai.addEventListener('change', function() {
            if (this.value) {
                const prevYear = parseInt(this.value) - 1;
                tahunMulai.value = prevYear;
                updatePreview();
            }
        });
        
        // Initial preview
        updatePreview();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

