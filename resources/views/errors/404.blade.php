{{-- filepath: resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        html, body, * { 
            font-family: 'Poppins', sans-serif !important; 
        }
    </style>
</head>
<body class="font-sans bg-gradient-to-b from-white to-blue-50">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-3xl w-full text-center">
            
            <!-- Logo Section -->
            <div class="mb-8 flex justify-center">
                <img src="{{ asset('images/logo_sman12.png') }}" alt="Logo SMA 12" class="w-24 h-24 object-contain" />
            </div>

            <!-- Error Code -->
            <div class="mb-6">
                <h1 class="text-[140px] md:text-[200px] font-bold text-blue-500 leading-none drop-shadow-lg">
                    404
                </h1>
            </div>

            <!-- Error Message -->
            <div class="mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Ups! Halaman Tidak Ditemukan
                </h2>
                <p class="text-lg text-gray-600 max-w-xl mx-auto leading-relaxed">
                    Sepertinya Anda tersesat. Halaman yang Anda cari tidak tersedia atau telah dipindahkan.
                </p>
            </div>

            <!-- Friendly Message Card -->
            <div class="bg-white rounded-3xl shadow-lg border-2 border-blue-100 p-8 mb-10 max-w-2xl mx-auto">
                <div class="mb-6">
                    <svg class="w-20 h-20 mx-auto text-blue-400 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-blue-700 mb-4">
                    Yang Bisa Anda Lakukan:
                </h3>
                <ul class="text-left space-y-3 text-gray-700 max-w-md mx-auto">
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-4 text-base">Periksa kembali alamat URL yang Anda masukkan</span>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-4 text-base">Kembali ke halaman sebelumnya</span>
                    </li>
                    <li class="flex items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="ml-4 text-base">Mulai dari halaman utama</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                <a href="javascript:history.back()" 
                   class="inline-flex items-center px-8 py-3 bg-white text-blue-500 border-2 border-blue-500 rounded-full font-semibold text-base hover:bg-blue-50 transition-all duration-200 shadow-md hover:shadow-lg min-w-[180px] justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                
                <a href="/" 
                   class="inline-flex items-center px-8 py-3 bg-blue-500 text-white rounded-full font-semibold text-base hover:bg-blue-600 transition-all duration-200 shadow-lg hover:shadow-xl min-w-[180px] justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Halaman Utama
                </a>

                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-8 py-3 bg-green-500 text-white rounded-full font-semibold text-base hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl min-w-[180px] justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login
                </a>
            </div>

            <!-- Help Text -->
            <div class="mt-10">
                <p class="text-base text-gray-600">
                    Butuh bantuan lebih lanjut? Hubungi administrator sekolah
                </p>
            </div>

        </div>
    </div>

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