@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
       <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-slate-800">Tambah Berkas - {{ $jadwal->mataPelajaran->nama_mapel }}</h2>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.store_materi', $jadwal->id_jadwal) }}" method="POST" enctype="multipart/form-data" data-validate>
        @csrf
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="space-y-8">
            
            <div>
                <label for="pertemuan" class="block text-xl font-bold text-slate-900 mb-4">Pilih Pertemuan</label>
                <div class="relative w-full max-w-xs">
                    <select id="id_pertemuan" name="id_pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required>
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

            <h3 class="text-2xl font-bold text-blue-600">Berkas yang Sudah Diupload</h3>

            <!-- List File yang Sudah Ada -->
            <div class="space-y-3">
                @forelse($existingMateri->merge($existingTugas) as $item)
                    @php
                        $isMateri = isset($item->judul_materi);
                        $judul = $isMateri ? $item->judul_materi : $item->judul_tugas;
                        $file = $isMateri ? $item->file_materi : $item->file_tugas;
                        $type = $isMateri ? 'materi' : 'tugas';
                    @endphp
                    <a href="{{ route('guru.edit_materi', [$jadwal->id_jadwal, $type, $isMateri ? $item->id_materi : $item->id_tugas]) }}" 
                       class="border-2 border-blue-300 rounded-full p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors max-w-lg">
                        <div class="flex items-center space-x-4">
                            <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Ikon Folder" class="w-8 h-8">
                            <div>
                                <span class="text-slate-800 font-medium block">{{ $judul }}</span>
                                <span class="text-xs text-slate-500">Pertemuan {{ $item->pertemuan->pertemuan_ke }} - {{ ucfirst($type) }}</span>
                            </div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @empty
                    <div class="text-center py-4 text-slate-500">
                        <p>Belum ada berkas yang diupload</p>
                    </div>
                @endforelse
            </div>

            <hr class="border-t-2 border-slate-200">

            <h3 class="text-2xl font-bold text-blue-600">Upload Berkas Baru</h3>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Pilih Berkas</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="tipe_berkas" value="materi" class="hidden" checked>
                        <div class="w-6 h-6 rounded-full border-2 border-blue-400 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <span class="text-slate-800 font-medium text-lg">Materi</span>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="radio" name="tipe_berkas" value="tugas" class="hidden">
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                        </div>
                        <span class="text-slate-800 font-medium text-lg">Tugas</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Upload Berkas</label>
                <div id="drop-zone" class="mt-1 w-full max-w-xl px-6 py-10 border-2 border-blue-300 border-dashed rounded-xl bg-white hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer">
                    <div class="space-y-3 text-center">
                        <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10" alt="Icon Upload">
                        <label for="file-upload" class="block text-sm font-medium text-blue-500 hover:text-blue-600 cursor-pointer">
                            <span id="upload-text">Upload berkas anda</span>
                            <input id="file-upload" name="file" type="file" class="sr-only" required accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                        </label>
                    </div>
                </div>
                <!-- Preview File -->
                <div id="file-preview" class="mt-4 hidden">
                    <div class="flex items-center justify-between p-4 bg-blue-50 border-2 border-blue-200 rounded-xl max-w-xl">
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
                <label for="judul" class="block text-lg font-bold text-slate-900 mb-2">Judul Berkas</label>
                <input type="text" name="judul" id="judul" class="w-full max-w-lg border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500" placeholder="Judul materi atau tugas" required value="{{ old('judul') }}">
            </div>

            <div>
                <label for="deskripsi" class="block text-lg font-bold text-slate-900 mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-lg border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 resize-none" placeholder="Deskripsi materi atau tugas anda" required>{{ old('deskripsi') }}</textarea>
            </div>

            <div id="waktu-section" class="hidden">
                <label class="block text-lg font-bold text-slate-900 mb-4">
                    Waktu <span class="text-base font-normal text-slate-500">(khusus berkas tugas)</span>
                </label>
                <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-x-4 max-w-md">
                    <div>
                        <div class="mb-2 text-blue-500 text-sm">Dibuka</div>
                        <input type="time" name="jam_buka" value="{{ old('jam_buka', '08:00') }}" class="w-full text-center border-2 border-blue-300 rounded-lg py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="pt-7">
                        <div class="w-5 h-1.5 bg-blue-600 rounded-full"></div>
                    </div>
                    <div>
                        <div class="mb-2 text-blue-400 text-sm">Ditutup</div>
                         <input type="time" name="jam_tutup" value="{{ old('jam_tutup', '23:59') }}" class="w-full text-center border-2 border-blue-300 rounded-lg py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex justify-end gap-3">
            <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="inline-flex items-center space-x-2 bg-slate-500 text-white font-medium px-6 py-2 rounded-full hover:bg-slate-600 transition-colors">
                <span>Batal</span>
            </a>
            <button type="submit" class="inline-flex items-center space-x-2 bg-green-500 text-white font-medium px-6 py-2 rounded-full hover:bg-green-600 transition-colors shadow-lg shadow-green-100">
                <img src="{{ asset('images/save.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <span>Simpan Berkas</span>
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
        const uploadText = document.getElementById('upload-text');

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
                // Get the first file only
                const file = files[0];
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                
                // Update file info (force clear first to avoid duplication)
                fileName.textContent = '';
                fileSize.textContent = '';
                
                // Set new values
                setTimeout(() => {
                    fileName.textContent = file.name;
                    fileSize.textContent = `${fileSizeMB} MB`;
                }, 10);
                
                // Show preview, hide drop zone
                dropZone.classList.add('hidden');
                filePreview.classList.remove('hidden');
            }
        }

        function removeFile() {
            // Clear file input
            fileInput.value = '';
            
            // Reset preview content
            fileName.textContent = '';
            fileSize.textContent = '';
            
            // Show drop zone, hide preview
            dropZone.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }
    </script>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection
                <label for="pertemuan" class="block text-xl font-bold text-slate-900 mb-4">Pilih Pertemuan</label>
                <div class="relative w-full max-w-xs">
                    <select id="pertemuan" name="pertemuan" class="block w-full appearance-none bg-white border-2 border-blue-300 text-slate-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                        <option>Pertemuan 1</option>
                        <option>Pertemuan 2</option>
                        <option selected>Pertemuan 3</option>
                        <option>Pertemuan 4</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-blue-600">
                        <svg class="fill-current h-6 w-6" weui_folder-filled.png viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <h3 class="text-2xl font-bold text-blue-600">Upload Berkas Berisi Materi atau Tugas</h3>

            <a href="{{ route('guru.edit_materi') }}" class="border-2 border-blue-300 rounded-full p-4 flex items-center justify-between cursor-pointer hover:bg-slate-50 transition-colors max-w-lg">
                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/weui_folder-filled.png') }}" alt="Ikon Folder" class="w-8 h-8">
                    <span class="text-slate-800 font-medium">Nama Tugas/Materi</span>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-6 h-6 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Pilih Berkas</label>
                <div class="space-y-3">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full border-2 border-blue-400 flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        </div>
                        <span class="text-slate-800 font-medium text-lg">Materi</span>
                         <input type="radio" name="tipe_berkas" value="materi" class="hidden" checked>
                    </label>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <div class="w-6 h-6 rounded-full border-2 border-slate-300"></div>
                        <span class="text-slate-800 font-medium text-lg">Tugas</span>
                        <input type="radio" name="tipe_berkas" value="tugas" class="hidden">
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">Upload Berkas</label>
                <div class="mt-1 w-full max-w-xl px-6 py-10 border-2 border-blue-300 border-dashed rounded-xl bg-white">
                    <div class="space-y-3 text-center">
                        <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10" alt="Icon Upload">
                        <label for="file-upload" class="block text-sm font-medium text-blue-500 hover:text-blue-600 cursor-pointer">
                            Upload berkas anda
                            <input id="file-upload" name="file-upload" type="file" class="sr-only">
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <label for="nama_berkas" class="block text-lg font-bold text-slate-900 mb-2">Nama Berkas</label>
                <input type="text" name="nama_berkas" id="nama_berkas" class="w-full max-w-lg border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500" placeholder="Maksimal 10 Karakter">
            </div>

            <div>
                <label for="deskripsi" class="block text-lg font-bold text-slate-900 mb-2">Deskripsi Materi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-lg border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-blue-500 resize-none" placeholder="Deskripsi materi atau tugas anda"></textarea>
            </div>

            <div>
                <label class="block text-lg font-bold text-slate-900 mb-4">
                    Waktu <span class="text-base font-normal text-slate-500">(khusus berkas tugas)</span>
                </label>
                <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-x-4 max-w-md">
                    <div>
                        <div class="mb-2 text-blue-500 text-sm">Dibuka</div>
                        <input type="text" value="00:00" class="w-full text-center border-2 border-blue-300 rounded-lg py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="pt-7">
                        <div class="w-5 h-1.5 bg-blue-600 rounded-full"></div>
                    </div>
                    <div>
                        <div class="mb-2 text-blue-400 text-sm">Ditutup</div>
                         <input type="text" value="00:00" class="w-full text-center border-2 border-blue-300 rounded-lg py-3 text-slate-600 focus:outline-none focus:border-blue-500">
                    </div>
                </div>
            </div>

            <div class="mt-12!">
                <a href="{{ route('guru.detail_tugas') }}" class="inline-flex items-center space-x-3 bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl hover:bg-blue-600 transition-colors shadow-lg shadow-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Berkas</span>
                </a>
            </div>

        </div>
    </div>

@endsection