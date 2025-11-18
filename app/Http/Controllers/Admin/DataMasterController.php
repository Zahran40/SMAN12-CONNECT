<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataMasterController extends Controller
{
    /**
     * Halaman utama Data Master
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'kelas');
        $tahunAjaranId = $request->get('tahun_ajaran');
        $semester = $request->get('semester', 'Genap');
        $kelasId = $request->get('kelas');

        // Ambil tahun ajaran
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranId && $tahunAjaranAktif) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
        }

        $data = [
            'tab' => $tab,
            'tahunAjaranList' => $tahunAjaranList,
            'tahunAjaranId' => $tahunAjaranId,
            'semester' => $semester,
            'kelasId' => $kelasId,
        ];

        if ($tab === 'kelas' && $tahunAjaranId) {
            $data['kelasList'] = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
                ->withCount('siswa')
                ->orderBy('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        } elseif ($tab === 'siswa' && $tahunAjaranId) {
            // Ambil siswa berdasarkan kelas yang ada di tahun ajaran ini
            $kelasIds = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->pluck('id_kelas');
            $query = Siswa::whereIn('kelas_id', $kelasIds)->with('kelas');
            
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }
            
            $data['siswaList'] = $query->orderBy('nama_lengkap', 'asc')->get();
            $data['kelasList'] = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->get();
        } elseif ($tab === 'guru') {
            $data['guruList'] = Guru::with('mataPelajaran')->orderBy('nama_lengkap', 'asc')->get();
        } elseif ($tab === 'mapel') {
            $data['mapelList'] = MataPelajaran::withCount('guru')->orderBy('nama_mapel', 'asc')->get();
        }

        return view('Admin.dataMaster', $data);
    }

    /**
     * Halaman detail siswa untuk form input/edit
     */
    public function showSiswaForm($id = null)
    {
        $siswa = $id ? Siswa::with('kelas', 'user')->findOrFail($id) : null;
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        return view('Admin.pendataanSiswa', compact('siswa', 'tahunAjaranList', 'kelasList'));
    }

    /**
     * Simpan atau update data siswa
     */
    public function storeSiswa(Request $request, $id = null)
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nis' => 'required|string|max:50|unique:siswa,nis' . ($id ? ",$id,id_siswa" : ''),
            'nisn' => 'required|string|max:50|unique:siswa,nisn' . ($id ? ",$id,id_siswa" : ''),
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:siswa,email' . ($id ? ",$id,id_siswa" : ''),
            'agama' => 'required|string',
            'golongan_darah' => 'nullable|string|max:5',
            'kelas_id' => 'required|exists:kelas,id_kelas',
        ];

        $validated = $request->validate($rules, [
            'nama_lengkap.required' => 'Nama wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'nis.unique' => 'NIS sudah terdaftar',
            'nisn.unique' => 'NISN sudah terdaftar',
        ]);

        try {
            DB::beginTransaction();

            if ($id) {
                // Update
                $siswa = Siswa::findOrFail($id);
                $user = User::findOrFail($siswa->user_id);
                
                $user->update([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ]);

                $siswa->update([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'alamat' => $validated['alamat'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'nis' => $validated['nis'],
                    'nisn' => $validated['nisn'],
                    'no_telepon' => $validated['no_telepon'],
                    'email' => $validated['email'],
                    'agama' => $validated['agama'],
                    'golongan_darah' => $request->golongan_darah,
                    'kelas_id' => $validated['kelas_id'],
                ]);

                $message = 'Data siswa berhasil diperbarui';
            } else {
                // Create - Auto-generate password default: siswa123
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa',
                ]);

                Siswa::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'alamat' => $validated['alamat'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'nis' => $validated['nis'],
                    'nisn' => $validated['nisn'],
                    'no_telepon' => $validated['no_telepon'],
                    'email' => $validated['email'],
                    'agama' => $validated['agama'],
                    'golongan_darah' => $request->golongan_darah,
                    'kelas_id' => $validated['kelas_id'],
                ]);

                $message = 'Data siswa berhasil ditambahkan';
            }

            DB::commit();
            return redirect()->route('admin.data-master.index', ['tab' => 'siswa'])
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Halaman detail guru untuk form input/edit
     */
    public function showGuruForm($id = null)
    {
        $guru = $id ? Guru::with('mataPelajaran', 'user')->findOrFail($id) : null;
        $mapelList = MataPelajaran::orderBy('nama_mapel')->get();
        
        return view('Admin.pendataanGuru', compact('guru', 'mapelList'));
    }

    /**
     * Simpan atau update data guru
     */
    public function storeGuru(Request $request, $id = null)
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nip' => 'required|string|max:50|unique:guru,nip' . ($id ? ",$id,id_guru" : ''),
            'no_telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:guru,email' . ($id ? ",$id,id_guru" : ''),
            'agama' => 'required|string',
            'golongan_darah' => 'nullable|string|max:5',
            'mapel_id' => 'required|exists:mata_pelajaran,id_mapel',
        ];

        $validated = $request->validate($rules, [
            'nama_lengkap.required' => 'Nama wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'nip.unique' => 'NIP sudah terdaftar',
        ]);

        try {
            DB::beginTransaction();

            if ($id) {
                // Update
                $guru = Guru::findOrFail($id);
                $user = User::findOrFail($guru->user_id);
                
                $user->update([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ]);

                $guru->update([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'alamat' => $validated['alamat'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'nip' => $validated['nip'],
                    'no_telepon' => $validated['no_telepon'],
                    'email' => $validated['email'],
                    'agama' => $validated['agama'],
                    'golongan_darah' => $request->golongan_darah,
                    'mapel_id' => $validated['mapel_id'],
                ]);

                $message = 'Data guru berhasil diperbarui';
            } else {
                // Create - Auto-generate password default: guru123
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make('guru123'),
                    'role' => 'guru',
                ]);

                Guru::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'tgl_lahir' => $validated['tgl_lahir'],
                    'tempat_lahir' => $validated['tempat_lahir'],
                    'alamat' => $validated['alamat'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'nip' => $validated['nip'],
                    'no_telepon' => $validated['no_telepon'],
                    'email' => $validated['email'],
                    'agama' => $validated['agama'],
                    'golongan_darah' => $request->golongan_darah,
                    'mapel_id' => $validated['mapel_id'],
                ]);

                $message = 'Data guru berhasil ditambahkan';
            }

            DB::commit();
            return redirect()->route('admin.data-master.index', ['tab' => 'guru'])
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail siswa (read-only view)
     */
    public function detailSiswa($id)
    {
        $siswa = Siswa::with('kelas.tahunAjaran', 'user')->findOrFail($id);
        return view('Admin.detailSiswa', compact('siswa'));
    }

    /**
     * Detail guru (read-only view)
     */
    public function detailGuru($id)
    {
        $guru = Guru::with('mataPelajaran', 'user')->findOrFail($id);
        return view('Admin.detailGuru', compact('guru'));
    }

    /**
     * Hapus siswa
     */
    public function deleteSiswa($id)
    {
        try {
            DB::beginTransaction();

            $siswa = Siswa::findOrFail($id);
            $namaSiswa = $siswa->nama_lengkap;
            $userId = $siswa->user_id;

            $siswa->delete();
            User::find($userId)->delete();

            DB::commit();
            return redirect()->route('admin.data-master.index', ['tab' => 'siswa'])
                ->with('success', 'Data siswa "' . $namaSiswa . '" berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.data-master.index', ['tab' => 'siswa'])
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Hapus guru
     */
    public function deleteGuru($id)
    {
        try {
            DB::beginTransaction();

            $guru = Guru::findOrFail($id);
            $namaGuru = $guru->nama_lengkap;
            $userId = $guru->user_id;

            $guru->delete();
            User::find($userId)->delete();

            DB::commit();
            return redirect()->route('admin.data-master.index', ['tab' => 'guru'])
                ->with('success', 'Data guru "' . $namaGuru . '" berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.data-master.index', ['tab' => 'guru'])
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Detail kelas - Tab Siswa
     */
    public function detailKelasSiswa($kelasId)
    {
        $kelas = Kelas::with(['tahunAjaran', 'siswa.user'])->findOrFail($kelasId);
        $siswaList = $kelas->siswa;
        
        return view('Admin.dataMasterSiswa', compact('kelas', 'siswaList'));
    }

    /**
     * Detail kelas - Tab Guru
     */
    public function detailKelasGuru($kelasId)
    {
        $kelas = Kelas::with(['tahunAjaran'])->findOrFail($kelasId);
        // Ambil guru yang mengajar di kelas ini melalui jadwal
        $guruList = Guru::with('mataPelajaran')->get(); // Untuk sekarang ambil semua guru
        
        return view('Admin.dataMasterGuru', compact('kelas', 'guruList'));
    }

    /**
     * Detail kelas - Tab Mapel
     */
    public function detailKelasMapel($kelasId)
    {
        $kelas = Kelas::with(['tahunAjaran'])->findOrFail($kelasId);
        $mapelList = MataPelajaran::withCount('guru')->get();
        
        return view('Admin.dataMasterMapel', compact('kelas', 'mapelList'));
    }

    /**
     * Tampilan list siswa (semua kelas) dengan filter
     */
    public function listSiswa(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');
        $semester = $request->get('semester', 'Genap');
        $kelasId = $request->get('kelas');

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranId && $tahunAjaranAktif) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
        }

        $query = Siswa::with(['kelas.tahunAjaran', 'user']);
        
        if ($tahunAjaranId) {
            $kelasIds = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->pluck('id_kelas');
            $query->whereIn('kelas_id', $kelasIds);
        }
        
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }
        
        $siswaList = $query->orderBy('nama_lengkap', 'asc')->get();
        $kelasList = $tahunAjaranId ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->get() : collect();

        return view('Admin.dataMaster_Siswa', compact('siswaList', 'tahunAjaranList', 'tahunAjaranId', 'semester', 'kelasId', 'kelasList'));
    }

    /**
     * Tampilan list guru (semua) dengan filter
     */
    public function listGuru(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');
        $semester = $request->get('semester', 'Genap');
        $kelasId = $request->get('kelas');

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranId && $tahunAjaranAktif) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
        }

        $guruList = Guru::with('mataPelajaran')->orderBy('nama_lengkap', 'asc')->get();
        $kelasList = $tahunAjaranId ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->get() : collect();

        return view('Admin.dataMaster_Guru', compact('guruList', 'tahunAjaranList', 'tahunAjaranId', 'semester', 'kelasId', 'kelasList'));
    }

    /**
     * Tampilan list mapel dengan filter
     */
    public function listMapel(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');
        $semester = $request->get('semester', 'Genap');
        $kelasId = $request->get('kelas');

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranId && $tahunAjaranAktif) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
        }

        $mapelList = MataPelajaran::withCount('guru')->orderBy('nama_mapel', 'asc')->get();
        $kelasList = $tahunAjaranId ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->get() : collect();

        return view('Admin.dataMaster_Mapel', compact('mapelList', 'tahunAjaranList', 'tahunAjaranId', 'semester', 'kelasId', 'kelasList'));
    }
}

