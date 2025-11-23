@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6 sm:mb-8">
        <a href="{{ route('guru.detail_tugas', $tugas->id_tugas) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3lg sm:xl font-bold text-blue-500">Edit Tugas - {{ $tugas->jadwalPelajaran->mataPelajaran->nama_mapel }}</h2>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.update_tugas', $tugas->id_tugas) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
    <div class="bg-white rounded-lg sm:xl shadow-lg p-4 sm:p-6 md:p-8">
        
        <div class="space-y-6">

            <div>
                <label for="pertemuan" class="block text-xl font-bold text-slate-900 mb-4">Pilih Pertemuan</label>
                <div class="relative w-full max-w-xs">
                    <select id="pertemuan" name="id_pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-8 rounded-lg sm:xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required>
                        @foreach($pertemuans as $p)
                            <option value="{{ $p->id_pertemuan }}" {{ $tugas->pertemuan_id == $p->id_pertemuan ? 'selected' : '' }}>
                                Pertemuan {{ $p->pertemuan_ke }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-600">
                        <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <h3 class="text-2lg sm:xl font-bold text-blue-600">Informasi Tugas</h3>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">File Tugas Saat Ini</label>
                @if($tugas->file_path)
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 mb-4 max-w-lg sm:xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                            <span class="text-sm font-medium text-slate-700">{{ basename($tugas->file_path) }}</span>
                        </div>
                        <a href="{{ route('guru.download_materi', ['type' => 'tugas', 'id' => $tugas->id_tugas]) }}" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z" />
                                <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
                
                <label class="block text-lg font-bold text-slate-900 mb-4">Upload File Baru (Opsional)</label>
                <div id="drop-zone" class="mt-1 w-full max-w-lg sm:xl px-6 py-10 border-2 border-blue-300 border-dashed rounded-lg sm:xl bg-white hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer">
                    <div class="space-y-3 text-center">
                        <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10" alt="Icon Upload">
                        <label for="file-upload" class="block text-sm font-medium text-blue-500 hover:text-blue-600 cursor-pointer">
                            <span id="upload-text">Upload file baru (kosongkan jika tidak ingin mengganti)</span>
                            <input id="file-upload" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                        </label>
                    </div>
                </div>
                <div id="file-preview" class="mt-4 hidden">
                    <div class="flex items-center justify-between p-4 bg-blue-50 border-2 border-blue-200 rounded-lg sm:xl max-w-lg sm:xl">
                        <div class="flex items-center space-x-3">
                            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                            <div>
                                <p id="file-name" class="font-medium text-slate-700"></p>
                                <p id="file-size" class="text-xs text-slate-500"></p>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label for="judul" class="block text-lg font-bold text-slate-900 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                <input type="text" name="judul" id="judul" class="w-full max-w-lg border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500" placeholder="Judul tugas" required value="{{ old('judul', $tugas->judul_tugas) }}">
            </div>

            <div>
                <label for="deskripsi" class="block text-lg font-bold text-slate-900 mb-2">Deskripsi Tugas</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-lg border-2 border-blue-300 rounded-lg sm:xl py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 resize-none" placeholder="Deskripsi tugas (opsional)">{{ old('deskripsi', $tugas->deskripsi_tugas) }}</textarea>
            </div>

            @php
                $tanggalDibuka = $tugas->tanggal_dibuka ? \Carbon\Carbon::parse($tugas->tanggal_dibuka)->format('Y-m-d') : date('Y-m-d');
                $tanggalDitutup = $tugas->tanggal_ditutup ? \Carbon\Carbon::parse($tugas->tanggal_ditutup)->format('Y-m-d') : date('Y-m-d');
                $jamBuka = $tugas->jam_buka ?? '08:00';
                $jamTutup = $tugas->jam_tutup ?? '23:59';
                
                list($jamBukaHour, $jamBukaMinute) = explode(':', $jamBuka);
                list($jamTutupHour, $jamTutupMinute) = explode(':', $jamTutup);
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 max-w-lg sm:xl">
                <div>
                    <label class="block text-lg font-bold text-blue-400 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline mr-1">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                        </svg>
                        Tanggal Dibuka <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_dibuka" id="tanggal_dibuka" value="{{ old('tanggal_dibuka', $tanggalDibuka) }}" class="w-full border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 focus:outline-none focus:border-blue-500" min="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-lg font-bold text-blue-400 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline mr-1">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                        </svg>
                        Tanggal Ditutup <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_ditutup" id="tanggal_ditutup" value="{{ old('tanggal_ditutup', $tanggalDitutup) }}" class="w-full border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 focus:outline-none focus:border-blue-500" min="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            
            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">
                    Waktu <span class="text-base font-normal text-slate-500">(jam buka dan tutup)</span> <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-md">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-1">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                            </svg>
                            Jam Dibuka
                        </label>
                        <div class="flex gap-2">
                            <select id="jam_buka_hour" class="w-1/2 border-2 border-blue-300 rounded-lg py-2.5 px-3 text-slate-700 text-base font-semibold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                @for($h = 0; $h < 24; $h++)
                                    <option value="{{ sprintf('%02d', $h) }}" {{ $jamBukaHour == sprintf('%02d', $h) ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}</option>
                                @endfor
                            </select>
                            <select id="jam_buka_minute" class="w-1/2 border-2 border-blue-300 rounded-lg py-2.5 px-3 text-slate-700 text-base font-semibold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option value="00" {{ $jamBukaMinute == '00' ? 'selected' : '' }}>00</option>
                                <option value="15" {{ $jamBukaMinute == '15' ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $jamBukaMinute == '30' ? 'selected' : '' }}>30</option>
                                <option value="45" {{ $jamBukaMinute == '45' ? 'selected' : '' }}>45</option>
                            </select>
                            <input type="hidden" id="jam_buka" name="jam_buka" value="{{ $jamBuka }}" required>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-1">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z" clip-rule="evenodd" />
                            </svg>
                            Jam Ditutup
                        </label>
                        <div class="flex gap-2">
                            <select id="jam_tutup_hour" class="w-1/2 border-2 border-blue-300 rounded-lg py-2.5 px-3 text-slate-700 text-base font-semibold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-red-200">
                                @for($h = 0; $h < 24; $h++)
                                    <option value="{{ sprintf('%02d', $h) }}" {{ $jamTutupHour == sprintf('%02d', $h) ? 'selected' : '' }}>{{ sprintf('%02d', $h) }}</option>
                                @endfor
                            </select>
                            <select id="jam_tutup_minute" class="w-1/2 border-2 border-blue-300 rounded-lg py-2.5 px-3 text-slate-700 text-base font-semibold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-red-200">
                                <option value="00" {{ $jamTutupMinute == '00' ? 'selected' : '' }}>00</option>
                                <option value="15" {{ $jamTutupMinute == '15' ? 'selected' : '' }}>15</option>
                                <option value="30" {{ $jamTutupMinute == '30' ? 'selected' : '' }}>30</option>
                                <option value="45" {{ $jamTutupMinute == '45' ? 'selected' : '' }}>45</option>
                                <option value="59" {{ $jamTutupMinute == '59' ? 'selected' : '' }}>59</option>
                            </select>
                            <input type="hidden" id="jam_tutup" name="jam_tutup" value="{{ $jamTutup }}" required>
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 inline mr-1">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" />
                    </svg>
                    Anda bisa mengubah waktu untuk membuka kembali tugas yang sudah ditutup
                </p>
            </div>

        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex justify-end gap-3">
            <a href="{{ route('guru.detail_tugas', $tugas->id_tugas) }}" class="inline-flex items-center space-x-2 bg-slate-500 text-white font-medium px-6 py-2 rounded-full hover:bg-slate-600 transition-colors">
                <span>Batal</span>
            </a>
            <button type="submit" class="flex items-center space-x-2 bg-green-500 text-white font-medium px-6 py-2 rounded-full hover:bg-green-600 transition-colors">
                <img src="{{ asset('images/save.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </img>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </div>
    </form>

    <script>
        // Update hidden time inputs when selects change
        function updateJamBuka() {
            const hour = document.getElementById('jam_buka_hour').value;
            const minute = document.getElementById('jam_buka_minute').value;
            document.getElementById('jam_buka').value = hour + ':' + minute;
        }
        
        function updateJamTutup() {
            const hour = document.getElementById('jam_tutup_hour').value;
            const minute = document.getElementById('jam_tutup_minute').value;
            document.getElementById('jam_tutup').value = hour + ':' + minute;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Attach listeners
            document.getElementById('jam_buka_hour').addEventListener('change', updateJamBuka);
            document.getElementById('jam_buka_minute').addEventListener('change', updateJamBuka);
            document.getElementById('jam_tutup_hour').addEventListener('change', updateJamTutup);
            document.getElementById('jam_tutup_minute').addEventListener('change', updateJamTutup);
            
            // Initialize values
            updateJamBuka();
            updateJamTutup();
        });

        // Drag & Drop functionality
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-upload');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropZone.classList.add('border-blue-600', 'bg-blue-100');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-600', 'bg-blue-100');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                handleFiles(files);
            }
        }

        fileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                handleFiles(this.files);
            }
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                
                fileName.textContent = file.name;
                fileSize.textContent = `${fileSizeMB} MB`;
                
                dropZone.classList.add('hidden');
                filePreview.classList.remove('hidden');
            }
        }

        function removeFile() {
            fileInput.value = '';
            fileName.textContent = '';
            fileSize.textContent = '';
            dropZone.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }
    </script>

@endsection



