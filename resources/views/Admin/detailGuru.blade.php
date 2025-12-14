@extends('layouts.admin.app')

@section('content')
<div class="flex flex-col space-y-4 sm:space-y-6">
    <div class="flex items-center space-x-4">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h1 class="text-2xl font-bold text-blue-700">Detail Guru</h1>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center space-x-4 sm:space-x-6">
            <div class="w-24 h-24 bg-blue-100 rounded-2xl flex items-center justify-center">
                <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Icon Guru" class="w-20 h-20">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-slate-800">{{ $guru->nama_lengkap }}</h2>
                <p class="text-slate-500 text-sm mb-2">NIP {{ $guru->nip }}</p>
                <span class="border border-yellow-400 text-yellow-600 text-xs font-semibold px-6 py-1 rounded-full">
                    Guru
                </span>
            </div>
        </div>
        <form action="{{ route('admin.data-master.guru.destroy', $guru->id_guru) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus guru ini?')">
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
            <h3 class="text-xl font-bold text-blue-600">Data Diri Guru</h3>
            <a href="{{ route('admin.data-master.guru.edit', $guru->id_guru) }}" class="bg-green-400 hover:bg-green-500 text-white px-4 py-2 sm:py-1.5 rounded-full flex items-center justify-center space-x-2 transition-colors text-sm font-medium w-full sm:w-auto">
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
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->nama_lengkap }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Tanggal lahir</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->tgl_lahir ? \Carbon\Carbon::parse($guru->tgl_lahir)->format('d/m/Y') : '-' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Tempat lahir</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->tempat_lahir }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Alamat</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->alamat }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Jenis Kelamin</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">NIP</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->nip }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">No handphone</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->no_telepon ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 border-b border-slate-100 pb-3">
                    <span class="font-bold text-slate-800">Email</span>
                    <a href="mailto:{{ $guru->user->email ?? $guru->email }}" class="col-span-2 text-slate-600 font-medium underline decoration-slate-400">{{ $guru->user->email ?? $guru->email ?? '-' }}</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 pb-3">
                    <span class="font-bold text-slate-800">Agama</span>
                    <span class="col-span-2 text-slate-600 font-medium">{{ $guru->agama }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Mata Pelajaran yang Diajar --}}
    <div>
        <h3 class="text-xl font-bold text-blue-600 mb-4">Mata Pelajaran yang Diajar</h3>
        
        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm">
            @if($guru->mataPelajaran && $guru->mataPelajaran->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Kode
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Nama Mata Pelajaran
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Kelas
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Tingkat
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($guru->mataPelajaran as $index => $mapel)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $mapel->kode_mapel }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900">
                                    {{ $mapel->nama_mapel }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">
                                    {{ $mapel->nama_kelas }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-slate-900">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $mapel->tingkat == 10 ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $mapel->tingkat == 11 ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $mapel->tingkat == 12 ? 'bg-purple-100 text-purple-800' : '' }}">
                                        Kelas {{ $mapel->tingkat }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4 text-sm text-slate-500">
                    Total: <span class="font-semibold text-slate-700">{{ $guru->mataPelajaran->count() }}</span> mata pelajaran
                </div>
            @else
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <p class="text-slate-400 font-medium">Belum ada mata pelajaran yang diajar</p>
                    <p class="text-slate-400 text-sm mt-1">Guru ini belum ditugaskan untuk mengajar mata pelajaran apapun</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

