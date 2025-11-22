@extends('layouts.admin.app')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-600">Manajemen Pembayaran SPP</h1>
        <p class="text-slate-500 text-sm mt-1">(Manajemen untuk pembayaran per siswa)</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r mb-4 sm:mb-6 shadow-sm">
            <p class="font-bold">Berhasil</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r mb-4 sm:mb-6 shadow-sm">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white p-4 sm:p-6 rounded-xl border border-blue-400 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-medium mb-1">Total Tagihan</p>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($totalTagihan) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl border border-blue-400 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-medium mb-1">Sudah Lunas</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($totalLunas) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl border border-blue-400 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-medium mb-1">Belum Lunas</p>
                <p class="text-3xl font-bold text-red-600">{{ number_format($totalBelumLunas) }}</p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-6 rounded-xl border border-blue-400 shadow-sm flex justify-between items-center">
            <div>
                <p class="text-sm text-slate-500 font-medium mb-1">Total Terbayar</p>
                <p class="text-2xl font-bold text-yellow-500">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-blue-400 mb-6 sm:mb-8">
        <form method="GET" class="flex flex-col md:flex-row md:items-end gap-4">
            
            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Tahun Ajaran</label>
                <div class="relative">
                    <select name="tahun_ajaran" onchange="this.form.submit()" class="w-full appearance-none border border-blue-300 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunAjaranList as $ta)
                            <option value="{{ $ta->id_tahun_ajaran }}" {{ request('tahun_ajaran') == $ta->id_tahun_ajaran ? 'selected' : '' }}>
                                {{ $ta->tahun_mulai }}/{{ $ta->tahun_selesai }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Kelas</label>
                <div class="relative">
                    <select name="kelas" onchange="this.form.submit()" class="w-full appearance-none border border-blue-300 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ request('kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/4">
                <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                <div class="relative">
                    <select name="status" onchange="this.form.submit()" class="w-full appearance-none border border-blue-300 rounded-lg px-4 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-auto flex space-x-2">
                <a href="{{ route('admin.pembayaran.index') }}" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                    Reset Filter
                </a>
                <a href="{{ route('admin.pembayaran.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Buat Tagihan
                </a>
            </div>

        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-blue-400 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Tagihan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembayaranList as $index => $pembayaran)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $pembayaranList->firstItem() + $index }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900">{{ $pembayaran->siswa->nama_lengkap }}</span>
                                <span class="text-xs text-slate-500">{{ $pembayaran->siswa->nis }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $pembayaran->siswa->kelas->nama_kelas ?? '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-slate-900">
                            {{ $pembayaran->nama_tagihan }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-600">
                            @php
                                $bulanText = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                            @endphp
                            {{ $bulanText[$pembayaran->bulan] }} {{ $pembayaran->tahun }}
                        </td>

                        <td class="px-6 py-4 text-sm font-bold text-slate-900">
                            Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4">
                            @if($pembayaran->status === 'Lunas')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Lunas
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Belum Lunas
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.pembayaran.show', $pembayaran->id_pembayaran) }}" class="text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-slate-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium text-slate-600">Belum ada data pembayaran</p>
                                <p class="text-sm text-slate-400 mt-1">Silakan klik tombol "Buat Tagihan" untuk memulai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembayaranList->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $pembayaranList->links() }}
            </div>
        @endif
    </div>

@endsection

