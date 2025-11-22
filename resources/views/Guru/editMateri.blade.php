@extends('layouts.guru.app')

@section('content')

    <div class="flex items-center space-x-4 mb-8">
        <a href="{{ route('guru.detail_materi', $jadwal->id_jadwal) }}" class="w-12 h-12 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors" title="Kembali">
            <img src="{{ asset('images/mingcute_back-fill.png') }}" fill="none" viewBox="0 0 26 26" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </img>
        </a>
        <h2 class="text-3xl font-bold text-blue-500">Edit Materi - {{ $jadwal->mataPelajaran->nama_mapel }}</h2>
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

    <form action="{{ route('guru.update_materi', [$jadwal->id_jadwal, $item->id_materi]) }}" method="POST" enctype="multipart/form-data" data-validate>
        @csrf
        @method('PUT')
        <input type="hidden" name="id_pertemuan" value="{{ $item->pertemuan_id }}">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="space-y-8">

            <div class="flex justify-between items-start">
                <div>
                    <label class="block text-2xl font-bold text-slate-800 mb-4">Pertemuan</label>
                    <div class="bg-blue-100 text-blue-700 px-4 py-3 rounded-xl font-semibold">
                        Pertemuan {{ $item->pertemuan->pertemuan_ke }}
                    </div>
                </div>
                <button type="button" onclick="if(confirm('Apakah Anda yakin ingin menghapus materi ini?')) document.getElementById('delete-form').submit()" class="flex items-center space-x-2 bg-red-500 text-white font-bold text-lg px-6 py-3 rounded-full hover:bg-red-600 transition-colors shadow-md shadow-red-200" data-delete-confirm>
                    <img src="{{ asset('images/material-symbols_delete.png') }}" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.49 1.478l-.56-.095q-.53-.103-1.073-.178c-1.312-.178-2.654-.227-4.005-.227-1.35 0-2.693.05-4.005.227q-.542.075-1.073.178c-.186.036-.375.068-.56.095a.75.75 0 0 1-.49-1.478c1.287-.175 2.594-.346 3.878-.512v-.227c0-1.121.884-2.017 2.005-2.017h3.89c1.12 0 2.005.896 2.005 2.017ZM15.65 7.658a.75.75 0 0 0-.992.123l-1.418 1.419-1.418-1.419a.75.75 0 1 0-1.062 1.062l1.419 1.418-1.419 1.418a.75.75 0 1 0 1.062 1.062l1.418-1.419 1.418 1.419a.75.75 0 1 0 1.062-1.062l-1.419-1.418 1.419-1.418a.75.75 0 0 0-.123-.992Z" clip-rule="evenodd" />
                        <path d="M4.574 9.566c.032-.573.52-1.016 1.095-1.016h12.662c.575 0 1.063.443 1.095 1.016l.333 10.232c.056 1.73-1.324 3.202-3.055 3.202H7.296c-1.73 0-3.111-1.472-3.055-3.202l.333-10.232Z" />
                    </img>
                    <span>Hapus</span>
                </button>
            </div>

            <h3 class="text-2xl font-bold text-blue-600">Edit Berkas Berisi Materi</h3>

            <div>
                <label class="block text-xl font-bold text-slate-800 mb-4">Tipe Berkas</label>
                <div class="bg-blue-50 px-4 py-3 rounded-lg">
                    <span class="text-slate-700 font-medium text-lg">Materi</span>
                </div>
            </div>

            <div>
                <label class="block text-xl font-bold text-slate-900 mb-4">File Saat Ini</label>
                
                <div class="border-2 border-blue-300 rounded-full py-3 px-6 flex items-center justify-between mb-4 max-w-lg">
                    <div class="flex items-center space-x-4">
                        <img src="{{ asset('images/bxs_file.png') }}" alt="Ikon File" class="w-8 h-8">
                        @php
                            $fileName = basename($item->file_path);
                            // Remove timestamp prefix (e.g., "1763218901_" from filename)
                            $displayName = preg_replace('/^\d+_/', '', $fileName);
                        @endphp
                        <span class="text-slate-800 font-medium">{{ $displayName }}</span>
                    </div>
                    <a href="{{ route('guru.download_materi', ['materi', $item->id_materi]) }}" class="text-blue-500 hover:text-blue-700 transition-colors" title="Download file">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </div>

                <label class="block text-lg font-medium text-slate-700 mb-2">Ganti File (Opsional)</label>
                <div id="drop-zone" class="mt-1 flex justify-center px-6 py-12 border-2 border-blue-500 border-dashed rounded-xl bg-slate-50/50 max-w-lg cursor-pointer hover:border-blue-600 hover:bg-blue-50 transition-all">
                    <div class="space-y-2 text-center">
                      <img src="{{ asset('images/Vector.png') }}" class="mx-auto h-10 w-10 text-blue-400">
                        <div class="text-sm text-blue-600 font-medium">
                            <label for="file-upload" class="cursor-pointer">
                                <span>Upload file baru</span>
                            </label>
                            <input id="file-upload" name="file" type="file" class="sr-only" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                        </div>
                    </div>
                </div>
                <!-- Preview File -->
                <div id="file-preview" class="mt-4 hidden">
                    <div class="flex items-center justify-between p-4 bg-blue-50 border-2 border-blue-200 rounded-xl max-w-lg">
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
                <label for="judul" class="block text-xl font-bold text-slate-900 mb-2">Judul Materi</label>
                <input type="text" name="judul" id="judul" class="w-full max-w-lg border-2 border-blue-300 rounded-lg py-3 px-4 text-slate-700 placeholder:text-slate-500 focus:outline-none focus:border-blue-500" value="{{ old('judul', $item->judul_materi) }}" required>
            </div>

            <div>
                <label for="deskripsi" class="block text-xl font-bold text-slate-900 mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="w-full max-w-lg border-2 border-blue-300 rounded-xl py-3 px-4 text-slate-700 placeholder:text-slate-500 focus:outline-none focus:border-blue-500 resize-none" required>{{ old('deskripsi', $item->deskripsi_materi) }}</textarea>
            </div>

        </div>
    </div>

    <div class="mt-8 pt-6 border-t border-slate-200">
        <div class="flex justify-end">
            <button type="submit" class="flex items-center space-x-2 bg-green-500 text-white font-medium px-6 py-2 rounded-full hover:bg-green-600 transition-colors">
                <img src="{{ asset('images/save.png') }}" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </img>
                <span>Update</span>
            </button>
        </div>
    </div>
    </form>

    <form id="delete-form" action="{{ route('guru.delete_materi', [$jadwal->id_jadwal, 'materi', $item->id_materi]) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
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
                
                // Update file info
                fileName.textContent = '';
                fileSize.textContent = '';
                
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
            fileInput.value = '';
            fileName.textContent = '';
            fileSize.textContent = '';
            dropZone.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }
    </script>

    <script src="{{ asset('js/materi-handler.js') }}"></script>

@endsection