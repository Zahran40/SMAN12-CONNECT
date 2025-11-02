@extends('layouts.app')

@section('content')

 <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-blue-500">Tagihan Uang Sekolah</h2>
        </button>
    </div>

    <div class="flex space-x-4 mb-6">
        <button data-tab="belum" class="bg-white text-slate-600 font-medium px-6 py-2 rounded-lg text-sm border border-slate-300 hover:bg-slate-50">
            Belum Dibayar
        </button>
        <button class="bg-blue-600 text-white font-medium px-6 py-2 rounded-lg text-sm shadow-md">
            Sudah Dibayar
        </button>
    </div>

    <div class="space-y-6">
        
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            
            <div class="p-5 flex justify-between items-start border-b border-slate-200">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Semester Genap 25/26</h3>
                    <p class="text-sm text-slate-500">November</p>
                </div>
                <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                    Sudah dibayar
                </span>
            </div>

            <div class="p-5 space-y-6">
                
                <div>
                    <div class="flex items-center space-x-2 mb-3">
                        <span class="w-6 h-2 bg-yellow-400 rounded-sm"></span>
                        <h4 class="text-sm font-semibold text-slate-600 uppercase">Detail Siswa</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
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
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
                        <div>
                            <p class="text-xs text-slate-500">Nama Tagihan</p>
                            <p class="font-medium text-slate-800">Uang Sekolah Bulanan</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Waktu Tagihan dibuat</p>
                            <p class="font-medium text-slate-800">01 November 2025</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Waktu Tagihan dibayar</p>
                            <p class="font-medium text-slate-800">05 November 2025</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Nominal</p>
                            <p class="font-medium text-slate-800">Rp 200.000</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-5 flex justify-end border-t border-slate-200 bg-slate-50/50">
                <a href="{{ route('siswa.detail_tagihan_sudah_dibayar') }}" class="bg-blue-600 text-white font-medium px-10 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                    Detail
                </a>
            </div>

        </div>

        </div>

    <script>
        window.TAGIHAN_UNPAID_URL = "{{ route('siswa.tagihan') }}";
    </script>
    @vite('resources/js/tagihan.js')

@endsection