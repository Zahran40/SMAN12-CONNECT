@extends('layouts.guru.app')

@section('title', 'Beranda Guru')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-4 sm:mb-6">Beranda</h2>

    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 mb-6 sm:mb-8 flex items-center space-x-4">
        <div class="rounded-full overflow-hidden w-16 h-16 bg-slate-100 flex items-center justify-center ring-4 ring-blue-100">
            @if($guru && $guru->foto_profil)
                <img src="{{ asset('storage/' . $guru->foto_profil) }}" alt="Foto Guru" class="w-full h-full object-cover" />
            @else
                <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Foto Guru" class="w-full h-full object-cover" />
            @endif
        </div>
        <div>
            <h3 class="text-xl font-bold text-slate-900">{{ $guru->nama_lengkap ?? 'Nama Guru' }}</h3>
            <p class="text-sm text-slate-500">NIP: {{ $guru->nip ?? '-' }}</p>
            <span class="inline-block border border-yellow-400 text-yellow-600 text-xs font-semibold px-3 py-1 rounded-full mt-2">Guru</span>
        </div>
    </div>

    <section class="mb-8">
        <h3 class="text-xl font-bold text-blue-600 mb-4">Jadwal Mengajar</h3>
        
        <div class="flex space-x-2 mb-4">
            @foreach($allDays as $day)
                <button onclick="switchDay('{{ $day }}')" 
                        class="day-tab px-8 py-10 rounded-lg text-sm font-medium {{ $day == $hariIni ? 'bg-blue-400 text-white' : 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50' }}"
                        data-day="{{ $day }}">
                    {{ $day }}
                </button>
            @endforeach
        </div>
        
        @foreach($allDays as $day)
            <div id="jadwal-{{ $day }}" class="day-schedule bg-white rounded-xl shadow-md p-4 sm:p-6 space-y-4 {{ $day != $hariIni ? 'hidden' : '' }}">
                @if($jadwalPerHari[$day]->count() > 0)
                    @foreach($jadwalPerHari[$day] as $jadwal)
                        <div class="border border-slate-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center text-blue-600 text-sm font-medium mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                </svg>
                                {{ substr($jadwal->jam_mulai, 0, 5) }}-{{ substr($jadwal->jam_selesai, 0, 5) }}
                            </div>
                            <h4 class="text-lg font-bold text-slate-800">{{ $jadwal->nama_mapel }}</h4>
                            <p class="text-sm text-slate-500">Kelas {{ $jadwal->nama_kelas }}</p>
                        </div>
                    @endforeach
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
        <h3 class="text-xl font-bold text-blue-600 mb-4">Mata Pelajaran Saya Hari ini</h3>
        <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 space-y-4">
            @if($jadwalHariIni->count() > 0)
                @foreach($jadwalHariIni as $jadwal)
                    <a href="{{ route('guru.materi', $jadwal->id_jadwal) }}" 
                       class="border-2 border-blue-300 rounded-2xl p-4 flex items-center justify-between hover:bg-blue-50 transition-colors cursor-pointer block">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('images/Book (1).png') }}" alt="Ikon Buku" class="w-14 h-14 object-contain">
                            
                            <div>
                                <h4 class="text-base font-bold text-blue-600">{{ $jadwal->nama_mapel }}</h4>
                                <p class="text-sm text-slate-500">Kelas {{ $jadwal->nama_kelas }}</p>
                                <div class="flex items-center text-xs text-slate-400 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" />
                                    </svg>
                                    {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                </div>
                                <div class="flex items-center text-xs text-slate-400 mt-1">
                                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 mr-1">
                                        <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.182-.311a3.376 3.376 0 002.246-2.976 60.646 60.646 0 01-9.9-5.86a.75.75 0 010-1.337A60.653 60.653 0 0111.7 2.805z" />
                                        <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-5.385 2.929.75.75 0 01-.853 0A47.878 47.878 0 008.916 17.2l-.46-.713a.75.75 0 01-.087-.395c.043-1.15.116-2.295.22-3.428l.051-.526.024-.253.038-.396.05-.53c.19-2.048.48-4.076.867-6.076l.003-.015v.001c.464-2.34 1.043-4.64 1.726-6.896.675 2.21 1.25 4.567 1.72 6.915l.002.011.004.02.014.069.063.316.054.275.084.42.107.534c.38 1.886.667 3.825.857 5.823l.004.042.025.255.05.517.054.554.127 1.323.043.444h.002c.015.155.029.31.043.465a.75.75 0 01-.75.82h-.005a.75.75 0 01-.745-.68c-.015-.15-.029-.305-.044-.46v-.002l-.043-.443-.126-1.32-.054-.555-.05-.518-.026-.256-.004-.041a48.836 48.836 0 00-.853-5.787l-.107-.535-.084-.419-.054-.275-.064-.317-.014-.069-.004-.02-.002-.011c-.45-2.275-1.01-4.555-1.668-6.766a60.586 60.586 0 00-1.673 6.748v.002l-.003.015a48.868 48.868 0 00-.863 6.038l-.05.53-.038.397-.024.252-.051.527c-.1 1.11-.172 2.233-.215 3.36l.848 1.314c1.683.586 3.409 1.07 5.166 1.451a.75.75 0 001.114-.605V15.473z" />
                                    </svg>
                                    {{ $jadwal->jumlah_siswa }} Siswa
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center text-blue-400 text-sm font-medium">
                            Pergi
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 ml-1">
                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                            </svg>
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

