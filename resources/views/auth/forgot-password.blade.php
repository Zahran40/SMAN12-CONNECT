<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password | SMA Negeri 12 Medan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#e6f0fa] flex items-center justify-center min-h-screen relative">

    <div class="absolute top-8 left-8">
        <a href="{{ route('login') }}"
            class="inline-flex items-center bg-white rounded-xl shadow-md px-4 py-2 text-[#2a5db0] font-medium text-[18px] hover:shadow-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="#2a5db0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="flex flex-col md:flex-row bg-white rounded-[32px] shadow-xl max-w-[980px] w-[90%] overflow-hidden">

        <div class="flex-1 p-10 md:p-12">

            <div class="flex items-center gap-3 mb-4 sm:mb-6">
                <img src="/images/logo_sman12.png" alt="Logo" class="h-[68px] w-[68px]">
                <div class="bg-[#4eaaff] text-white font-semibold rounded-full px-5 py-1.5 text-[15px]">
                    SMA NEGERI 12 MEDAN
                </div>
            </div>

            <h2 class="text-[#2a5db0] font-bold text-[32px] mb-2">Lupa Password</h2>
            <p class="text-gray-600 text-[15px] mb-4 sm:mb-6">Masukkan email Anda untuk menerima kode OTP</p>

            {{-- Flash Messages --}}
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 sm:mb-6 rounded-lg" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 sm:mb-6 rounded-lg" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 sm:mb-6 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.send-otp') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="text-[15px] font-medium">Email</label>
                    <input id="email" type="email" name="email" required autofocus
                        value="{{ old('email') }}"
                        class="w-full mt-1 border border-[#bcd6f6] rounded-lg px-4 py-3 text-[16px] focus:ring-2 focus:ring-[#4eaaff] outline-none @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email Anda">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-[#4eaaff] text-white rounded-full py-3 text-[17px] font-medium shadow-md hover:bg-[#3d97f5] transition">
                    Kirim Kode OTP
                </button>

                <div class="text-center">
                    <p class="text-gray-600 text-[14px]">
                        Sudah ingat password? 
                        <a href="{{ route('login') }}" class="text-[#4eaaff] hover:underline font-medium">Login di sini</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="hidden md:flex flex-1 bg-gradient-to-br from-[#46a8fe] to-[#5db6fd] 
         items-center justify-center text-center text-white p-10 
         md:rounded-r-[32px]">
            <div class="space-y-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <span class="text-[32px] font-bold tracking-wide leading-snug block">
                    RESET<br>PASSWORD
                </span>
            </div>
        </div>

    </div>

</body>

</html>

