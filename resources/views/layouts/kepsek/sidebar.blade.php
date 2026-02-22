<nav id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-white shadow-lg min-h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">
    <!-- Close Button for Mobile -->
    <div class="flex items-center justify-between p-4 border-b lg:hidden">
        <h2 class="text-lg font-semibold text-slate-800">Menu Kepala Sekolah</h2>
        <button id="close-sidebar-btn" class="p-2 rounded-lg hover:bg-slate-100 focus:outline-none" aria-label="Close Menu">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <div class="p-4 overflow-y-auto" style="max-height: calc(100vh - 73px);">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('kepsek.beranda') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.beranda') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                    </svg>
                    Dashboard Executive
                </a>
            </li>

            <li class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Laporan & Analytics</p>
            </li>

            <li>
                <a href="{{ route('kepsek.laporan-akademik-global') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.laporan-akademik-global') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    Laporan Akademik Global
                </a>
            </li>

            <li>
                <a href="{{ route('kepsek.monitoring-presensi') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.monitoring-presensi') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Monitoring Presensi
                </a>
            </li>

            <li>
                <a href="{{ route('kepsek.laporan-keuangan') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.laporan-keuangan') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                    Laporan Keuangan
                </a>
            </li>

            <li>
                <a href="{{ route('kepsek.analisis-trending') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.analisis-trending') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                    Analisis Trending
                </a>
            </li>

            <li>
                <a href="{{ route('kepsek.export-reports') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.export-reports') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Export Reports
                </a>
            </li>

            <li class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manajemen</p>
            </li>

            <li>
                <a href="{{ route('kepsek.evaluasi-kinerja-guru') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.evaluasi-kinerja-guru') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    Evaluasi Kinerja Guru
                </a>
            </li>

            <li>
                <a href="{{ route('kepsek.manajemen-pengumuman') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('kepsek.manajemen-pengumuman') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 001.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 010 3.46" />
                    </svg>
                    Manajemen Pengumuman
                </a>
            </li>

            <li class="pt-4 pb-2 mt-4 border-t">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 font-medium rounded-lg text-red-600 hover:bg-red-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
