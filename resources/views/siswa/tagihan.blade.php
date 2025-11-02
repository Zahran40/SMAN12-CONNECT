@extends('layouts.app')

@section('content')

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-blue-500">Tagihan Uang Sekolah</h2>
        <button class="flex items-center space-x-2 bg-blue-100 text-blue-700 font-medium px-4 py-2 rounded-lg hover:bg-blue-200 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
              <path fill-rule="evenodd" d="M5 2.75C5 1.784 5.784 1 6.75 1h6.5c.966 0 1.75.784 1.75 1.75v3.5A1.75 1.75 0 0 1 13.25 8H6.75A1.75 1.75 0 0 1 5 6.25v-3.5ZM11.5 4a.5.5 0 0 0 0-1H8.5a.5.5 0 0 0 0 1h3Z" clip-rule="evenodd" />
              <path fill-rule="evenodd" d="M1.5 8.75A1.75 1.75 0 0 1 3.25 7h13.5A1.75 1.75 0 0 1 18.5 8.75v5.75c0 .59-.224 1.135-.59 1.562l-2.148 2.148A1.75 1.75 0 0 1 14.22 19H5.78a1.75 1.75 0 0 1-1.543-.89l-2.148-2.148A1.75 1.75 0 0 1 1.5 14.5v-5.75ZM3.25 8.5a.25.25 0 0 0-.25.25v5.75c0 .1.03.192.08.27l2.148 2.148c.078.078.17.13.27.13h8.44a.25.25 0 0 0 .19-.08l2.148-2.148a.25.25 0 0 0 .08-.27v-5.75a.25.25 0 0 0-.25-.25H3.25Z" clip-rule="evenodd" />
            </svg>
            <span>Cetak Tagihan</span>
        </button>
    </div>

    <div class="flex space-x-4 mb-6">
        <button class="bg-blue-600 text-white font-medium px-6 py-2 rounded-lg text-sm shadow-md">
            Belum Dibayar
        </button>
        <button data-tab="sudah" class="bg-white text-slate-600 font-medium px-6 py-2 rounded-lg text-sm border border-slate-300 hover:bg-slate-50">
            Sudah Dibayarkan
        </button>
    </div>

    <div class="space-y-6">
        
        <div class="bg-white rounded-xl border-2 border-blue-600 overflow-hidden">
            
            <div class="p-5 flex justify-between items-start border-b border-slate-200">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Semester Genap 25/26</h3>
                    <p class="text-sm text-slate-500">Desember</p>
                </div>
                <span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Belum dibayar
                </span>
            </div>

            <div class="p-5 space-y-6">
                
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                        <h4 class="text-sm font-semibold text-slate-600 uppercase">Detail Siswa</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-xs text-slate-500">Nama</p>
                            <p class="font-medium text-slate-800">Nama Siswa</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">NIS</p>
                            <p class="font-medium text-slate-800">19013810922</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Kelas</p>
                            <p class="font-medium text-slate-800">Kelas 12-A</p>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                        <h4 class="text-sm font-semibold text-slate-600 uppercase">Detail Tagihan</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-xs text-slate-500">Nama Tagihan</p>
                            <p class="font-medium text-slate-800">Uang Sekolah Bulanan</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Waktu Tagihan dibuat</p>
                            <p class="font-medium text-slate-800">01 Desember 2025</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Nominal</p>
                            <p class="font-medium text-slate-800">Rp 200.000</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-5 flex justify-end border-t border-slate-200 bg-slate-50/50">
                <a href="{{ route('siswa.detail_tagihan') }}" class="bg-blue-600 text-white font-medium px-10 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Bayar
                </a>
            </div>

        </div>

        </div>

    <script>
        window.TAGIHAN_PAID_URL = "{{ route('siswa.tagihan_sudah_dibayar') }}";
    </script>
    @vite('resources/js/tagihan.js')

@endsection