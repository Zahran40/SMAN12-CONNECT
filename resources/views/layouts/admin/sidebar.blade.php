<nav id="sidebar-admin" class="fixed lg:static inset-y-0 left-0 w-64 bg-white shadow-lg min-h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">
    <!-- Close Button for Mobile -->
    <div class="flex items-center justify-between p-4 border-b lg:hidden">
        <h2 class="text-lg font-semibold text-slate-800">Menu Admin</h2>
        <button id="close-sidebar-btn-admin" class="p-2 rounded-lg hover:bg-slate-100 focus:outline-none" aria-label="Close Menu">
            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <div class="p-4 overflow-y-auto" style="max-height: calc(100vh - 73px);">
        <ul class="space-y-2">

                        <li>
                                                  <a href="{{ route('admin.tahun-ajaran.index') }}"
                                                          aria-current="{{ request()->routeIs('admin.tahun-ajaran.*') ? 'page' : '' }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.tahun-ajaran.*') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/iwwa_year.png') }}"  viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 mr-3">
                                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z" clip-rule="evenodd" />
                                        </img>
                                        Manajemen Tahun Ajaran
                                </a>
                        </li>

                        <li>
                                                  <a href="{{ route('admin.data-master.index') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.data-master.*') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/Group.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                                        </img>
                                        Manajemen Data Master
                                </a>
                        </li>

                        <li>
                                                  <a href="{{ route('admin.data-master.siswa.create') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.data-master.siswa.*') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        Pendataan Siswa
                                </a>
                        </li>

                        <li>
                                                  <a href="{{ route('admin.data-master.guru.create') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.data-master.guru.*') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                                        </svg>
                                        Pendataan Guru
                                </a>
                        </li>

                        <li>
                                                  <a href="{{ route('admin.kelas.all') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.kelas.all') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                        </svg>
                                        Pendataan Kelas
                                </a>
                        </li>

                        <li>
                                                  <a href="{{ route('admin.akademik.index') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.akademik') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/Vector (2).png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                                        </img>
                                        Manajemen Akademik
                                </a>
                        </li>
            
                        <li>
                                                  <a href="{{ route('admin.pengumuman') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.pengumuman') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/Vector (1).png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </img>
                                        Manajemen Pengumuman
                                </a>
                        </li>
                        <li>
                                                  <a href="{{ route('admin.pembayaran.index') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.pembayaran*') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <img src="{{ asset('images/si_money-fill.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A1.5 1.5 0 0 1 18 21.75H6a1.5 1.5 0 0 1-1.499-1.632Z" />
                                        </img>
                                        Manajemen Pembayaran
                                </a>
                        </li>

                        <li>
                                                  <a href="{{ route('admin.log-aktivitas.index') }}"
                                                          class="flex items-center px-4 py-3 font-medium rounded-lg {{ request()->routeIs('admin.log-aktivitas.*') ? 'bg-blue-100 text-blue-400' : 'text-slate-600 hover:bg-slate-100' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                                        </svg>
                                        Log Aktivitas
                                </a>
                        </li>

            </ul>

            <!-- Logout Button -->
            
    </div>
</nav>