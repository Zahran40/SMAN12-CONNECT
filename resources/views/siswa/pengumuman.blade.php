@extends('layouts.siswa.app')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-6">Pengumuman</h2>

    <div class="space-y-6">

        @forelse($pengumuman as $item)
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
            
            <h3 class="text-center text-xl font-bold text-slate-900 mb-6">
                {{ $item->judul }}
            </h3>

            <div class="mb-6 text-sm text-slate-600 space-y-1">
                <p>Hari : {{ $item->hari ?? \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('l') }}</p>
                <p>Tanggal : {{ \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('d F Y') }}</p>
            </div>

            <div class="text-sm text-slate-700 leading-relaxed space-y-4">
                <p class="text-justify">{{ $item->isi_pengumuman }}</p>
            </div>

            @if($item->file_lampiran)
            <div class="mt-6">
                <a href="{{ Storage::url($item->file_lampiran) }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm transition-colors" download>
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    Unduh Lampiran
                </a>
            </div>
            @endif

            <div class="mt-8 text-right">
                <p class="text-sm text-slate-600 mb-1">Medan {{ \Carbon\Carbon::parse($item->tgl_publikasi)->translatedFormat('d F Y') }}</p>
                <p class="text-sm font-bold text-blue-700">SMA NEGERI 12 MEDAN</p>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 text-center">
            <p class="text-slate-500">Belum ada pengumuman</p>
        </div>
        @endforelse

    </div>

@endsection