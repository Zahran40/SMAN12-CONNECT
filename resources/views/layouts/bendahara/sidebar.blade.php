<nav id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-white shadow-lg min-h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">
    <!-- Close Button for Mobile -->
    <div class="flex items-center justify-between p-4 border-b lg:hidden">
        <h2 class="text-lg font-semibold text-slate-800">Menu Bendahara</h2>
        <button id="close-sidebar-btn" class="p-2 rounded-lg hover:bg-slate-100 focus:outline-none" aria-label="Close Menu">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <div class="p-4 overflow-y-auto" style="max-height: calc(100vh - 73px);">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('bendahara.beranda') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.beranda') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                    </svg>
                    Dashboard Keuangan
                </a>
            </li>

            <li class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Manajemen SPP</p>
            </li>

            <li>
                <a href="{{ route('bendahara.manajemen-tagihan-spp') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.manajemen-tagihan-spp') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Manajemen Tagihan SPP
                </a>
            </li>

            <li>
                <a href="{{ route('bendahara.verifikasi-pembayaran') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.verifikasi-pembayaran') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Verifikasi Pembayaran
                </a>
            </li>

            <li>
                <a href="{{ route('bendahara.tunggakan-reminder') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.tunggakan-reminder') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    Tunggakan & Reminder
                </a>
            </li>

            <li>
                <a href="{{ route('bendahara.rekonsiliasi-bank') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.rekonsiliasi-bank') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    Rekonsiliasi Bank
                </a>
            </li>

            <li class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Laporan & Metode Pembayaran</p>
            </li>

            <li>
                <a href="{{ route('bendahara.rekap-pembayaran') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.rekap-pembayaran') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    Rekap Pembayaran
                </a>
            </li>

            <li>
                <a href="{{ route('bendahara.multi-payment-method') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.multi-payment-method') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    Multi Payment Method
                </a>
            </li>

            <li>
                <a href="{{ route('bendahara.refund-management') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.refund-management') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                    </svg>
                    Refund Management
                </a>
            </li>

            <li class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Pengaturan</p>
            </li>

            <li>
                <a href="{{ route('bendahara.manajemen-diskon-beasiswa') }}"
                     class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('bendahara.manajemen-diskon-beasiswa') ? 'bg-emerald-100 text-emerald-600' : 'text-slate-600 hover:bg-slate-100' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                    Diskon & Beasiswa
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
