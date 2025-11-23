@extends('layouts.siswa.app')

@section('content')

    <div class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6">
        <a href="{{ url()->previous() }}" class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors shrink-0" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-6 h-6 sm:w-8 sm:h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-xl sm:text-2xl md:text-2xl sm:text-3xl font-bold text-slate-800">Profil</h2>
    </div>

    <div class="bg-white rounded-xl p-4 sm:p-6 mb-6 sm:mb-8 flex flex-col sm:flex-row items-center sm:justify-between border border-slate-200 gap-4">

    <!-- Kiri: Foto + Info Siswa -->
    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-6 text-center sm:text-left">
        <div class="text-center shrink-0">
            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-blue-100 rounded-full flex items-center justify-center p-3 sm:p-4 mb-2">
                <div class="rounded-full overflow-hidden w-14 h-14 sm:w-16 sm:h-16 ring-4 ring-blue-100">
                    @if($siswa && $siswa->foto_profil)
                        <img src="{{ asset('storage/' . $siswa->foto_profil) }}" alt="Foto Siswa" class="w-full h-full object-cover" />
                    @else
                        <img src="{{ asset('images/Frame 50.png') }}" alt="Foto Siswa" class="w-full h-full object-cover" />
                    @endif
                </div>
            </div>
        </div>

        <div class="flex-1">
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-slate-900">{{ $siswa->nama_lengkap ?? 'Nama Siswa' }}</h3>
            <p class="text-xs sm:text-sm text-slate-500 mb-2 sm:mb-3">NIS: {{ $siswa->nis ?? '-' }}</p>
            @if($kelasNama)
                <span class="inline-block bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full mt-2">{{ $kelasNama }}</span>
            @else
                <span class="inline-block bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full mt-2">Belum Ada Kelas</span>
            @endif
        </div>
    </div>

    
    <div class="w-full sm:w-auto sm:ml-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full sm:w-auto flex items-center justify-center px-4 sm:px-5 py-2 sm:py-3 font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 transition text-sm sm:text-base">
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
        <h3 class="text-blue-400 font-semibold text-base sm:text-lg px-4 sm:px-6 py-2 sm:py-3 rounded-t-xl inline-block">Data Diri Siswa</h3>
        
        <div class="bg-white rounded-b-xl rounded-r-xl shadow-lg p-4 sm:p-6 md:p-8">
            <div class="space-y-4 sm:space-y-5">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm">
                    <p class="font-semibold text-slate-600">Nama</p>
                    <p class="md:col-span-2 text-slate-800 break-words">{{ $siswa->nama_lengkap ?? '-' }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm">
                    <p class="font-semibold text-slate-600">Tanggal lahir</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->tgl_lahir ? $siswa->tgl_lahir->format('d/m/Y') : '-' }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm">
                    <p class="font-semibold text-slate-600">Tempat lahir</p>
                    <p class="md:col-span-2 text-slate-800">{{ $siswa->tempat_lahir ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-1 sm:gap-2 text-xs sm:text-sm">
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
