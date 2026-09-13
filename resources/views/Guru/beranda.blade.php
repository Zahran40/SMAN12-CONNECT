@extends('layouts.guru.app')

@section('title', 'Beranda Guru')

@section('content')

    <h2 class="text-2xl sm:text-3xl font-bold text-blue-500 mb-4 sm:mb-6">Beranda</h2>

    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-6 sm:mb-8 flex items-center space-x-3 sm:space-x-4">
        <div class="rounded-full overflow-hidden w-12 h-12 sm:w-16 sm:h-16 bg-slate-100 flex items-center justify-center ring-4 ring-blue-100 flex-shrink-0">
            @if($guru && $guru->foto_profil)
                <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto Guru" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Foto Guru" class="w-full h-full object-cover" />
            @endif
        </div>
        <div>
            <h3 class="text-lg sm:text-xl font-bold text-slate-900">{{ $guru->nama_lengkap ?? 'Nama Guru' }}</h3>
            <p class="text-sm text-slate-500">NIP: {{ $guru->nip ?? '-' }}</p>
            <span class="inline-block border border-yellow-400 text-yellow-600 text-xs font-semibold px-3 py-1 rounded-full mt-2">Guru</span>
        </div>
    </div>

    <!-- Pengumuman Aktif -->
    @if(isset($pengumuman) && count($pengumuman) > 0)
    <section class="mb-6 sm:mb-8">
        <h3 class="text-lg sm:text-xl font-semibold text-blue-600 mb-3 sm:mb-4"> Pengumuman Terbaru</h3>
        <div class="space-y-3">
            @foreach($pengumuman as $item)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <h4 class="text-sm font-bold text-blue-800">{{ $item->judul }}</h4>
                            <p class="text-sm text-blue-700 mt-1">{{ $item->isi_pengumuman }}</p>
                            <p class="text-xs text-blue-600 mt-2">
                                <span class="font-medium">Tanggal:</span> {{ \Carbon\Carbon::parse($item->tgl_publikasi)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <section class="mb-8">
        <h3 class="text-lg sm:text-xl font-bold text-blue-600 mb-4">Jadwal Mengajar</h3>
        
        <div class="overflow-x-auto scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0 mb-4">
            <div class="flex space-x-2 min-w-max">
                @foreach($allDays as $day)
                    <button onclick="switchDay('{{ $day }}')" 
                            class="day-tab px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm font-medium flex-shrink-0 {{ $day == $hariIni ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50' }}"
                            data-day="{{ $day }}">
                        {{ $day }}
                    </button>
                @endforeach
            </div>
        </div>
        
        @foreach($allDays as $day)
            <div id="jadwal-{{ $day }}" class="day-schedule bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6 {{ $day != $hariIni ? 'hidden' : '' }}">
                @if($jadwalPerHari[$day]->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($jadwalPerHari[$day] as $jadwal)
                            <div class="border border-slate-200 rounded-xl p-4 hover:border-blue-300 hover:shadow-md transition-all bg-white flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full">
                                            Kelas {{ $jadwal->nama_kelas }}
                                        </span>
                                        <span class="flex items-center text-xs font-medium text-slate-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-1 text-blue-500">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                            </svg>
                                            {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                        </span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-800 leading-snug line-clamp-2" title="{{ $jadwal->nama_mapel }}">{{ $jadwal->nama_mapel }}</h4>
                                </div>
                                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $jadwal->jumlah_siswa ?? '-' }} Siswa</span>
                                    <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="text-blue-500 hover:text-blue-700 font-semibold flex items-center">
                                        Lihat Materi
                                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-3 opacity-50">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        <p class="font-medium">Tidak ada jadwal mengajar</p>
                        <p class="text-sm mt-1">Anda tidak memiliki kelas pada hari {{ $day }}</p>
                    </div>
                @endif
            </div>
        @endforeach
        
        <script>
            function switchDay(day) {
                // Hide all schedules
                document.querySelectorAll('.day-schedule').forEach(el => el.classList.add('hidden'));
                
                // Show selected day schedule
                document.getElementById('jadwal-' + day).classList.remove('hidden');
                
                // Update button styles
                document.querySelectorAll('.day-tab').forEach(btn => {
                    if (btn.dataset.day === day) {
                        btn.classList.remove('bg-white', 'text-slate-700', 'border', 'border-slate-300', 'hover:bg-slate-50');
                        btn.classList.add('bg-blue-400', 'text-white');
                    } else {
                        btn.classList.remove('bg-blue-400', 'text-white');
                        btn.classList.add('bg-white', 'text-slate-700', 'border', 'border-slate-300', 'hover:bg-slate-50');
                    }
                });
            }
        </script>
    </section>

    <section>
        <h3 class="text-lg sm:text-xl font-bold text-blue-600 mb-4">Mata Pelajaran Saya Hari ini</h3>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6 space-y-4">
            @if($jadwalHariIni->count() > 0)
                @foreach($jadwalHariIni as $jadwal)
                    <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" 
                       class="border-2 border-blue-200 hover:border-blue-400 bg-white rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-blue-50/50 hover:shadow-md transition-all cursor-pointer block gap-4">
                        <div class="flex items-center space-x-4 w-full sm:w-auto">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center flex-shrink-0">
                                <img src="{{ asset('images/Book (1).png') }}" alt="Ikon Buku" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base sm:text-lg font-bold text-slate-800 hover:text-blue-600 transition-colors">{{ $jadwal->nama_mapel }}</h4>
                                <p class="text-sm font-medium text-blue-500">Kelas {{ $jadwal->nama_kelas }}</p>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-1.5">
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1 text-blue-400">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                        </svg>
                                        {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1 text-blue-400">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                        </svg>
                                        {{ $jadwal->jumlah_siswa }} Siswa
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-end sm:justify-start w-full sm:w-auto mt-2 sm:mt-0">
                            <span class="px-4 py-2 bg-blue-50 text-blue-600 font-semibold text-sm rounded-xl hover:bg-blue-100 transition-colors flex items-center gap-1.5">
                                Kelola Materi
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="text-center py-8 text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 mx-auto mb-3 opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <p class="font-medium">Tidak ada kelas hari ini</p>
                    <p class="text-sm mt-1">Anda tidak memiliki jadwal mengajar pada hari {{ $hariIni }}</p>
                </div>
            @endif
        </div>
    </section>

@endsection

