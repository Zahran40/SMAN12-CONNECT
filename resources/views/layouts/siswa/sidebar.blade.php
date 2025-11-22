<nav class="w-64 bg-white shadow-lg min-h-screen">
    <div class="p-4">
        <ul class="space-y-2">

                        <li>
                                <a href="{{ route('siswa.beranda') }}"
                                     aria-current="{{ request()->routeIs('siswa.beranda') ? 'page' : '' }}"
                                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('siswa.beranda') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/material-symbols_home-rounded.png') }}"  viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-3">
                                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                                        </img>
                                        Beranda
                                </a>
                        </li>

                        <li>
                                <a href="{{ route('siswa.presensi') }}"
                                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('siswa.presensi') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/mingcute_task-line.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                        </img>
                                        Presensi Kehadiran
                                </a>
                        </li>

                        <li>
                                <a href="{{ route('siswa.materi') }}"
                                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('siswa.materi') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/solar_book-bold.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                                        </img>
                                        Materi Kelas
                                </a>
                        </li>
            
                        <li>
                                <a href="{{ route('siswa.nilai') }}"
                                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('siswa.nilai') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/streamline-plump_file-report-solid.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </img>
                                        Nilai Rapor
                                </a>
                        </li>

                        <li>
                                <a href="{{ route('siswa.tagihan') }}"
                                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('siswa.tagihan') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/si_money-fill.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h6m3-3.75l-3-3m3 3l3 3m-3-3v3.75m6-6.75h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-7.5a2.25 2.25 0 0 1 2.25-2.25h.75Z" />
                                        </img>
                                        Tagihan Uang Sekolah
                                </a>
                        </li>

                        <li>
                                <a href="{{ route('siswa.pengumuman') }}"
                                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('siswa.pengumuman') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/si_money-fill.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A1.5 1.5 0 0 1 18 21.75H6a1.5 1.5 0 0 1-1.499-1.632Z" />
                                        </img>
                                        Pengumuman
                                </a>
                        </li>

            </ul>

            
    </div>
</nav>
