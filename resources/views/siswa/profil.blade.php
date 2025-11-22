@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ url()->previous() }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Profil</h2>
    </div>

    <div class="bg-white rounded-xl  p-6 mb-8 flex items-center justify-between border border-slate-200">

    <!-- Kiri: Foto + Info Siswa -->
    <div class="flex items-center space-x-6">
        <div class="text-center shrink-0">
            <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center p-4 mb-2">
                <div class="rounded-full overflow-hidden w-16 h-16 ring-4 ring-blue-100">
                    @if($siswa && $siswa->foto_profil)
                        <img src="{{ asset('storage/' . $siswa->foto_profil) }}" alt="Foto Siswa" class="w-full h-full object-cover" />
                    @else
                        <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
                    @endif
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-bold text-slate-900">{{ $siswa->nama_lengkap ?? 'Nama Siswa' }}</h3>
            <p class="text-sm text-slate-500 mb-3">NIS: {{ $siswa->nis ?? '-' }}</p>
            @if($kelasNama)
                <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">{{ $kelasNama }}</span>
            @else
                <span class="inline-block bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full mt-2">Belum Ada Kelas</span>
            @endif
        </div>
    </div>

    
    <div class="ml-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="flex items-center px-5 py-3 font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                Logout
            </button>
        </form>
    </div>

</div>


    <div>
        <h3 class=" text-blue-400 font-semibold text-lg px-6 py-3 rounded-t-xl inline-block">Data Diri Siswa</h3>
        
        <div class="bg-white rounded-b-xl rounded-r-xl shadow-lg p-6 md:p-8">
            <div class="space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Nama</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->nama_lengkap ?? '-' }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Tanggal lahir</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->tgl_lahir ? $siswa->tgl_lahir->format('d/m/Y') : '-' }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Tempat lahir</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->tempat_lahir ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Alamat</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->alamat ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Jenis Kelamin</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->jenis_kelamin ?? '-' }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">NIS</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->nis ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">NISN</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->nisn ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">No handphone</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->no_telepon ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Email</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->email ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                    <p class="font-semibold text-slate-600">Agama</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->agama ?? '-' }}</p>
                </div>

              

            </div>
        </div>
    </div>

@endsection