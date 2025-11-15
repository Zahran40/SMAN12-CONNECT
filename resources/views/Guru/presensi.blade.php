@extends('layouts.guru.app')

@section('title', 'Presensi')

@section('content')

  <h2 class="text-3xl font-bold text-slate-800 mb-6">Materi Kelas Saya</h2>



    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-slate-900 mb-2">Presensi mata Pelajaran Anda</h2>
        <p class="text-sm text-slate-500 mb-8">Pilih Mata Pelajaran anda untuk membuat presensi kehadiran</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl border-2 border-blue-400 p-6 flex flex-col shadow-sm">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/Schedule.png') }}" alt="Ikon Nilai Raport" class="w-32 h-32 object-contain">
                </div>
                <div class="text-left mb-6">
                    <p class="text-slate-600 mb-1">Kelas 2A</p>
                    <h3 class="text-xl font-bold text-blue-600 mb-2">Mata Pelajaran</h3>
                    <div class="flex items-center text-slate-500 text-sm">
                        <img src="{{ asset('images/Student.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.182-.311a3.376 3.376 0 002.246-2.976 60.646 60.646 0 01-9.9-5.86a.75.75 0 010-1.337A60.653 60.653 0 0111.7 2.805z" />
                            <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-5.385 2.929.75.75 0 01-.853 0A47.878 47.878 0 008.916 17.2l-.46-.713a.75.75 0 01-.087-.395c.043-1.15.116-2.295.22-3.428l.051-.526.024-.253.038-.396.05-.53c.19-2.048.48-4.076.867-6.076l.003-.015v.001c.464-2.34 1.043-4.64 1.726-6.896.675 2.21 1.25 4.567 1.72 6.915l.002.011.004.02.014.069.063.316.054.275.084.42.107.534c.38 1.886.667 3.825.857 5.823l.004.042.025.255.05.517.054.554.127 1.323.043.444h.002c.015.155.029.31.043.465a.75.75 0 01-.75.82h-.005a.75.75 0 01-.745-.68c-.015-.15-.029-.305-.044-.46v-.002l-.043-.443-.126-1.32-.054-.555-.05-.518-.026-.256-.004-.041a48.836 48.836 0 00-.853-5.787l-.107-.535-.084-.419-.054-.275-.064-.317-.014-.069-.004-.02-.002-.011c-.45-2.275-1.01-4.555-1.668-6.766a60.586 60.586 0 00-1.673 6.748v.002l-.003.015a48.868 48.868 0 00-.863 6.038l-.05.53-.038.397-.024.252-.051.527c-.1 1.11-.172 2.233-.215 3.36l.848 1.314c1.683.586 3.409 1.07 5.166 1.451a.75.75 0 001.114-.605V15.473z" />
                        </img>
                        36 Siswa
                    </div>
                </div>
                <a href="{{ route('guru.detail_presensi') }}" class="w-full bg-blue-400 text-white text-center font-bold text-lg py-3 rounded-full hover:bg-blue-500 transition-colors mt-auto">
                    Pergi
                </a>
            </div>

            <div class="bg-white rounded-3xl border-2 border-blue-400 p-6 flex flex-col shadow-sm">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/Schedule.png') }}" alt="Ikon Nilai Raport" class="w-32 h-32 object-contain">
                </div>
                <div class="text-left mb-6">
                    <p class="text-slate-600 mb-1">Kelas 2B</p>
                    <h3 class="text-xl font-bold text-blue-600 mb-2">Mata Pelajaran</h3>
                    <div class="flex items-center text-slate-500 text-sm">
                        <img src="{{ asset('images/Student.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.182-.311a3.376 3.376 0 002.246-2.976 60.646 60.646 0 01-9.9-5.86a.75.75 0 010-1.337A60.653 60.653 0 0111.7 2.805z" />
                            <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-5.385 2.929.75.75 0 01-.853 0A47.878 47.878 0 008.916 17.2l-.46-.713a.75.75 0 01-.087-.395c.043-1.15.116-2.295.22-3.428l.051-.526.024-.253.038-.396.05-.53c.19-2.048.48-4.076.867-6.076l.003-.015v.001c.464-2.34 1.043-4.64 1.726-6.896.675 2.21 1.25 4.567 1.72 6.915l.002.011.004.02.014.069.063.316.054.275.084.42.107.534c.38 1.886.667 3.825.857 5.823l.004.042.025.255.05.517.054.554.127 1.323.043.444h.002c.015.155.029.31.043.465a.75.75 0 01-.75.82h-.005a.75.75 0 01-.745-.68c-.015-.15-.029-.305-.044-.46v-.002l-.043-.443-.126-1.32-.054-.555-.05-.518-.026-.256-.004-.041a48.836 48.836 0 00-.853-5.787l-.107-.535-.084-.419-.054-.275-.064-.317-.014-.069-.004-.02-.002-.011c-.45-2.275-1.01-4.555-1.668-6.766a60.586 60.586 0 00-1.673 6.748v.002l-.003.015a48.868 48.868 0 00-.863 6.038l-.05.53-.038.397-.024.252-.051.527c-.1 1.11-.172 2.233-.215 3.36l.848 1.314c1.683.586 3.409 1.07 5.166 1.451a.75.75 0 001.114-.605V15.473z" />
                        </img>
                        32 Siswa
                    </div>
                </div>
                <a href="{{ route('guru.detail_presensi') }}" class="w-full bg-blue-400 text-white text-center font-bold text-lg py-3 rounded-full hover:bg-blue-500 transition-colors mt-auto">
                    Pergi
                </a>
            </div>

            <div class="bg-white rounded-3xl border-2 border-blue-400 p-6 flex flex-col shadow-sm">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('images/Schedule.png') }}" alt="Ikon Nilai Raport" class="w-32 h-32 object-contain">
                </div>
                <div class="text-left mb-6">
                    <p class="text-slate-600 mb-1">Kelas 3A</p>
                    <h3 class="text-xl font-bold text-blue-600 mb-2">Mata Pelajaran</h3>
                    <div class="flex items-center text-slate-500 text-sm">
                        <img src="{{ asset('images/Student.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mr-2">
                            <path d="M11.7 2.805a.75.75 0 01.6 0A60.65 60.65 0 0122.83 8.72a.75.75 0 01-.231 1.337 49.949 49.949 0 00-9.902 3.912l-.003.002-.34.18a.75.75 0 01-.707 0A50.009 50.009 0 007.5 12.174v-.224c0-.131.067-.248.182-.311a3.376 3.376 0 002.246-2.976 60.646 60.646 0 01-9.9-5.86a.75.75 0 010-1.337A60.653 60.653 0 0111.7 2.805z" />
                            <path d="M13.06 15.473a48.45 48.45 0 017.666-3.282c.134 1.414.22 2.843.255 4.285a.75.75 0 01-.46.71 47.878 47.878 0 00-5.385 2.929.75.75 0 01-.853 0A47.878 47.878 0 008.916 17.2l-.46-.713a.75.75 0 01-.087-.395c.043-1.15.116-2.295.22-3.428l.051-.526.024-.253.038-.396.05-.53c.19-2.048.48-4.076.867-6.076l.003-.015v.001c.464-2.34 1.043-4.64 1.726-6.896.675 2.21 1.25 4.567 1.72 6.915l.002.011.004.02.014.069.063.316.054.275.084.42.107.534c.38 1.886.667 3.825.857 5.823l.004.042.025.255.05.517.054.554.127 1.323.043.444h.002c.015.155.029.31.043.465a.75.75 0 01-.75.82h-.005a.75.75 0 01-.745-.68c-.015-.15-.029-.305-.044-.46v-.002l-.043-.443-.126-1.32-.054-.555-.05-.518-.026-.256-.004-.041a48.836 48.836 0 00-.853-5.787l-.107-.535-.084-.419-.054-.275-.064-.317-.014-.069-.004-.02-.002-.011c-.45-2.275-1.01-4.555-1.668-6.766a60.586 60.586 0 00-1.673 6.748v.002l-.003.015a48.868 48.868 0 00-.863 6.038l-.05.53-.038.397-.024.252-.051.527c-.1 1.11-.172 2.233-.215 3.36l.848 1.314c1.683.586 3.409 1.07 5.166 1.451a.75.75 0 001.114-.605V15.473z" />
                        </img>
                        40 Siswa
                    </div>
                </div>
                <a href="{{ route('guru.detail_presensi') }}" class="w-full bg-blue-400 text-white text-center font-bold text-lg py-3 rounded-full hover:bg-blue-500 transition-colors mt-auto">
                    Pergi
                </a>
            </div>
        </div>
    </div>

@endsection