<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataMasterController extends Controller
{
    /**
     * Halaman utama Data Master - REDIRECT ke halaman yang benar
     * Menghindari penggunaan tab dan semester di URL
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'kelas');
        
        // Redirect ke route yang benar berdasarkan tab
        return match($tab) {
            'siswa' => redirect()->route('admin.data-master.list-siswa'),
            'guru' => redirect()->route('admin.data-master.list-guru'),
            'mapel' => redirect()->route('admin.data-master.list-mapel'),
            default => $this->indexKelas($request),
        };
    }

    /**
     * Halaman Kelas (default halaman data master)
     */
    private function indexKelas(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');

        // Ambil tahun ajaran
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranId && $tahunAjaranAktif) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
        }

        $data = [
            'tab' => 'kelas',
            'tahunAjaranList' => $tahunAjaranList,
            'tahunAjaranId' => $tahunAjaranId,
        ];

        $data['kelasList'] = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->withCount('siswa')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('Admin.dataMaster', $data);
    }

    /**
     * Halaman form create siswa baru (kosong)
     */
    public function createSiswa()
    {
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        
        // Ambil kelas dari tahun ajaran aktif saja
        $kelasList = Kelas::with('tahunAjaran')
            ->whereHas('tahunAjaran', function($query) {
                $query->where('status', 'Aktif');
            })
            ->orderBy('nama_kelas')
            ->get();
        
        return view('Admin.pendataanSiswa', compact('tahunAjaranList', 'kelasList'));
    }

    /**
     * Halaman form edit siswa
     */
    public function editSiswa($id)
    {
        $siswa = Siswa::with('kelas', 'user')->findOrFail($id);
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        
        // Ambil kelas dari tahun ajaran aktif saja
        $kelasList = Kelas::with('tahunAjaran')
            ->whereHas('tahunAjaran', function($query) {
                $query->where('status', 'Aktif');
            })
            ->orderBy('nama_kelas')
            ->get();
        
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
            'email' => 'required|email|unique:users,email' . ($id ? "," . Siswa::find($id)?->user_id . ",id" : ''),
            'agama' => 'required|string',
            'golongan_darah' => 'nullable|string|max:5',
            'kelas_id' => 'nullable|exists:kelas,id_kelas',
            'password' => 'required|string|min:8',
        ];

        $validated = $request->validate($rules, [
            'nama_lengkap.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'nis.unique' => 'NIS sudah terdaftar',
            'nisn.unique' => 'NISN sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        try {
            DB::beginTransaction();

            if ($id) {
                // Update
                $siswa = Siswa::findOrFail($id);
                $user = User::findOrFail($siswa->user_id);
                
                $userData = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ];

                $user->update($userData);

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
                    'kelas_id' => $validated['kelas_id'] ?? null,
                ]);

                // Update siswa_kelas jika kelas berubah
                if ($validated['kelas_id']) {
                    $kelas = Kelas::findOrFail($validated['kelas_id']);
                    
                    // Hapus kelas sebelumnya untuk tahun ajaran yang sama
                    SiswaKelas::where('siswa_id', $siswa->id_siswa)
                        ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                        ->where('status', 'Aktif')
                        ->delete();
                    
                    // Insert record baru
                    SiswaKelas::create([
                        'siswa_id' => $siswa->id_siswa,
                        'kelas_id' => $validated['kelas_id'],
                        'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
                        'status' => 'Aktif',
                        'tanggal_masuk' => now(),
                    ]);
                }

                $message = 'Data siswa berhasil diperbarui';
            } else {
                // Create
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 'siswa',
                ]);

                $siswa = Siswa::create([
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
                    'kelas_id' => $validated['kelas_id'] ?? null,
                ]);

                // Insert ke siswa_kelas jika ada kelas dipilih
                if ($validated['kelas_id']) {
                    $kelas = Kelas::findOrFail($validated['kelas_id']);
                    
                    SiswaKelas::create([
                        'siswa_id' => $siswa->id_siswa,
                        'kelas_id' => $validated['kelas_id'],
                        'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
                        'status' => 'Aktif',
                        'tanggal_masuk' => now(),
                    ]);
                }

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
     * Halaman form create guru baru (kosong)
     */
    public function createGuru()
    {
        return view('Admin.pendataanGuru');
    }

    /**
     * Halaman form edit guru
     */
    public function editGuru($id)
    {
        $guru = Guru::with('user')->findOrFail($id);
        
        return view('Admin.pendataanGuru', compact('guru'));
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
            'email' => 'required|email|unique:users,email' . ($id ? "," . Guru::find($id)?->user_id . ",id" : ''),
            'agama' => 'required|string',
            'golongan_darah' => 'nullable|string|max:5',
            'password' => 'required|string|min:8',
        ];

        $validated = $request->validate($rules, [
            'nama_lengkap.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.unique' => 'Email sudah terdaftar',
            'nip.unique' => 'NIP sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        try {
            DB::beginTransaction();

            if ($id) {
                // Update
                $guru = Guru::findOrFail($id);
                $user = User::findOrFail($guru->user_id);
                
                $userData = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                ];

                $user->update($userData);

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
                ]);

                $message = 'Data guru berhasil diperbarui';
            } else {
                // Create
                $user = User::create([
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
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
     * Detail kelas - Redirect ke detail kelas tahun ajaran
     */
    public function detailKelasSiswa($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        return redirect()->route('admin.kelas.show', [$kelas->tahun_ajaran_id, $kelasId]);
    }

    /**
     * Detail kelas - Redirect ke detail kelas tahun ajaran
     */
    public function detailKelasGuru($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        return redirect()->route('admin.kelas.show', [$kelas->tahun_ajaran_id, $kelasId]);
    }

    /**
     * Detail kelas - Redirect ke detail kelas tahun ajaran
     */
    public function detailKelasMapel($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        return redirect()->route('admin.kelas.show', [$kelas->tahun_ajaran_id, $kelasId]);
    }

    /**
     * Tampilan list siswa (semua kelas) dengan filter
     * Menggunakan view_siswa_kelas untuk data konsisten
     */
    public function listSiswa(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');
        $kelasId = $request->get('kelas');

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        // Default ke tahun ajaran aktif
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran ?? $tahunAjaranList->first()?->id_tahun_ajaran;
        }

        // Ambil daftar kelas berdasarkan tahun ajaran
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
        
        // Query menggunakan database view untuk data konsisten
        $query = DB::table('view_siswa_kelas');
        
        // Filter by tahun ajaran
        if ($tahunAjaranId) {
            $query->where('tahun_ajaran_id', $tahunAjaranId);
        }

        // Filter by kelas
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }
        
        $siswaList = $query->orderBy('nama_lengkap')->get();
        
        return view('Admin.dataMaster_Siswa', compact('siswaList', 'tahunAjaranList', 'tahunAjaranId', 'kelasId', 'kelasList'));
    }

    /**
     * Tampilan list guru (semua) dengan filter
     * Menggunakan view_guru_mengajar untuk data konsisten
     */
    public function listGuru(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');
        $kelasId = $request->get('kelas');

        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        // Default ke tahun ajaran aktif
        if (!$tahunAjaranId) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran ?? $tahunAjaranList->first()?->id_tahun_ajaran;
        }

        // Ambil daftar kelas berdasarkan tahun ajaran
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        // Tampilkan semua guru tanpa filter mata pelajaran
        // Karena 1 guru bisa mengajar banyak mapel, tidak perlu view untuk menghindari duplikasi
        $guruList = Guru::orderBy('nama_lengkap', 'asc')->get();

        return view('Admin.dataMaster_Guru', compact('guruList', 'tahunAjaranList', 'tahunAjaranId', 'kelasId', 'kelasList'));
    }

    /**
     * Tampilan list mapel
     * Menggunakan view_mapel_diajarkan untuk data konsisten
     */
    public function listMapel()
    {
        $tahunAjaranList = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaranId = request('tahun_ajaran', $tahunAjaranAktif->id_tahun_ajaran ?? $tahunAjaranList->first()->id_tahun_ajaran ?? null);
        $kelasId = request('kelas', '');

        // Query kelas berdasarkan tahun ajaran
        $kelasList = Kelas::where('tahun_ajaran_id', $tahunAjaranId)->orderBy('nama_kelas')->get();

        // Query menggunakan database view untuk data konsisten
        if ($kelasId || $tahunAjaranId) {
            // Filter: Mapel yang diajarkan di kelas/tahun ajaran tertentu
            $query = DB::table('view_mapel_diajarkan')
                ->select(
                    'id_mapel', 
                    'kode_mapel', 
                    'nama_mapel', 
                    'kategori',
                    DB::raw('COUNT(DISTINCT guru_id) as guru_count')
                )
                ->groupBy('id_mapel', 'kode_mapel', 'nama_mapel', 'kategori');
            
            if ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            }
            
            if ($kelasId) {
                $query->where('kelas_id', $kelasId);
            }
            
            $mapelList = $query->orderBy('nama_mapel')->get();
        } else {
            // Tanpa filter: tampilkan semua mata pelajaran
            $mapelList = MataPelajaran::select('mata_pelajaran.*')
                ->selectRaw('(SELECT COUNT(DISTINCT guru_id) FROM jadwal_pelajaran 
                    WHERE jadwal_pelajaran.mapel_id = mata_pelajaran.id_mapel) as guru_count')
                ->orderBy('nama_mapel', 'asc')
                ->get();
        }

        return view('Admin.dataMaster_Mapel', compact('mapelList', 'tahunAjaranList', 'tahunAjaranId', 'kelasId', 'kelasList'));
    }
}
