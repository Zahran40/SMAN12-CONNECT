@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-4 sm:space-y-6">
        
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-blue-400">Form Data Diri Siswa</h3>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <div class="font-bold mb-2">Terjadi kesalahan:</div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 md:p-8">
            <form action="{{ isset($siswa) ? route('admin.data-master.siswa.update', $siswa->id_siswa) : route('admin.data-master.siswa.store') }}" method="POST" class="space-y-6"autocomplete="off">
                @csrf
                @if(isset($siswa))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Nama</label>
                    <div class="md:col-span-2">
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $siswa->nama_lengkap ?? '') }}" required placeholder="Masukkan Nama Siswa" class="w-full border {{ $errors->has('nama_lengkap') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @error('nama_lengkap')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Tanggal Lahir</label>
                    <div class="md:col-span-2">
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $siswa->tgl_lahir ?? '') }}" required max="{{ date('Y-m-d') }}" class="w-full border {{ $errors->has('tgl_lahir') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('tgl_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Tempat Lahir</label>
                    <div class="md:col-span-2">
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $siswa->tempat_lahir ?? '') }}" required placeholder="Kota Kelahiran" class="w-full border {{ $errors->has('tempat_lahir') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                    <label class="font-bold text-slate-700 mt-2">Alamat</label>
                    <div class="md:col-span-2">
                        <textarea name="alamat" rows="3" required placeholder="Alamat Lengkap" class="w-full border {{ $errors->has('alamat') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">{{ old('alamat', $siswa->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Jenis Kelamin</label>
                    <div class="md:col-span-2">
                        <select name="jenis_kelamin" required class="w-full border {{ $errors->has('jenis_kelamin') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ (old('jenis_kelamin', isset($siswa) ? $siswa->jenis_kelamin : '') == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ (old('jenis_kelamin', isset($siswa) ? $siswa->jenis_kelamin : '') == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">NIS</label>
                    <div class="md:col-span-2">
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis ?? '') }}" required placeholder="Nomor Induk Siswa" class="w-full border {{ $errors->has('nis') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('nis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">NISN</label>
                    <div class="md:col-span-2">
                        <input type="text" name="nisn" value="{{ old('nisn', $siswa->nisn ?? '') }}" placeholder="Nomor Induk Siswa Nasional" class="w-full border {{ $errors->has('nisn') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('nisn')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">No Handphone</label>
                    <div class="md:col-span-2">
                        <input type="tel" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon ?? '') }}" required placeholder="+62..." class="w-full border {{ $errors->has('no_telepon') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('no_telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Email</label>
                    <div class="md:col-span-2">
                        <input type="email" name="email" value="{{ old('email') }}" autocomplete="off" required placeholder="contoh@email.com" class="w-full border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Password</label>
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input type="password" id="passwordSiswa" name="password" value="" autocomplete="new-password" required placeholder="Masukkan password" class="w-full border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 pr-10 focus:outline-none focus:border-blue-500">
                            <button type="button" onclick="togglePasswordField('passwordSiswa', 'eyeIconSiswa')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <svg id="eyeIconSiswa" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Minimal 8 karakter</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Agama</label>
                    <div class="md:col-span-2">
                        <select name="agama" required class="w-full border {{ $errors->has('agama') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ (old('agama', isset($siswa) ? $siswa->agama : '') == 'Islam') ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ (old('agama', isset($siswa) ? $siswa->agama : '') == 'Kristen') ? 'selected' : '' }}>Kristen Protestan</option>
                            <option value="Katolik" {{ (old('agama', isset($siswa) ? $siswa->agama : '') == 'Katolik') ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ (old('agama', isset($siswa) ? $siswa->agama : '') == 'Hindu') ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ (old('agama', isset($siswa) ? $siswa->agama : '') == 'Buddha') ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ (old('agama', isset($siswa) ? $siswa->agama : '') == 'Konghucu') ? 'selected' : '' }}>Konghucu</option>
                        </select>
                        @error('agama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Golongan Darah</label>
                    <div class="md:col-span-2">
                        <select name="golongan_darah" class="w-full border {{ $errors->has('golongan_darah') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A" {{ (old('golongan_darah', isset($siswa) ? $siswa->golongan_darah : '') == 'A') ? 'selected' : '' }}>A</option>
                            <option value="B" {{ (old('golongan_darah', isset($siswa) ? $siswa->golongan_darah : '') == 'B') ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ (old('golongan_darah', isset($siswa) ? $siswa->golongan_darah : '') == 'AB') ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ (old('golongan_darah', isset($siswa) ? $siswa->golongan_darah : '') == 'O') ? 'selected' : '' }}>O</option>
                        </select>
                        @error('golongan_darah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Informasi Kelas (Opsional saat create, bisa diisi lewat Detail Kelas) --}}
                <div class="border-t border-slate-200 pt-6 mt-4 sm:mt-6">
                    <h3 class="text-lg font-bold text-blue-600 mb-4">Informasi Kelas (Opsional)</h3>
                    <p class="text-sm text-slate-500 mb-4">
                        Anda bisa melewati bagian ini dan menambahkan siswa ke kelas nanti melalui halaman <strong>Detail Kelas</strong>
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <label class="font-bold text-slate-700">Kelas</label>
                        <div class="md:col-span-2">
                            <select name="kelas_id" class="w-full border {{ $errors->has('kelas_id') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                                <option value="">Tidak Assign Kelas (Bisa diatur nanti)</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id_kelas }}" {{ (old('kelas_id', isset($siswa) ? $siswa->kelas_id : '') == $kelas->id_kelas) ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }} - {{ $kelas->tahunAjaran->tahun_mulai }}/{{ $kelas->tahunAjaran->tahun_selesai }} {{ $kelas->tahunAjaran->semester }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-slate-500 mt-2">
                                <strong>Catatan:</strong> Siswa akan otomatis masuk ke tabel <code>siswa_kelas</code> untuk tracking historis kelas per tahun ajaran
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-2">
                        <img src="{{ asset('images/save.png') }}" class="w-5 h-5">
                        Simpan Data Siswa
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function togglePasswordField(inputId, iconId) {
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
@endsection
