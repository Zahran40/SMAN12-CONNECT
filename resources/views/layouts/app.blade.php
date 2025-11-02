<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Opsi: Menambahkan font default yang mirip, seperti Inter */
        @import url('https://rsms.me/inter/inter.css');
        html { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100">

    <div class="flex flex-col h-screen">
        
        <header class="bg-blue-400 text-white p-4 flex justify-between items-center shadow-md z-10">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/1760101516102 1.png') }}" alt="Logo SMA Negeri 12 Medan" class="h-11 md:h-14 lg:h-14 w-auto object-contain" />
                <h1 class="text-lg font-bold">SMA NEGERI 12 MEDAN</h1>
            </div>
            <a href="{{ route('siswa.profil') }}" class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white hover:bg-white/30 focus:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Profil Siswa">
    <img
        src="{{ asset('images/Frame 50.png') }}"
        alt="Profil Siswa"
        class="w-20 h-18 rounded-full object-cover"
    />
</a>
        </header>

        <div class="flex flex-1 overflow-hidden">
            
            @include('layouts.sidebar')

            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                @yield('content')
            </main>

        </div>
    </div>

</body>
</html>