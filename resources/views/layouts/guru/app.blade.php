<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Guru') | SMAN 12 Connect</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
            <button id="hamburger-btn-guru" class="lg:hidden p-2 rounded-lg hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Toggle Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="flex items-center gap-2 sm:gap-3 flex-1 lg:flex-initial justify-center lg:justify-start">
                <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo SMA Negeri 12 Medan" class="h-12 sm:h-14 md:h-16 w-auto object-contain" />
                <h1 class="text-sm sm:text-base md:text-xl font-semibold hidden sm:block">SMA NEGERI 12 MEDAN</h1>
            </div>
            
            <a href="{{ route('guru.profil') }}" class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center text-white hover:bg-white/30 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 shrink-0" aria-label="Profil Guru">
                <img src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}" alt="Profil Guru" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover" />
            </a>
        </header>

        <div class="flex flex-1 overflow-hidden relative">

            @include('layouts.guru.sidebar')

            <main class="flex-1 p-4 sm:p-6 md:p-8 overflow-y-auto">
                
                <!-- Global Confirmation Modal -->
                <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all" onclick="event.stopPropagation()">
                        <div class="p-6">
                            <div class="flex justify-center mb-4">
                                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-center text-gray-800 mb-2" id="confirmTitle">⚠️ PERHATIAN!</h3>
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
                                <p class="text-sm text-red-800 font-semibold" id="confirmMessage"></p>
                            </div>
                            <p class="text-center text-gray-600 mb-6" id="confirmQuestion">Apakah Anda yakin ingin melanjutkan?</p>
                            <div class="flex gap-3">
                                <button onclick="closeConfirmModal()" class="flex-1 bg-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-lg hover:bg-gray-300 transition">
                                    Batal
                                </button>
                                <button id="confirmButton" class="flex-1 bg-red-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition">
                                    Ya, Lanjutkan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Toast Container -->
                <div id="toastContainer" class="fixed top-20 right-4 z-50 space-y-2"></div>

                {{-- Alert Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-green-700 hover:text-green-900">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
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
                        <button onclick="this.parentElement.style.display='none'" class="text-red-700 hover:text-red-900">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 flex items-center justify-between" role="alert">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button onclick="this.parentElement.style.display='none'" class="text-blue-700 hover:text-blue-900">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

        </div>
    </div>

    <!-- Overlay for Mobile Sidebar -->
    <div id="sidebar-overlay-guru" class="hidden fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    <script>
        // Sidebar Toggle Script for Guru
        const hamburgerBtnGuru = document.getElementById('hamburger-btn-guru');
        const sidebarGuru = document.getElementById('sidebar-guru');
        const sidebarOverlayGuru = document.getElementById('sidebar-overlay-guru');
        const closeSidebarBtnGuru = document.getElementById('close-sidebar-btn-guru');

        function openSidebarGuru() {
            sidebarGuru.classList.remove('-translate-x-full');
            sidebarGuru.classList.add('translate-x-0');
            sidebarOverlayGuru.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebarGuru() {
            sidebarGuru.classList.add('-translate-x-full');
            sidebarGuru.classList.remove('translate-x-0');
            sidebarOverlayGuru.classList.add('hidden');
            document.body.style.overflow = '';
        }

        if (hamburgerBtnGuru) {
            hamburgerBtnGuru.addEventListener('click', openSidebarGuru);
        }

        if (closeSidebarBtnGuru) {
            closeSidebarBtnGuru.addEventListener('click', closeSidebarGuru);
        }

        if (sidebarOverlayGuru) {
            sidebarOverlayGuru.addEventListener('click', closeSidebarGuru);
        }

        // Close sidebar when clicking menu item on mobile
        const sidebarLinksGuru = sidebarGuru?.querySelectorAll('a');
        sidebarLinksGuru?.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebarGuru();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebarGuru();
            }
        });

        // Global Confirm Modal Functions
        let confirmCallback = null;

        window.showConfirmModal = function(options) {
            const modal = document.getElementById('confirmModal');
            const title = document.getElementById('confirmTitle');
            const message = document.getElementById('confirmMessage');
            const question = document.getElementById('confirmQuestion');
            const confirmBtn = document.getElementById('confirmButton');

            title.textContent = options.title || '⚠️ PERHATIAN!';
            message.textContent = options.message || '';
            question.textContent = options.question || 'Apakah Anda yakin ingin melanjutkan?';
            confirmBtn.textContent = options.confirmText || 'Ya, Lanjutkan';
            confirmBtn.className = options.confirmClass || 'flex-1 bg-red-600 text-white font-semibold px-6 py-3 rounded-lg hover:bg-red-700 transition';

            confirmCallback = options.onConfirm;
            modal.classList.remove('hidden');
        };

        window.closeConfirmModal = function() {
            document.getElementById('confirmModal').classList.add('hidden');
            confirmCallback = null;
        };

        document.getElementById('confirmButton').addEventListener('click', function() {
            if (confirmCallback) {
                confirmCallback();
            }
            closeConfirmModal();
        });

        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });

        // Global Toast Function
        window.showToast = function(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const bgColors = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                warning: 'bg-yellow-500',
                info: 'bg-blue-500'
            };

            const icons = {
                success: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>',
                error: '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>',
                warning: '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>',
                info: '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>'
            };

            toast.className = `${bgColors[type]} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md transform transition-all duration-300 translate-x-0`;
            toast.innerHTML = `
                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    ${icons[type]}
                </svg>
                <span class="flex-1">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.transform = 'translateX(400px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        };
    </script>

    @stack('scripts')
</body>
</html>


