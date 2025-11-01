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
            <h1 class="text-lg font-bold">SMA NEGERI 12 MEDAN</h1>
            <button class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A1.5 1.5 0 0 1 18 21.75H6a1.5 1.5 0 0 1-1.499-1.632Z" />
                </svg>
            </button>
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