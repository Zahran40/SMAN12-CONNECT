@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        
        <div>
            <h3 class="text-xl font-bold text-blue-400">Form Data Diri Guru</h3>
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
            <form action="{{ isset($guru) ? route('admin.data-master.guru.update', $guru->id_guru) : route('admin.data-master.guru.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($guru))
                    @method('PUT')
                @endif
                
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Nama</label>
                    <div class="w-full md:w-2/3">
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $guru->nama_lengkap ?? '') }}" required placeholder="Nama Lengkap Guru" class="w-full border {{ $errors->has('nama_lengkap') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 text-right-input focus:outline-none focus:border-blue-500">
                        @error('nama_lengkap')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Mata Pelajaran</label>
                    <div class="w-full md:w-2/3">
                        <select name="mapel_id" required class="w-full border {{ $errors->has('mapel_id') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapelList as $mapel)
                                <option value="{{ $mapel->id_mapel }}" {{ (old('mapel_id', isset($guru) ? $guru->mapel_id : '') == $mapel->id_mapel) ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Tanggal Lahir</label>
                    <div class="w-full md:w-2/3">
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $guru->tgl_lahir ?? '') }}" required class="w-full border {{ $errors->has('tgl_lahir') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('tgl_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Tempat Lahir</label>
                    <div class="w-full md:w-2/3">
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $guru->tempat_lahir ?? '') }}" required placeholder="Kota Kelahiran" class="w-full border {{ $errors->has('tempat_lahir') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('tempat_lahir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-2">
                    <label class="text-slate-800 font-medium w-1/3 mt-2">Alamat</label>
                    <div class="w-full md:w-2/3">
                        <textarea name="alamat" rows="2" required placeholder="Alamat Lengkap" class="w-full border {{ $errors->has('alamat') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">{{ old('alamat', $guru->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Jenis Kelamin</label>
                    <div class="w-full md:w-2/3">
                        <select name="jenis_kelamin" required class="w-full border {{ $errors->has('jenis_kelamin') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih...</option>
                            <option value="Laki-laki" {{ (old('jenis_kelamin', isset($guru) ? $guru->jenis_kelamin : '') == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ (old('jenis_kelamin', isset($guru) ? $guru->jenis_kelamin : '') == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">NIS / NIP</label>
                    <div class="w-full md:w-2/3">
                        <input type="text" name="nip" value="{{ old('nip', $guru->nip ?? '') }}" required placeholder="1773832119" class="w-full border {{ $errors->has('nip') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('nip')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">No Handphone</label>
                    <div class="w-full md:w-2/3">
                        <input type="tel" name="no_telepon" value="{{ old('no_telepon', $guru->no_telepon ?? '') }}" required placeholder="+62..." class="w-full border {{ $errors->has('no_telepon') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('no_telepon')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Email</label>
                    <div class="w-full md:w-2/3">
                        <input type="email" name="email" value="{{ old('email', $guru->email ?? '') }}" required placeholder="example@gmail.com" class="w-full border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Agama</label>
                    <div class="w-full md:w-2/3">
                        <select name="agama" required class="w-full border {{ $errors->has('agama') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ (old('agama', isset($guru) ? $guru->agama : '') == 'Islam') ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ (old('agama', isset($guru) ? $guru->agama : '') == 'Kristen') ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ (old('agama', isset($guru) ? $guru->agama : '') == 'Katolik') ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ (old('agama', isset($guru) ? $guru->agama : '') == 'Hindu') ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ (old('agama', isset($guru) ? $guru->agama : '') == 'Buddha') ? 'selected' : '' }}>Buddha</option>
                        </select>
                        @error('agama')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Golongan Darah</label>
                    <div class="w-full md:w-2/3">
                        <select name="golongan_darah" class="w-full border {{ $errors->has('golongan_darah') ? 'border-red-500' : 'border-slate-300' }} rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A" {{ (old('golongan_darah', isset($guru) ? $guru->golongan_darah : '') == 'A') ? 'selected' : '' }}>A</option>
                            <option value="B" {{ (old('golongan_darah', isset($guru) ? $guru->golongan_darah : '') == 'B') ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ (old('golongan_darah', isset($guru) ? $guru->golongan_darah : '') == 'AB') ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ (old('golongan_darah', isset($guru) ? $guru->golongan_darah : '') == 'O') ? 'selected' : '' }}>O</option>
                        </select>
                        @error('golongan_darah')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-6">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-full shadow-lg transition-all transform hover:scale-105 flex items-center gap-2">
                        <img src="{{ asset('images/save.png') }}" class="w-5 h-5">
                        Simpan Data Guru
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection