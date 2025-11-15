@extends('layouts.admin.app')

@section('content')
    <div class="flex flex-col space-y-6">
        
        <div>
            <h3 class="text-xl font-bold text-slate-800">Form Data Diri Guru</h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-8">
            <form action="#" method="POST" class="space-y-6">
                @csrf
                
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Nama</label>
                    <input type="text" name="nama" placeholder="Nama Lengkap Guru" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 text-right-input focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" placeholder="Kota Kelahiran" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-2">
                    <label class="text-slate-800 font-medium w-1/3 mt-2">Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat Lengkap" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="">Pilih...</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">NIS / NIP</label>
                    <input type="number" name="nip" placeholder="1773832119" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">No Handphone</label>
                    <input type="tel" name="no_hp" placeholder="+62..." class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Email</label>
                    <input type="email" name="email" placeholder="example@gmail.com" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <label class="text-slate-800 font-medium w-1/3">Agama</label>
                    <select name="agama" class="w-full md:w-2/3 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500 bg-white">
                        <option value="">Pilih Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                    </select>
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