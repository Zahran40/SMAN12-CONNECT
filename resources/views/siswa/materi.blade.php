@extends('layouts.siswa.app')

@section('content')

    <h2 class="text-3xl font-bold text-blue-500 mb-6">Materi Kelas</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
            <h4 class="font-semibold text-blue-500 mb-4">Mata Pelajaran</h4>
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            <a href="{{ route('siswa.detail_materi') }}" class="bg-blue-400 text-white font-medium py-2 px-12 rounded-full hover:bg-blue-700 transition-colors">
                Lihat
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
            <h4 class="font-semibold text-blue-500 mb-4">Mata Pelajaran</h4>
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            <a href="{{ route('siswa.detail_materi') }}" class="bg-blue-400 text-white font-medium py-2 px-12 rounded-full hover:bg-blue-700 transition-colors">
                Lihat
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
            <h4 class="font-semibold text-blue-500 mb-4">Mata Pelajaran</h4>
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            <a href="{{ route('siswa.detail_materi') }}" class="bg-blue-400 text-white font-medium py-2 px-12 rounded-full hover:bg-blue-700 transition-colors">
                Lihat
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
            <h4 class="font-semibold text-blue-500 mb-4">Mata Pelajaran</h4>
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            <a href="{{ route('siswa.detail_materi') }}" class="bg-blue-400 text-white font-medium py-2 px-12 rounded-full hover:bg-blue-700 transition-colors">
                Lihat
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
            <h4 class="font-semibold text-blue-500 mb-4">Mata Pelajaran</h4>
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            <a href="{{ route('siswa.detail_materi') }}" class="bg-blue-400 text-white font-medium py-2 px-12 rounded-full hover:bg-blue-700 transition-colors">
                Lihat
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center">
            <h4 class="font-semibold text-blue-500 mb-4">Mata Pelajaran</h4>
            <div class="bg-yellow-400 w-32 h-32 rounded-full flex items-center justify-center mb-6">
                <img src="{{ asset('images/Book.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-emerald-700">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18c-2.305 0-4.408.867-6 2.292m0-14.25v14.25" />
                </img>
            </div>
            <a href="{{ route('siswa.detail_materi') }}" class="bg-blue-400 text-white font-medium py-2 px-12 rounded-full hover:bg-blue-700 transition-colors">
                Lihat
            </a>
        </div>

    </div>

@endsection