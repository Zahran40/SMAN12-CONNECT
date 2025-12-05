@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center gap-4 mb-6 sm:mb-8">
        <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="shrink-0 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" class="w-6 h-6 sm:w-8 sm:h-8" alt="Back">
        </a>
        <h2 class="text-lg sm:text-2xl font-bold text-slate-800 leading-tight">Membuat Materi - {{ $jadwal->mataPelajaran->nama_mapel }}</h2>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4 sm:mb-6 text-sm sm:text-base">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.store_materi', $jadwal->id_jadwal) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-xl shadow-lg p-5 sm:p-8">
            
            <div class="space-y-6 sm:space-y-8">

                <div>
                    <label for="pertemuan" class="block text-base sm:text-xl font-bold text-slate-900 mb-2 sm:mb-4">Pilih Pertemuan</label>
                    <div class="relative w-full sm:max-w-xs">
                        <select id="pertemuan" name="id_pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-10 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500 transition-colors cursor-pointer" required>
                            <option value="">-- Pilih Pertemuan --</option>
                            @foreach($pertemuans as $p)
                                <option value="{{ $p->id_pertemuan }}">Pertemuan {{ $p->pertemuan_ke }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-600">
                            <svg class="fill-current h-5 w-5 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-blue-600 mb-4">Upload Berkas Materi</h3>
                    <div class="w-full">
                        <label class="block text-base sm:text-lg font-bold text-slate-900 mb-2 sm:mb-4">Upload Berkas</label>
                        
                        <div id="drop-zone" class="w-full sm:max-w-xl px-6 py-8 sm:py-10 border-2 border-blue-300 border-dashed rounded-xl bg-white hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer group">
                            <div class="space-y-3 text-center">
                                <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10 group-hover:scale-110 transition-transform" alt="Icon Upload">
                                <label for="file-upload" class="block text-sm sm:text-base font-medium text-blue-500 hover:text-blue-600 cursor-pointer">
                                    <span id="upload-text">Upload berkas materi anda</span>
                                    <input id="file-upload" name="file" type="file" class="sr-only" required accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                                </label>
                            </div>
                        </div>

                        <div id="file-preview" class="mt-4 hidden w-full sm:max-w-xl">
                            <div class="flex items-center justify-between p-3 sm:p-4 bg-blue-50 border-2 border-blue-200 rounded-xl w-full">
                                <div class="flex items-center space-x-3 overflow-hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-600 shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <div class="min-w-0">
                                        <div id="file-name" class="text-sm font-medium text-slate-700 truncate"></div>
                                        <div id="file-size" class="text-xs text-slate-500"></div>
                                    </div>
                                </div>
                                <button type="button" onclick="removeFile()" class="ml-2 text-red-500 hover:text-red-700 shrink-0 p-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="judul" class="block text-base sm:text-lg font-bold text-slate-900 mb-2">Judul Materi <span class="text-red-500">*</span></label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="w-full sm:max-w-xl border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 focus:outline-none focus:border-blue-500" placeholder="Masukkan judul materi" required>
                </div>

                <div>
                    <label for="deskripsi" class="block text-base sm:text-lg font-bold text-slate-900 mb-2">Deskripsi Materi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full sm:max-w-xl border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 focus:outline-none focus:border-blue-500" placeholder="Masukkan deskripsi materi (opsional)">{{ old('deskripsi') }}</textarea>
                </div>

            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-200">
            <div class="flex flex-col sm:flex-row justify-end w-full">
                <button type="submit" class="w-full sm:w-auto flex items-center justify-center space-x-2 bg-green-500 text-white font-medium px-8 py-3 rounded-xl hover:bg-green-600 transition-colors shadow-lg shadow-green-200">
                    <img src="{{ asset('images/save.png') }}" class="w-5 h-5" alt="Save Icon">
                    <span>Simpan Materi</span>
                </button>
            </div>
        </div>
    </form>

    <script>
        // JS Logic tetap sama, tidak disentuh
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