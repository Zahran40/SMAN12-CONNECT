@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-bold text-blue-400">Form Data Diri Siswa</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            <form action="#" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Nama</label>
                    <div class="md:col-span-2">
                        <input type="text" name="nama" placeholder="Masukkan Nama Siswa" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Tanggal Lahir</label>
                    <div class="md:col-span-2">
                        <input type="date" name="tanggal_lahir" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Tempat Lahir</label>
                    <div class="md:col-span-2">
                        <input type="text" name="tempat_lahir" placeholder="Kota Kelahiran" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
                    <label class="font-bold text-slate-700 mt-2">Alamat</label>
                    <div class="md:col-span-2">
                        <textarea name="alamat" rows="3" placeholder="Alamat Lengkap" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Jenis Kelamin</label>
                    <div class="md:col-span-2">
                        <select name="jenis_kelamin" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">NIS</label>
                    <div class="md:col-span-2">
                        <input type="number" name="nis" placeholder="Nomor Induk Siswa" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">NISN</label>
                    <div class="md:col-span-2">
                        <input type="number" name="nisn" placeholder="Nomor Induk Siswa Nasional" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">No Handphone</label>
                    <div class="md:col-span-2">
                        <input type="tel" name="no_hp" placeholder="+62..." class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Email</label>
                    <div class="md:col-span-2">
                        <input type="email" name="email" placeholder="contoh@email.com" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="font-bold text-slate-700">Agama</label>
                    <div class="md:col-span-2">
                        <select name="agama" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                            <option value="">Pilih Agama</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen Protestan</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
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