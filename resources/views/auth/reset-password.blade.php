<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password | SMA Negeri 12 Medan</title>
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
        <a href="{{ route('password.request') }}"
            class="inline-flex items-center bg-white rounded-xl shadow-md px-4 py-2 text-[#2a5db0] font-medium text-[18px] hover:shadow-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="#2a5db0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="flex flex-col md:flex-row bg-white rounded-[32px] shadow-xl max-w-[980px] w-[90%] overflow-hidden">

        <div class="flex-1 p-10 md:p-12">

            <div class="flex items-center gap-3 mb-6">
                <img src="/images/logo_sman12.png" alt="Logo" class="h-[68px] w-[68px]">
                <div class="bg-[#4eaaff] text-white font-semibold rounded-full px-5 py-1.5 text-[15px]">
                    SMA NEGERI 12 MEDAN
                </div>
            </div>

            <h2 class="text-[#2a5db0] font-bold text-[32px] mb-2">Reset Password</h2>
            <p class="text-gray-600 text-[15px] mb-6">Masukkan kode OTP dan password baru Anda</p>

            {{-- Flash Messages --}}
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="text-[15px] font-medium">Email</label>
                    <input id="email" type="email" name="email" required readonly
                        value="{{ session('email') ?? old('email') }}"
                        class="w-full mt-1 border border-[#bcd6f6] rounded-lg px-4 py-3 text-[16px] bg-gray-100 focus:ring-2 focus:ring-[#4eaaff] outline-none">
                </div>

                <div>
                    <label for="otp" class="text-[15px] font-medium">Kode OTP</label>
                    <input id="otp" type="text" name="otp" required autofocus maxlength="6"
                        value="{{ old('otp') }}"
                        class="w-full mt-1 border border-[#bcd6f6] rounded-lg px-4 py-3 text-[16px] text-center text-2xl font-bold tracking-widest focus:ring-2 focus:ring-[#4eaaff] outline-none @error('otp') border-red-500 @enderror"
                        placeholder="000000">
                    @error('otp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Cek email Anda untuk kode OTP (berlaku 10 menit)</p>
                </div>

               <div>
                    <label for="password" class="text-[15px] font-medium">Password Baru</label>
                    <div class="relative mt-1">
                        <input id="password" type="password" name="password" required
                            class="w-full border border-[#bcd6f6] rounded-lg px-4 py-3 pr-10 text-[16px] focus:ring-2 focus:ring-[#4eaaff] outline-none @error('password') border-red-500 @enderror"
                            placeholder="Masukkan password baru"
                            autocomplete="new-password">
                        
                        <button type="button" onclick="togglePassword('password', 'eyeIcon1')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer z-10 text-gray-500 hover:text-[#4eaaff] transition-colors">
                            <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-gray-500 text-sm mt-1">Minimal 8 karakter</p>
                </div>

                <div>
                    <label for="password_confirmation" class="text-[15px] font-medium">Konfirmasi Password</label>
                    <div class="relative mt-1">
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="w-full border border-[#bcd6f6] rounded-lg px-4 py-3 pr-10 text-[16px] focus:ring-2 focus:ring-[#4eaaff] outline-none"
                            placeholder="Masukkan ulang password baru"
                            autocomplete="new-password">
                        
                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-2 cursor-pointer z-10 text-gray-500 hover:text-[#4eaaff] transition-colors">
                            <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-500 text-sm mt-1">Ketik ulang password untuk konfirmasi</p>
                </div>
                   

                <button type="submit"
                    class="w-full bg-[#4eaaff] text-white rounded-full py-3 text-[17px] font-medium shadow-md hover:bg-[#3d97f5] transition">
                    Reset Password
                </button>

                <div class="text-center">
                    <p class="text-gray-600 text-[14px]">
                        Tidak menerima kode? 
                        <a href="{{ route('password.request') }}" class="text-[#4eaaff] hover:underline font-medium">Kirim ulang</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="hidden md:flex flex-1 bg-gradient-to-br from-[#46a8fe] to-[#5db6fd] 
         items-center justify-center text-center text-white p-10 
         md:rounded-r-[32px]">
            <div class="space-y-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <span class="text-[32px] font-bold tracking-wide leading-snug block">
                    PASSWORD<br>BARU
                </span>
            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                // Change to eye-slash icon
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = 'password';
                // Change back to eye icon
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>

</body>

</html>
