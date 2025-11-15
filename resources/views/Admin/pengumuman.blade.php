@extends('layouts.admin.app')

@section('title', 'Pengumuman')

@section('content')
    <div class="flex flex-col space-y-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-blue-700">Manajemen Pengumuman</h1>
                <p class="text-slate-500 text-sm">(Manajemen untuk membuat pengumuman)</p>
            </div>
            <a href="{{ route('admin.buat_pengumuman')}} " class="bg-blue-400 hover:bg-blue-500 text-white px-6 py-2.5 rounded-full font-bold flex items-center space-x-2 shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                <span>Pengumuman</span>
            </a>
        </div>

        <div class="space-y-6">
            @for ($i = 0; $i < 2; $i++)
            <div class="bg-white rounded-2xl p-8 border border-blue-200 shadow-sm">
                <div class="relative flex items-center justify-center mb-8">
                    <h2 class="text-xl font-bold text-slate-800">Judul Pengumuman</h2>
                    <div class="absolute right-0">
                        <button class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg flex items-center space-x-2 transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Hapus</span>
                        </button>
                    </div>
                </div>

                <div class="mb-6 text-slate-700 font-medium">
                    <div class="flex">
                        <span class="w-24">Hari</span>
                        <span>: Senin</span>
                    </div>
                    <div class="flex">
                        <span class="w-24">Tanggal</span>
                        <span>: 19 November 2025</span>
                    </div>
                </div>

                <div class="mb-8">
                    <p class="text-slate-600 text-sm leading-relaxed text-justify">
                        Kepada Seluruh Siswa, <br>
                        Diberitahukan kepada seluruh siswa dan dewan guru SMA Negeri 12 Medan bahwa akan diadakan rapat bersama yang bertujuan untuk mempererat komunikasi antara pihak guru dan siswa dalam membahas berbagai hal yang berkaitan dengan kegiatan sekolah, baik dalam bidang akademik maupun non-akademik. Melalui rapat ini diharapkan tercipta kerja sama yang lebih baik, saling pengertian, serta peningkatan kualitas lingkungan belajar di sekolah. Rapat ini juga menjadi wadah bagi siswa untuk menyampaikan aspirasi, saran, dan gagasan yang membangun demi kemajuan bersama.
                    </p>
                </div>

                <div class="text-right">
                    <p class="text-slate-700 font-bold text-sm mb-1">Medan 10 November 2025</p>
                    <p class="text-blue-600 font-bold text-sm uppercase">SMA 12 NEGERI MEDAN</p>
                </div>
            </div>
            @endfor
        </div>

    </div>
@endsection
