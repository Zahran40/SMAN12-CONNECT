<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA NEGERI 12 MEDAN</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-[#60aaff] min-h-screen">
    <!-- Header -->
    <header class="w-full bg-white rounded-b-3xl shadow px-4 py-3 mt-2 mx-auto max-w-5xl flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="images/logo_sman12.png" alt="Logo SMA 12" class="w-12 h-12 rounded-full border-2 border-white shadow" />
            <span class="font-semibold text-lg text-gray-800">SMA NEGERI 12 MEDAN</span>
        </div>
        <div class="flex gap-3">
            <a href="" class="bg-white border border-blue-400 text-blue-600 px-4 py-1.5 rounded-full font-medium shadow hover:bg-blue-50 transition">Daftar</a>
            <a href="" class="bg-blue-400 text-white px-4 py-1.5 rounded-full font-medium shadow hover:bg-blue-500 transition">Masuk</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative flex flex-col items-center justify-center min-h-[70vh] pt-10 pb-0">
        <!-- Background clouds and sun -->
        <div class="absolute inset-0 z-0">
            <!-- Sun -->
            <div class="absolute top-10 right-32 w-20 h-20 bg-yellow-200 rounded-full opacity-80"></div>
            <!-- Clouds -->
            <div class="absolute top-0 left-0 w-2/3 h-32 bg-gradient-to-r from-white/90 to-transparent rounded-b-full blur-sm"></div>
            <div class="absolute top-12 right-0 w-1/3 h-20 bg-gradient-to-l from-white/70 to-transparent rounded-b-full blur-sm"></div>
        </div>

        <!-- Title -->
        <div class="relative z-10 mt-24 mb-10 text-center">
            <h1 class="text-white font-bold text-3xl md:text-4xl drop-shadow-lg">Bersama Membangun<br>Mimpi</h1>
        </div>

        <!-- School Illustration (replace with your SVG/png as needed) -->
        <div class="relative z-10 flex justify-center">
            <img src="{{ asset('img/bg-sekolah.png') }}" alt="SMA NEGERI 12 MEDAN" class="w-full max-w-3xl rounded-xl shadow-2xl border-4 border-white" />
        </div>
    </main>
</body>
</html>