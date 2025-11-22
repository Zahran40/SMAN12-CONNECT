<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin') | SMAN 12 Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite('resources/css/app.css')
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    html, body, * { font-family: 'Poppins', sans-serif !important; }
  </style>
</head>
<body class="bg-slate-100">

    <div class="flex flex-col h-screen">
        
        <header class="bg-blue-400 text-white p-2.5 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo SMA Negeri 12 Medan" class="h-16 md:h-16 lg:h-16 w-auto object-contain" />
                <h1 class="text-xl font-semibold">SMA NEGERI 12 MEDAN</h1>
            </div>
            <div class="flex items-center gap-3 relative">
                <div class="text-right">
                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                    <p class="text-sm text-blue-100">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
                <button id="profileButton" class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-white/50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileDropdown" class="hidden absolute top-full right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-slate-200 overflow-hidden z-50">
                    <div class="p-4 border-b border-slate-200 bg-slate-50">
                        <p class="font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-slate-600">{{ Auth::user()->email }}</p>
                        <p class="text-xs text-blue-600 mt-1">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <div class="p-2">
                        <button id="logoutButton" type="button" class="flex items-center w-full px-4 py-3 font-medium rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>

                <!-- Hidden Logout Form -->
                <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">

            @include('layouts.admin.sidebar')

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        // Toggle dropdown
        const profileButton = document.getElementById('profileButton');
        const profileDropdown = document.getElementById('profileDropdown');
        const logoutButton = document.getElementById('logoutButton');
        const logoutForm = document.getElementById('logoutForm');

        profileButton.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });

        // Logout with Sweet Alert
        logoutButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'font-poppins'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Logging out...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit logout form
                    logoutForm.submit();
                }
            });
        });
    </script>

</body>
</html>
