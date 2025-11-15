<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SMA NEGERI 12 MEDAN</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @vite('resources/css/app.css')
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    html, body, * { font-family: 'Poppins', sans-serif !important; }
  </style>
</head>
<body class="font-sans">

  <!-- Section 1-->
  <section class="bg-cover bg-center min-h-screen flex flex-col justify-center items-center"
           style="background-image: url('{{ asset('images/bg_sman12.png') }}');">
            <div class="absolute inset-0 bg-black/40"></div>
    <header class="absolute top-0 w-full bg-white/90 backdrop-blur-sm rounded-b-3xl shadow px-4 py-4 mx-auto max-w-8xl flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo SMA 12" class="w-16 h-16 rounded-full border-2 border-white shadow" />
        <span class="font-semibold text-xl text-gray-800">SMA NEGERI 12 MEDAN</span>
      </div>
      <div class="flex gap-3">
        @auth
          <span class="text-gray-700 px-4 py-1.5">{{ Auth::user()->name }}</span>
          <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-red-400 text-white px-4 py-1.5 rounded-full font-medium shadow hover:bg-red-500 transition">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="bg-blue-400 text-white px-4 py-1.5 rounded-full font-medium shadow hover:bg-blue-500 transition">Masuk</a>
        @endauth
      </div>
    </header>

    <h1 class="text-white font-bold text-4xl md:text-5xl text-center drop-shadow-lg z-10">
      Bersama Membangun<br>Mimpi
    </h1>
  </section>

  <!-- Section 2 -->
  <section class="bg-white py-20">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-12 px-6">
      <img src="{{ asset('images/phone_mockup.png') }}" alt="App SMA 12" class="w-64 md:w-80 mx-auto md:mx-0">
      <div class="text-center md:text-left">
        <h2 class="text-3xl font-bold text-blue-700 mb-3">SATU UNTUK SEMUA</h2>
        <p class="text-gray-600 leading-relaxed">
          Satu aplikasi untuk semua kebutuhan guru dan murid dapat diakses di mana saja dan kapan saja.
        </p>
      </div>
    </div>
  </section>

  <!-- Section 3 -->
  <section class="bg-blue-500 text-white py-20">
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between gap-10 px-6">
      <div>
        <h2 class="text-3xl font-bold mb-3">EDUKASI MENJADI MUDAH</h2>
        <p class="text-white/90 leading-relaxed">
          Kini guru dan murid dapat terhubung dalam satu platform yang praktis dan interaktif. 
          Semua kegiatan belajar dapat dilakukan dengan mudah — mulai dari berbagi materi, memberikan tugas, 
          hingga berkomunikasi secara langsung.
        </p>
      </div>
      <img src="{{ asset('images/classroom.png') }}" alt="Kelas" class="w-80 mx-auto md:mx-0">
    </div>
  </section>

  <!-- Section 4 -->
 <section class="bg-linear-to-b from-white to-blue-200 py-20 text-center">
  <h2 class="text-3xl md:text-4xl font-bold mb-16">
    Mulai Pembelajaran dengan <br> E-Learning Sekarang
  </h2>
  <a href="{{ route('login') }}" 
     class="bg-blue-500 text-white px-12 py-4 rounded-full shadow-lg hover:bg-blue-600 transition font-semibold text-lg">
     Mulai
  </a>
</section>



    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-200 py-10 px-6">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10">
            <div>
                <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo" class="w-14 h-14 mb-3 object-contain">
                <h3 class="font-bold text-lg">SMA NEGERI 12 MEDAN</h3>
                <p class="text-sm text-gray-400 mt-3 leading-relaxed text-justify max-w-md">
                    SMA Negeri (SMAN) 12 Medan, merupakan salah satu Sekolah Menengah Atas Negeri yang ada di Provinsi Sumatera Utara, Indonesia. 
                    Sama dengan SMA pada umumnya di Indonesia masa pendidikan sekolah di SMAN 12 Medan ditempuh dalam waktu tiga tahun pelajaran, 
                    mulai dari Kelas X sampai Kelas XII.
                </p>
            </div>
            <div>
                <h3 class="font-bold text-lg mt-16 mb-3">INFORMATION</h3>
                <p class="text-sm text-gray-400 mt-3 leading-relaxed text-justify max-w-md">
                    Jl. Cempaka No. 75, Medan, Sumatera Utara, Indonesia <br>
                    061-8455904 <br>
                    NPSN 10210876
                </p>
            </div>
        </div>
    </footer>
    
</body>
</html>
