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
</head>
<body class="bg-slate-100">

    <div class="flex flex-col h-screen">
        
        <header class="bg-blue-400 text-white p-2.5 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo SMA Negeri 12 Medan" class="h-16 md:h-16 lg:h-16 w-auto object-contain" />
                <h1 class="text-xl font-semibold">SMA NEGERI 12 MEDAN</h1>
            </div>
            <a href="{{ route('guru.profil') }}" class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-white hover:bg-white/30 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Profil Siswa">
    <img
        src="{{ asset('images/openmoji_woman-teacher-light-skin-tone.png') }}"
        alt="Profil Siswa"
        class="w-32 h-18 rounded-full object-cover"
    />
</a>
        </header>

        <div class="flex flex-1 overflow-hidden">

            @include('layouts.guru.sidebar')

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>
