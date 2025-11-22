@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-6 sm:mb-8">
        <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3lg sm:xl font-bold text-slate-800">Membuat Materi - {{ $jadwal->mataPelajaran->nama_mapel }}</h2>
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

    <form action="{{ route('guru.store_materi', $jadwal->id_jadwal) }}" method="POST" enctype="multipart/form-data">
        @csrf
    <div class="bg-white rounded-lg sm:xl shadow-lg p-4 sm:p-6 md:p-8">
        
        <div class="space-y-6">

            <div>
                <label for="pertemuan" class="block text-xl font-bold text-slate-900 mb-4">Pilih Pertemuan</label>
                <div class="relative w-full max-w-xs">
                    <select id="pertemuan" name="id_pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-8 rounded-lg sm:xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required>
                        <option value="">-- Pilih Pertemuan --</option>
                        @foreach($pertemuans as $p)
                            <option value="{{ $p->id_pertemuan }}">Pertemuan {{ $p->pertemuan_ke }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-600">
                        <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <h3 class="text-2lg sm:xl font-bold text-blue-600">Upload Berkas Materi</h3>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Upload Berkas</label>
                <div id="drop-zone" class="mt-1 w-full max-w-lg sm:xl px-6 py-10 border-2 border-blue-300 border-dashed rounded-lg sm:xl bg-white hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer">
                    <div class="space-y-3 text-center">
                        <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10" alt="Icon Upload">
                        <label for="file-upload" class="block text-sm font-medium text-blue-500 hover:text-blue-600 cursor-pointer">
                            <span id="upload-text">Upload berkas materi anda</span>
                            <input id="file-upload" name="file" type="file" class="sr-only" required accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                        </label>
                    </div>
                </div>
                <!-- Preview File -->
                <div id="file-preview" class="mt-4 hidden">
                    <div class="flex items-center justify-between p-4 bg-blue-50 border-2 border-blue-200 rounded-lg sm:xl max-w-lg sm:xl">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <div>
                                <div id="file-name" class="text-sm font-medium text-slate-700"></div>
                                <div id="file-size" class="text-xs text-slate-500"></div>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label for="judul" class="block text-lg font-bold text-slate-900 mb-2">Judul Materi <span class="text-red-500">*</span></label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" class="w-full max-w-lg sm:xl border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 focus:outline-none focus:border-blue-500" placeholder="Masukkan judul materi" required>
            </div>

            <div>
                <label for="deskripsi" class="block text-lg font-bold text-slate-900 mb-2">Deskripsi Materi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-2lg sm:xl border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 focus:outline-none focus:border-blue-500" placeholder="Masukkan deskripsi materi (opsional)">{{ old('deskripsi') }}</textarea>
            </div>

        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex justify-end">
            <button type="submit" class="flex items-center space-x-2 bg-green-500 text-white font-medium px-6 py-2 rounded-full hover:bg-green-600 transition-colors">
                <img src="{{ asset('images/save.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </img>
                <span>Simpan Materi</span>
            </button>
        </div>
    </div>
    </form>

    <script>
        // Drag & Drop functionality
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('file-upload');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Highlight drop zone when item is dragged over it
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

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files.length > 0) {
                fileInput.files = files;
                handleFiles(files);
            }
        }

        // Handle file selection via input
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


