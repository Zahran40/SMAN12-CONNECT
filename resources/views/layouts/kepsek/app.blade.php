<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Kepala Sekolah') | SMAN 12 Connect</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    html, body, * { font-family: 'Poppins', sans-serif !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100">

    <div class="flex flex-col h-screen">
        
        <header class="bg-blue-400 text-white p-2.5 sm:p-3 flex justify-between items-center shadow-md z-20 relative">
            <!-- Hamburger Button for Mobile -->
            <button id="hamburger-btn" class="lg:hidden p-2 rounded-lg hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Toggle Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-2 sm:gap-3 flex-1 lg:flex-initial justify-center lg:justify-start">
                <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo SMA Negeri 12 Medan" class="h-12 sm:h-14 md:h-16 w-auto object-contain" />
                <div class="hidden sm:block">
                    <h1 class="text-sm sm:text-base md:text-xl font-semibold">SMA NEGERI 12 MEDAN</h1>
                    <p class="text-xs text-blue-100">Portal Kepala Sekolah</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="text-right hidden md:block">
                    <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-blue-100">Kepala Sekolah</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center text-white shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden relative">
            
            @include('layouts.kepsek.sidebar')

            <main class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto">
                
                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">×</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">×</button>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <!-- Overlay for Mobile Sidebar -->
    <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <script>
        // Sidebar Toggle Script
        const hamburgerBtn = document.getElementById('hamburger-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            sidebarOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (hamburgerBtn) hamburgerBtn.addEventListener('click', openSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);
    </script>

    @stack('scripts')
</body>
</html>
