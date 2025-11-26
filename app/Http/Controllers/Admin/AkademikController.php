<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkademikController extends Controller
{
    /**
     * Halaman utama Akademik - Daftar Mata Pelajaran
     */
    public function index()
    {
        // Query mapel dengan count guru dan jadwal via relasi
        $mapelList = MataPelajaran::select('mata_pelajaran.*')
            ->selectRaw('(SELECT COUNT(DISTINCT guru_id) FROM jadwal_pelajaran WHERE jadwal_pelajaran.mapel_id = mata_pelajaran.id_mapel) as guru_count')
            ->selectRaw('(SELECT COUNT(*) FROM jadwal_pelajaran WHERE jadwal_pelajaran.mapel_id = mata_pelajaran.id_mapel) as jadwal_count')
            ->orderBy('nama_mapel')
            ->get();
        
        return view('Admin.akademik', compact('mapelList'));
    }

    /**
     * Form tambah mata pelajaran
     */
    public function createMapel()
    {
        return view('Admin.buatMapel');
    }

    /**
     * Simpan mata pelajaran baru
     */
    public function storeMapel(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel',
            'kategori' => 'required|in:Umum,Kelas X,MIPA,IPS,Bahasa,Mulok',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi',
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan',
            'kategori.required' => 'Kategori wajib dipilih',
        ]);

        try {
            // Simpan mata pelajaran (master data - tidak ada tahun ajaran)
            $mapel = MataPelajaran::create([
                'nama_mapel' => $validated['nama_mapel'],
                'kode_mapel' => $validated['kode_mapel'],
                'kategori' => $validated['kategori'],
            ]);

            return redirect()->route('admin.akademik.index')
                ->with('success', 'Mata pelajaran berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan mata pelajaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Detail mata pelajaran
     */
    public function detailMapel($id)
    {
        $mapel = MataPelajaran::with(['guru', 'jadwal.kelas', 'jadwal.guru', 'jadwal.tahunAjaran'])->findOrFail($id);
        
        return view('Admin.detailMapel', compact('mapel'));
    }

    /**
     * Form edit mata pelajaran
     */
    public function editMapel($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return view('Admin.editMapel', compact('mapel'));
    }

    /**
     * Update mata pelajaran
     */
    public function updateMapel(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kode_mapel' => 'required|string|max:20|unique:mata_pelajaran,kode_mapel,' . $id . ',id_mapel',
            'kategori' => 'required|in:Umum,Kelas X,MIPA,IPS,Bahasa,Mulok',
        ]);

        try {
            $mapel->update($validated);

            return redirect()->route('admin.akademik.mapel.show', $id)
                ->with('success', 'Mata pelajaran berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui mata pelajaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus mata pelajaran
     */
    public function deleteMapel($id)
    {
        try {
            $mapel = MataPelajaran::findOrFail($id);
            $namaMapel = $mapel->nama_mapel;

            // Cek apakah ada jadwal atau guru terkait
            $jumlahGuru = $mapel->guru()->count();
            $jumlahJadwal = $mapel->jadwal()->count();

            if ($jumlahGuru > 0 || $jumlahJadwal > 0) {
                return redirect()->route('admin.akademik.index')
                    ->with('error', 'Tidak dapat menghapus mata pelajaran yang memiliki guru atau jadwal terkait');
            }

            $mapel->delete();
            return redirect()->route('admin.akademik.index')
                ->with('success', 'Mata pelajaran "' . $namaMapel . '" berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.akademik.index')
                ->with('error', 'Gagal menghapus mata pelajaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman manajemen jadwal pelajaran
     */
    public function jadwal(Request $request)
    {
        $tahunAjaranId = $request->get('tahun_ajaran');
        $kelasId = $request->get('kelas');
        
        $tahunAjaranList = TahunAjaran::active()->orderBy('tahun_mulai', 'desc')->get();
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranId && $tahunAjaranAktif) {
            $tahunAjaranId = $tahunAjaranAktif->id_tahun_ajaran;
        }

        $kelasList = $tahunAjaranId ? Kelas::where('tahun_ajaran_id', $tahunAjaranId)->get() : collect();
        
        $jadwalList = collect();
        if ($kelasId) {
            $jadwalList = JadwalPelajaran::where('kelas_id', $kelasId)
                ->with(['mataPelajaran', 'guru', 'kelas'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('Admin.jadwal', compact('tahunAjaranList', 'tahunAjaranId', 'kelasList', 'kelasId', 'jadwalList'));
    }

    /**
     * Simpan jadwal pelajaran
     */
    public function storeJadwal(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id_kelas',
            'mapel_id' => 'required|exists:mata_pelajaran,id_mapel',
            'guru_id' => 'required|exists:guru,id_guru',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
        ]);

        try {
            // Validasi: kelas_id harus sesuai dengan tahun_ajaran_id yang dipilih
            $kelas = Kelas::findOrFail($validated['kelas_id']);
            if ($kelas->tahun_ajaran_id != $validated['tahun_ajaran_id']) {
                return back()->with('error', 'Kelas yang dipilih tidak sesuai dengan tahun ajaran. Pilih kelas dengan tahun ajaran ' . 
                    TahunAjaran::find($validated['tahun_ajaran_id'])->tahun_mulai . '/' . 
                    TahunAjaran::find($validated['tahun_ajaran_id'])->tahun_selesai)
                    ->withInput();
            }

            // Cek bentrok jadwal
            $bentrok = JadwalPelajaran::where('kelas_id', $validated['kelas_id'])
                ->where('hari', $validated['hari'])
                ->where(function($query) use ($validated) {
                    $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                                ->where('jam_selesai', '>=', $validated['jam_selesai']);
                        });
                })->exists();

            if ($bentrok) {
                return back()->with('error', 'Jadwal bentrok dengan jadwal lain pada hari dan waktu yang sama')
                    ->withInput();
            }

            JadwalPelajaran::create($validated);

            return back()->with('success', 'Jadwal berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateJadwal(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'kelas_id' => 'required|exists:kelas,id_kelas',
                'mapel_id' => 'required|exists:mata_pelajaran,id_mapel',
                'guru_id' => 'required|exists:guru,id_guru',
                'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id_tahun_ajaran',
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required|after:jam_mulai',
            ]);

            // Validasi: kelas_id harus sesuai dengan tahun_ajaran_id yang dipilih
            $kelas = Kelas::findOrFail($validated['kelas_id']);
            if ($kelas->tahun_ajaran_id != $validated['tahun_ajaran_id']) {
                return back()->with('error', 'Kelas yang dipilih tidak sesuai dengan tahun ajaran. Pilih kelas dengan tahun ajaran ' . 
                    TahunAjaran::find($validated['tahun_ajaran_id'])->tahun_mulai . '/' . 
                    TahunAjaran::find($validated['tahun_ajaran_id'])->tahun_selesai)
                    ->withInput();
            }

            $jadwal = JadwalPelajaran::findOrFail($id);

            $bentrok = JadwalPelajaran::where('kelas_id', $validated['kelas_id'])
                ->where('hari', $validated['hari'])
                ->where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
                ->where('id_jadwal', '!=', $id)
                ->where(function($query) use ($validated) {
                    $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function($q) use ($validated) {
                            $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                              ->where('jam_selesai', '>=', $validated['jam_selesai']);
                        });
                })->exists();

            if ($bentrok) {
                return back()->with('error', 'Jadwal bentrok dengan jadwal lain pada hari dan waktu yang sama')
                    ->withInput();
            }

            $jadwal->update($validated);

            return back()->with('success', 'Jadwal berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteJadwal($id)
    {
        try {
            $jadwal = JadwalPelajaran::findOrFail($id);
            $jadwal->delete();

            return back()->with('success', 'Jadwal berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus jadwal: ' . $e->getMessage());
        }
    }
}
