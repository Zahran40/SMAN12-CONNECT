@extends('layouts.admin.app')

@section('title', 'Pengumuman')

@section('content')
    <div class="flex flex-col space-y-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-blue-700">Pengumuman</h1>
                <p class="text-slate-500 text-sm">(Daftar pengumuman yang telah dibuat)</p>
            </div>
            <a href="{{ route('admin.pengumuman.create') }}" class="w-full sm:w-auto bg-blue-400 hover:bg-blue-500 text-white px-5 sm:px-6 py-2 sm:py-2.5 rounded-full font-bold flex items-center justify-center space-x-2 shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Buat Pengumuman</span>
            </a>
        </div>

        <div class="space-y-6">
            @forelse($pengumuman as $item)
            <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 border-2 {{ $item->status === 'aktif' ? 'border-blue-300' : 'border-slate-300' }} shadow-sm relative {{ $item->status === 'nonaktif' ? 'opacity-60' : '' }}">
                <div class="absolute top-4 right-4 flex items-center gap-2">
                    {{-- Toggle Status Button --}}
                    <form action="{{ route('admin.pengumuman.toggle', $item->id_pengumuman) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors font-medium text-sm
                                {{ $item->status === 'aktif' ? 'bg-orange-500 hover:bg-orange-600 text-white' : 'bg-green-500 hover:bg-green-600 text-white' }}"
                                title="{{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                            @if($item->status === 'aktif')
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            <span class="hidden sm:inline">{{ $item->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                        </button>
                    </form>
                    
                    {{-- Delete Button --}}
                    <form id="delete-pengumuman-{{ $item->id_pengumuman }}" action="{{ route('admin.pengumuman.destroy', $item->id_pengumuman) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="showConfirmModal({
                            title: '⚠️ Hapus Pengumuman',
                            message: 'Pengumuman ini akan dihapus secara permanen.',
                            question: 'Yakin ingin menghapus pengumuman ini?',
                            confirmText: 'Ya, Hapus',
                            onConfirm: () => document.getElementById('delete-pengumuman-{{ $item->id_pengumuman }}').submit()
                        })" class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors font-medium text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="hidden sm:inline">Hapus</span>
                        </button>
                    </form>
                </div>

                <div class="text-center mb-4 sm:mb-6">
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <h2 class="text-xl font-bold text-slate-800">{{ $item->judul }}</h2>
                        {{-- Status Badge --}}
                        @if($item->status === 'aktif')
                            <span class="inline-block bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-semibold">
                                Aktif
                            </span>
                        @else
                            <span class="inline-block bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full text-xs font-semibold">
                                Nonaktif
                            </span>
                        @endif
                    </div>
                    <div class="mt-2">
                        @if($item->target_role == 'Semua')
                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                 Untuk Semua (Guru & Siswa)
                            </span>
                        @elseif($item->target_role == 'guru')
                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Khusus Guru
                            </span>
                        @else
                            <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">
                                 Khusus Siswa
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-6 text-sm">
                    <p class="text-slate-700"><span class="font-medium">Hari</span> : {{ $item->hari ?? \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('l') }}</p>
                    <p class="text-slate-700"><span class="font-medium">Tanggal</span> : {{ \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('d F Y') }}</p>
                </div>

                <div class="mb-6">
                    <p class="text-slate-700 text-sm leading-relaxed text-justify">
                        {{ $item->isi_pengumuman }}
                    </p>
                </div>

                @if($item->file_lampiran)
                <div class="mb-6">
                    <a href="{{ Storage::url($item->file_lampiran) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm transition-colors" download>
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                        </svg>
                        Unduh Lampiran
                    </a>
                </div>
                @endif

                <div class="text-right text-sm">
                    <p class="text-slate-700">Medan {{ \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('d F Y') }}</p>
                    <p class="font-bold text-blue-600">SMA 12 NEGERI MEDAN</p>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-2xl p-4 sm:p-6 md:p-8 border border-blue-200 shadow-sm text-center">
                <p class="text-slate-500">Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        // Auto hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-auto-hide');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s';
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
    </script>
@endsection

