@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        
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

        <div class="bg-white rounded-xl shadow-sm p-8">
            <form action="{{ isset($siswa) ? route('admin.data-master.siswa.update', $siswa->id_siswa) : route('admin.data-master.siswa.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($siswa))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Kelas</label>
                    <div class="md:col-span-2">
                        <select name="kelas_id" required class="w-full border {{ $errors->has('kelas_id') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}" {{ (old('kelas_id', isset($siswa) ? $siswa->kelas_id : '') == $kelas->id_kelas) ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
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
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $siswa->tgl_lahir ?? '') }}" required class="w-full border {{ $errors->has('tgl_lahir') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
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
                        <input type="email" name="email" value="{{ old('email', $siswa->email ?? '') }}" required placeholder="contoh@email.com" class="w-full border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
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

                <div class="flex justify-end pt-6">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-2">
                        <img src="{{ asset('images/save.png') }}" class="w-5 h-5">
                        Simpan Data Siswa
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection