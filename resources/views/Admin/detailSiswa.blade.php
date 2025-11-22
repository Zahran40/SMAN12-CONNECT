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
                <div class="grid grid-cols-1 md:grid-cols-3 pb-3"> <span class="font-bold text-slate-800">Kelas</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

