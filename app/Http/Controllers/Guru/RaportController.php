<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Raport;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RaportController extends Controller
{
    /**
     * Halaman Nilai Raport - Pilih Mata Pelajaran
     */
    public function index()
    {
        $guru = Auth::user()->guru;
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        // Tentukan tahun ajaran mana yang digunakan untuk query jadwal
        // Jika yang aktif adalah Genap, cari jadwal dari Ganjil (karena jadwal dibuat di Ganjil)
        $tahunAjaranForQuery = $tahunAjaranAktif->id_tahun_ajaran;
        
        if ($tahunAjaranAktif->semester === 'Genap') {
            $semesterGanjil = TahunAjaran::where('tahun_mulai', $tahunAjaranAktif->tahun_mulai)
                ->where('tahun_selesai', $tahunAjaranAktif->tahun_selesai)
                ->where('semester', 'Ganjil')
                ->first();
            
            if ($semesterGanjil) {
                $tahunAjaranForQuery = $semesterGanjil->id_tahun_ajaran;
            }
        }
        
        // Ambil semua jadwal mengajar guru (unique per mapel dan kelas)
        $jadwalList = JadwalPelajaran::where('guru_id', $guru->id_guru)
            ->where('tahun_ajaran_id', $tahunAjaranForQuery)
            ->with(['mataPelajaran', 'kelas'])
            ->get()
            ->unique(function ($item) {
                return $item->mapel_id . '-' . $item->kelas_id;
            });
        
        return view('Guru.raport', compact('jadwalList', 'tahunAjaranAktif'));
    }

    /**
     * Halaman Input Nilai - Daftar Siswa per Kelas & Mapel
     */
    public function inputNilai($jadwalId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        $semesterAktif = $tahunAjaranAktif->semester;
        
        // Gunakan tahun_ajaran_id dari jadwal (bukan yang aktif) karena siswa_kelas mengikuti jadwal
        $tahunAjaran = $jadwal->tahun_ajaran_id;
        
        // Ambil semua siswa di kelas ini dengan nilai raport menggunakan siswa_kelas
        $siswaList = Siswa::whereHas('siswaKelas', function($query) use ($jadwal, $tahunAjaran) {
                $query->where('kelas_id', $jadwal->kelas_id)
                      ->where('tahun_ajaran_id', $tahunAjaran)
                      ->where('status', 'Aktif');
            })
            ->with(['user', 'nilai' => function($query) use ($jadwal, $tahunAjaran, $semesterAktif) {
                $query->where('mapel_id', $jadwal->mapel_id)
                      ->where('tahun_ajaran_id', $tahunAjaran)
                      ->where('semester', $semesterAktif);
            }])
            ->orderBy('nama_lengkap')
            ->get();
        
        return view('Guru.detailRaportSiswa', compact('jadwal', 'siswaList', 'tahunAjaran', 'tahunAjaranLabel', 'semesterAktif'));
    }

    /**
     * Halaman Detail Input Nilai Siswa
     */
    public function detailNilai($jadwalId, $siswaId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        $siswa = Siswa::with('user')->findOrFail($siswaId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        // Ambil nilai untuk semester Ganjil (fresh dari database, tanpa cache)
        $raport = Raport::where('siswa_id', $siswaId)
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->where('semester', 'Ganjil')
            ->first();
        
        // Refresh raport jika ada untuk memastikan data terbaru
        if ($raport) {
            $raport = $raport->fresh();
        }
        
        // Auto-calculate nilai tugas dari stored procedure untuk semester Ganjil
        DB::statement('CALL sp_calculate_average_tugas(?, ?, ?, @avg)', [$siswaId, $jadwal->mapel_id, 'Ganjil']);
        $averageTugas = DB::select('SELECT @avg as average')[0]->average;
        
        return view('Guru.chartRaportSiswaS1', compact('jadwal', 'siswa', 'raport', 'tahunAjaran', 'tahunAjaranLabel', 'averageTugas'));
    }

    /**
     * Halaman Detail Input Nilai Siswa - Semester 2
     */
    public function detailNilaiS2($jadwalId, $siswaId)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwalId);
        $siswa = Siswa::with('user')->findOrFail($siswaId);
        
        // Ambil tahun ajaran aktif
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
        $tahunAjaranLabel = $tahunAjaranAktif->tahun_mulai . '/' . $tahunAjaranAktif->tahun_selesai;
        
        // Ambil nilai untuk semester Genap (fresh dari database, tanpa cache)
        $raport = Raport::where('siswa_id', $siswaId)
            ->where('mapel_id', $jadwal->mapel_id)
            ->where('tahun_ajaran_id', $tahunAjaran)
            ->where('semester', 'Genap')
            ->first();
        
        // Refresh raport jika ada untuk memastikan data terbaru
        if ($raport) {
            $raport = $raport->fresh();
        }
        
        // Auto-calculate nilai tugas dari stored procedure untuk semester Genap
        DB::statement('CALL sp_calculate_average_tugas(?, ?, ?, @avg)', [$siswaId, $jadwal->mapel_id, 'Genap']);
        $averageTugas = DB::select('SELECT @avg as average')[0]->average;
        
        return view('Guru.chartRaportSiswaS2', compact('jadwal', 'siswa', 'raport', 'tahunAjaran', 'tahunAjaranLabel', 'averageTugas'));
    }

    /**
     * Simpan/Update Nilai Raport
     */
    public function simpanNilai(Request $request, $jadwalId, $siswaId)
    {
        try {
            // Log data yang masuk untuk debugging
            Log::info('Data nilai yang diterima:', [
                'nilai_uts' => $request->nilai_uts,
                'nilai_uas' => $request->nilai_uas,
                'deskripsi' => $request->deskripsi,
                'semester' => $request->semester,
                'jadwal_id' => $jadwalId,
                'siswa_id' => $siswaId
            ]);
            
            $request->validate([
                'nilai_uts' => 'nullable|numeric|min:1|max:100',
                'nilai_uas' => 'nullable|numeric|min:1|max:100',
                'deskripsi' => 'nullable|string|max:250',
                'semester' => 'required|in:Ganjil,Genap'
            ]);
            
            Log::info('Validasi passed');
            
            $jadwal = JadwalPelajaran::findOrFail($jadwalId);
            Log::info('Jadwal found', ['jadwal_id' => $jadwal->id_jadwal]);
            
            // Ambil tahun ajaran aktif
            $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
            $tahunAjaran = $tahunAjaranAktif->id_tahun_ajaran;
            Log::info('Tahun ajaran found', ['tahun_ajaran_id' => $tahunAjaran]);
            
            // Cek dulu apakah raport sudah ada dan di-lock
            $existingRaport = Raport::where([
                'siswa_id' => $siswaId,
                'mapel_id' => $jadwal->mapel_id,
                'tahun_ajaran_id' => $tahunAjaran,
                'semester' => $request->semester
            ])->first();
            
            Log::info('Check existing raport', ['exists' => $existingRaport ? 'yes' : 'no', 'is_locked' => $existingRaport ? $existingRaport->is_locked : false]);
            
            // Cek apakah nilai sudah di-lock
            if ($existingRaport && $existingRaport->is_locked) {
                return redirect()->back()->with('error', 'Nilai sudah di-lock dan tidak dapat diubah. Unlock terlebih dahulu jika ingin mengubah nilai.');
            }
            
            Log::info('Mulai hitung nilai tugas');
            
            // Hitung nilai tugas menggunakan STORED PROCEDURE (sesuai konsep MSBD)
            try {
                DB::statement('CALL sp_calculate_average_tugas(?, ?, ?, @avg)', [
                    $siswaId, 
                    $jadwal->mapel_id, 
                    $request->semester
                ]);
                $result = DB::select('SELECT @avg as average');
                $nilaiTugas = $result[0]->average ?? 0;
                Log::info('Nilai tugas dihitung via SP', ['nilai_tugas' => $nilaiTugas]);
            } catch (\Exception $e) {
                Log::warning('SP gagal, gunakan fallback query', ['error' => $e->getMessage()]);
                // Fallback: hitung manual jika SP error
                $nilaiTugas = DB::table('detail_tugas as dt')
                    ->join('tugas as t', 't.id_tugas', '=', 'dt.tugas_id')
                    ->where('dt.siswa_id', $siswaId)
                    ->where('t.jadwal_id', $jadwalId)
                    ->where('t.semester', $request->semester)
                    ->whereNotNull('dt.nilai')
                    ->avg('dt.nilai') ?? 0;
                Log::info('Nilai tugas dihitung via query fallback', ['nilai_tugas' => $nilaiTugas]);
            }
            
            // Hitung nilai akhir
            $nilaiAkhir = null;
            $nilaiHuruf = null;
            
            if ($nilaiTugas !== null && $request->nilai_uts !== null && $request->nilai_uas !== null) {
                $nilaiAkhir = ($nilaiTugas * 0.3) + ($request->nilai_uts * 0.3) + ($request->nilai_uas * 0.4);
                
                // Hitung grade menggunakan FUNCTION (sesuai konsep MSBD)
                try {
                    $gradeResult = DB::select('SELECT fn_convert_grade_letter(?) as grade', [$nilaiAkhir]);
                    $nilaiHuruf = $gradeResult[0]->grade;
                    Log::info('Nilai huruf dihitung via Function', ['nilai_huruf' => $nilaiHuruf]);
                } catch (\Exception $e) {
                    Log::warning('Function gagal, gunakan fallback', ['error' => $e->getMessage()]);
                    // Fallback: hitung manual jika Function error
                    if ($nilaiAkhir >= 90) $nilaiHuruf = 'A';
                    elseif ($nilaiAkhir >= 80) $nilaiHuruf = 'B';
                    elseif ($nilaiAkhir >= 70) $nilaiHuruf = 'C';
                    elseif ($nilaiAkhir >= 60) $nilaiHuruf = 'D';
                    else $nilaiHuruf = 'E';
                    Log::info('Nilai huruf dihitung via fallback', ['nilai_huruf' => $nilaiHuruf]);
                }
                
                Log::info('Nilai akhir dihitung', ['nilai_akhir' => $nilaiAkhir, 'nilai_huruf' => $nilaiHuruf]);
            }
            
            Log::info('Mulai updateOrCreate');
            
            // Simpan ke database
            $raport = Raport::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'mapel_id' => $jadwal->mapel_id,
                    'tahun_ajaran_id' => $tahunAjaran,
                    'semester' => $request->semester
                ],
                [
                    'nilai_tugas' => $nilaiTugas,
                    'nilai_uts' => $request->nilai_uts,
                    'nilai_uas' => $request->nilai_uas,
                    'nilai_akhir' => $nilaiAkhir,
                    'nilai_huruf' => $nilaiHuruf,
                    'deskripsi' => $request->deskripsi,
                ]
            );
            
            Log::info('Nilai berhasil disimpan:', [
                'id_nilai' => $raport->id_nilai,
                'nilai_tugas' => $raport->nilai_tugas,
                'nilai_uts' => $raport->nilai_uts,
                'nilai_uas' => $raport->nilai_uas,
                'nilai_akhir' => $raport->nilai_akhir,
                'nilai_huruf' => $raport->nilai_huruf
            ]);
            
            return redirect()->back()->with('success', 'Nilai berhasil disimpan!');
            
        } catch (\Exception $e) {
            Log::error('ERROR FATAL saat menyimpan nilai:', [
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'trace' => substr($e->getTraceAsString(), 0, 1000)
            ]);
            return redirect()->back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Lock nilai raport (PERMANEN - tidak bisa diubah lagi)
     */
    public function lockNilai($jadwalId, $siswaId, Request $request)
    {
        $request->validate([
            'semester' => 'required|in:Ganjil,Genap'
        ]);

        $jadwal = JadwalPelajaran::findOrFail($jadwalId);
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        $raport = Raport::where([
            'siswa_id' => $siswaId,
            'mapel_id' => $jadwal->mapel_id,
            'tahun_ajaran_id' => $tahunAjaranAktif->id_tahun_ajaran,
            'semester' => $request->semester
        ])->first();

        if (!$raport) {
            return redirect()->back()->with('error', 'Nilai belum ada. Simpan nilai terlebih dahulu.');
        }

        if ($raport->is_locked) {
            return redirect()->back()->with('error', 'Nilai sudah di-lock sebelumnya.');
        }

        $raport->is_locked = true;
        $raport->save();

        return redirect()->back()->with('success', 'Nilai berhasil di-lock PERMANEN. Nilai tidak dapat diubah lagi.');
    }
}
