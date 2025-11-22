@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-4 sm:mb-6">
        <a href="{{ route('guru.detail_materi', $tugas->jadwal_id) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Detail Tugas</h2>
    </div>

   

    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-6 sm:mb-8">
        
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/bxs_file.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125V6a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </img>
                <h3 class="text-2xl font-bold text-slate-800">{{ $tugas->judul_tugas }}</h3>
            </div>
            <a href="{{ route('guru.edit_tugas', $tugas->id_tugas) }}" class="flex items-center space-x-2 bg-blue-500 text-white font-medium px-4 py-1.5 sm:px-5 sm:py-2 rounded-full hover:bg-blue-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                    <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                    <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                </svg>
                <span>Edit Tugas</span>
            </a>
        </div>

        <div class="flex justify-between items-center mb-5">
            <span class="text-sm font-medium text-slate-600">Pertemuan {{ $tugas->pertemuan->nomor_pertemuan ?? '-' }}</span>
            <div class="flex items-center space-x-1.5 text-sm font-medium text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a1 1 0 0 0 0 2v3a1 1 0 0 0 2 0v-3a1 1 0 0 0-2 0Z" clip-rule="evenodd" />
                </svg>
                <span>Ditutup {{ $tugas->waktu_ditutup ? \Carbon\Carbon::parse($tugas->waktu_ditutup)->isoFormat('dddd, D MMMM YYYY HH:mm') : '-' }}</span>
            </div>
        </div>

        @if($tugas->file_path)
        <div class="border border-slate-200 rounded-lg p-4 mb-5">
            <div class="flex items-center justify-between">
                <span class="text-base font-semibold text-slate-700">{{ basename($tugas->file_path) }}</span>
                <a href="{{ route('guru.download_materi', ['type' => 'tugas', 'id' => $tugas->id_tugas]) }}" class="text-blue-500 hover:text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                        <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                    </svg>
                </a>
            </div>
        </div>
        @endif

        @if($tugas->deskripsi_tugas)
        <div class="border border-slate-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-slate-700 mb-1">Deskripsi Tugas:</h4>
            <p class="text-sm text-slate-500 whitespace-pre-wrap">{{ $tugas->deskripsi_tugas }}</p>
        </div>
        @endif
    </div>


    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 pb-4">
            <h3 class="text-2xl font-bold text-slate-800">Daftar Siswa ({{ $siswaList->count() }})</h3>
        </div>
    
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50 border-y border-slate-200">
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-16">No</th>
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-48">Nama</th>
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-32">NIS</th>
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-32">Teks Jawaban</th>
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-48">File</th>
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-32">Nilai</th>
                        <th scope="col" class="py-4 px-6 text-left text-sm font-semibold text-blue-500 w-64">Komentar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($siswaList as $index => $item)
                    <tr>
                        <td class="px-6 py-4">
                            <span class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 font-bold rounded-lg text-sm">{{ $loop->iteration }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-700">{{ $item['siswa']->nama_lengkap }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $item['siswa']->nis }}</td>
                        <td class="px-6 py-4">
                            @if($item['detail_tugas'] && $item['detail_tugas']->teks_jawaban)
                                <button onclick="showJawaban{{ $loop->iteration }}()" class="text-blue-600 hover:text-blue-800 text-sm underline">
                                    Lihat Jawaban
                                </button>
                                <div id="jawaban{{ $loop->iteration }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="this.classList.add('hidden')">
                                    <div class="bg-white rounded-lg p-4 sm:p-6 max-w-2xl w-full mx-4" onclick="event.stopPropagation()">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-bold text-slate-800">Jawaban - {{ $item['siswa']->nama_lengkap }}</h3>
                                            <button onclick="document.getElementById('jawaban{{ $loop->iteration }}').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                        <div class="bg-gray-50 rounded p-4 text-sm text-slate-700 whitespace-pre-wrap max-h-96 overflow-y-auto">
                                            {{ $item['detail_tugas']->teks_jawaban }}
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    function showJawaban{{ $loop->iteration }}() {
                                        document.getElementById('jawaban{{ $loop->iteration }}').classList.remove('hidden');
                                    }
                                </script>
                            @else
                                <span class="text-sm text-slate-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item['detail_tugas'] && $item['detail_tugas']->file_path)
                                <a href="{{ asset('storage/' . $item['detail_tugas']->file_path) }}" target="_blank" class="flex items-center space-x-2 bg-white border-2 border-blue-200 rounded-lg py-2 px-3 hover:bg-blue-50 group max-w-xs">
                                    <div class="flex items-center space-x-2 min-w-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-600 flex-shrink-0">
                                            <path fill-rule="evenodd" d="M4 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8.343a1 1 0 0 0-.293-.707l-3.414-3.414A1 1 0 0 0 13.657 4H4Zm6 6a1 1 0 1 0-2 0v3.5a.5.5 0 0 1-1 0V8a1 1 0 1 0-2 0v3.5a.5.5 0 0 1-1 0V8a1 1 0 0 0-1 1v1.5a1.5 1.5 0 0 0 1.5 1.5h1.5a.5.5 0 0 1 1 0V8a1 1 0 0 0-1-1H4.5a.5.5 0 0 1 0-1h3.5a1 1 0 0 1 1 1v1.5a.5.5 0 0 1-1 0V8a1 1 0 1 0-2 0v3.5A2.5 2.5 0 0 0 7.5 14h1A2.5 2.5 0 0 0 11 11.5V8a1 1 0 0 0-1-1Z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm font-medium text-slate-700 truncate" title="{{ basename($item['detail_tugas']->file_path) }}">{{ basename($item['detail_tugas']->file_path) }}</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-500 flex-shrink-0">
                                        <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                        <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-sm text-slate-400 italic">Belum mengumpulkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item['detail_tugas'])
                                <div class="flex items-center space-x-2" id="nilai-container-{{ $loop->iteration }}">
                                    {{-- Display Mode --}}
                                    <div id="nilai-display-{{ $loop->iteration }}" class="{{ $item['detail_tugas']->nilai ? '' : 'hidden' }} flex items-center space-x-2">
                                        <span class="w-20 text-center border-2 border-green-400 bg-green-50 rounded-lg py-1.5 text-slate-700 font-bold">{{ $item['detail_tugas']->nilai }}</span>
                                        <button onclick="toggleNilaiEdit({{ $loop->iteration }})" class="text-blue-600 hover:text-blue-800 p-1 transition-colors" title="Edit Nilai">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                                <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    {{-- Edit Mode Form --}}
                                    <form id="nilai-edit-{{ $loop->iteration }}" action="{{ route('guru.update_nilai_tugas', $item['detail_tugas']->id_detail_tugas) }}" method="POST" class="{{ $item['detail_tugas']->nilai ? 'hidden' : '' }} flex items-center space-x-2">
                                        @csrf
                                        <input type="number" 
                                               name="nilai" 
                                               value="{{ $item['detail_tugas']->nilai }}" 
                                               min="1" 
                                               max="100"
                                               step="1"
                                               inputmode="numeric"
                                               pattern="[0-9]*"
                                               required
                                               class="w-20 text-center border-2 border-blue-400 rounded-lg py-1.5 text-slate-700 font-semibold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                               placeholder="1-100">
                                        <button type="submit" class="text-green-600 hover:text-green-800 p-1 transition-colors" title="Simpan Nilai">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        @if($item['detail_tugas']->nilai)
                                        <button type="button" onclick="toggleNilaiEdit({{ $loop->iteration }})" class="text-slate-400 hover:text-slate-600 p-1" title="Batal">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                            </svg>
                                        </button>
                                        @endif
                                    </form>
                                </div>
                                <script>
                                function toggleNilaiEdit(id) {
                                    const display = document.getElementById('nilai-display-' + id);
                                    const edit = document.getElementById('nilai-edit-' + id);
                                    display.classList.toggle('hidden');
                                    edit.classList.toggle('hidden');
                                    
                                    // Focus pada input saat edit mode
                                    if (!edit.classList.contains('hidden')) {
                                        const input = edit.querySelector('input[name="nilai"]');
                                        if (input) {
                                            input.focus();
                                            input.select(); // Select all untuk gampang replace
                                        }
                                    }
                                }
                                
                                // Validasi input nilai saat typing
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.querySelectorAll('input[name="nilai"]').forEach(function(input) {
                                        input.addEventListener('input', function(e) {
                                            let value = parseInt(this.value);
                                            
                                            // Hapus karakter non-numeric
                                            this.value = this.value.replace(/[^0-9]/g, '');
                                            
                                            // Validasi range 1-100
                                            if (value > 100) {
                                                this.value = '100';
                                            } else if (value < 1 && this.value !== '') {
                                                this.value = '1';
                                            }
                                        });
                                        
                                        // Validasi saat blur (kehilangan focus)
                                        input.addEventListener('blur', function(e) {
                                            if (this.value === '' || parseInt(this.value) < 1) {
                                                this.value = '';
                                                this.setCustomValidity('Nilai harus antara 1-100');
                                            } else if (parseInt(this.value) > 100) {
                                                this.value = '100';
                                            } else {
                                                this.setCustomValidity('');
                                            }
                                        });
                                    });
                                });
                                </script>
                            @else
                                <span class="text-sm text-slate-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($item['detail_tugas'])
                                <form action="{{ route('guru.update_komentar_tugas', $item['detail_tugas']->id_detail_tugas) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <textarea name="komentar_guru" rows="2" class="w-full text-sm border-2 border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500" placeholder="Tulis komentar...">{{ $item['detail_tugas']->komentar_guru }}</textarea>
                                    <button type="submit" class="text-xs bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors">💬 Simpan Komentar</button>
                                </form>
                            @else
                                <span class="text-sm text-slate-400 italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Tidak ada siswa di kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
@endsection


