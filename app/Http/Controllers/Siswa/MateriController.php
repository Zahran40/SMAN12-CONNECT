<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\JadwalPelajaran;
use App\Models\Pertemuan;
use App\Models\Tugas;
use App\Models\DetailTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MateriController extends Controller
{
    /**
     * Tampilkan list mata pelajaran untuk siswa
     */
    public function index()
    {
        $siswa = Auth::user()->siswa;
        
        // Ambil semua jadwal pelajaran berdasarkan kelas siswa
        $jadwalPelajaran = JadwalPelajaran::where('kelas_id', $siswa->kelas_id)
            ->with(['mataPelajaran', 'kelas', 'guru', 'tahunAjaran'])
            ->get();

        return view('siswa.materi', compact('jadwalPelajaran'));
    }

    /**
     * Tampilkan detail materi per mata pelajaran
     */
    public function detail($jadwal_id)
    {
        $siswa = Auth::user()->siswa;
        
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])
            ->findOrFail($jadwal_id);
        
        // Pastikan jadwal ini untuk kelas siswa
        if ($jadwal->kelas_id != $siswa->kelas_id) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua pertemuan dengan materi dan tugas
        $pertemuans = Pertemuan::where('jadwal_id', $jadwal_id)
            ->with([
                'materi', 
                'tugas',
                'tugas.detailTugas' => function($query) use ($siswa) {
                    $query->where('siswa_id', $siswa->id_siswa);
                }
            ])
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        // Ambil semua tugas untuk tab Status Tugas
        $allTugasSiswa = Tugas::where('jadwal_id', $jadwal_id)
            ->with(['pertemuan', 'detailTugas' => function($query) use ($siswa) {
                $query->where('siswa_id', $siswa->id_siswa);
            }])
            ->get()
            ->map(function($tugas) use ($siswa) {
                $detailTugas = $tugas->detailTugas->first();
                $waktuTutup = \Carbon\Carbon::parse($tugas->waktu_ditutup);
                $now = now();
                
                // Tentukan status pengumpulan
                if (!$detailTugas) {
                    $statusPengumpulan = $now->greaterThan($waktuTutup) ? 'Belum Dikumpulkan' : 'Belum Dikumpulkan';
                } else {
                    $tglKumpul = \Carbon\Carbon::parse($detailTugas->tgl_kumpul);
                    $statusPengumpulan = $tglKumpul->lessThanOrEqualTo($waktuTutup) ? 'Tepat Waktu' : 'Terlambat';
                }
                
                return (object)[
                    'id_tugas' => $tugas->id_tugas,
                    'judul_tugas' => $tugas->judul_tugas,
                    'deskripsi' => $tugas->deskripsi,
                    'nomor_pertemuan' => $tugas->pertemuan->nomor_pertemuan ?? '-',
                    'deadline' => $tugas->deadline,
                    'waktu_ditutup' => $tugas->waktu_ditutup,
                    'tgl_kumpul' => $detailTugas->tgl_kumpul ?? null,
                    'nilai' => $detailTugas->nilai ?? null,
                    'komentar_guru' => $detailTugas->komentar_guru ?? null,
                    'status_pengumpulan' => $statusPengumpulan,
                ];
            })
            ->sortByDesc('deadline');

        return view('siswa.detailMateri', compact('jadwal', 'pertemuans', 'allTugasSiswa'));
    }

    /**
     * Download file materi
     */
    public function downloadMateri($id)
    {
        $materi = Materi::findOrFail($id);

        $fullPath = storage_path('app/public/' . $materi->file_path);
        
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $extension = pathinfo($materi->file_path, PATHINFO_EXTENSION);
        return response()->download($fullPath, $materi->judul_materi . '.' . $extension);
    }

    /**
     * Download file tugas (soal)
     */
    public function downloadTugas($id)
    {
        $tugas = Tugas::findOrFail($id);

        // Debug: Log file path
        Log::info('Download Tugas - File Path: ' . $tugas->file_path);
        
        if (!$tugas->file_path) {
            return back()->with('error', 'File tugas tidak tersedia');
        }

        $fullPath = storage_path('app/public/' . $tugas->file_path);
        
        // Debug: Log full path
        Log::info('Download Tugas - Full Path: ' . $fullPath);
        Log::info('Download Tugas - File Exists: ' . (file_exists($fullPath) ? 'YES' : 'NO'));
        
        if (!file_exists($fullPath)) {
            return back()->with('error', 'File tidak ditemukan di: ' . $tugas->file_path);
        }

        $extension = pathinfo($tugas->file_path, PATHINFO_EXTENSION);
        $fileName = $tugas->judul_tugas . '.' . $extension;
        
        return response()->download($fullPath, $fileName);
    }

    /**
     * Upload jawaban tugas siswa
     */
    public function uploadTugas(Request $request, $tugas_id)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip,rar|max:5120', // max 5MB
        ], [
            'file.required' => 'File tugas wajib diupload',
            'file.mimes' => 'File harus berformat PDF, DOC, DOCX, ZIP, atau RAR',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        $tugas = Tugas::findOrFail($tugas_id);
        $siswa = Auth::user()->siswa;

        // Cek apakah masih dalam waktu pengumpulan (gunakan waktu_dibuka dan waktu_ditutup)
        $now = now();
        $waktuBuka = \Carbon\Carbon::parse($tugas->waktu_dibuka);
        $waktuTutup = \Carbon\Carbon::parse($tugas->waktu_ditutup);

        if ($now->lt($waktuBuka)) {
            return back()->with('error', 'Tugas belum dibuka untuk pengumpulan');
        }

        if ($now->gt($waktuTutup)) {
            return back()->with('error', 'Waktu pengumpulan tugas sudah ditutup');
        }

        DB::beginTransaction();
        try {
            // Cek apakah sudah pernah upload
            $detailTugas = DetailTugas::where('tugas_id', $tugas_id)
                ->where('siswa_id', $siswa->id_siswa)
                ->first();

            // Upload file
            $file = $request->file('file');
            $fileName = time() . '_' . $siswa->nis . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('tugas_siswa', $fileName, 'public');

            if ($detailTugas) {
                // Update (replace file lama)
                if ($detailTugas->file_path && Storage::disk('public')->exists($detailTugas->file_path)) {
                    Storage::disk('public')->delete($detailTugas->file_path);
                }
                
                $detailTugas->file_path = $filePath;
                $detailTugas->tgl_kumpul = now();
                $detailTugas->save();

                $message = 'Tugas berhasil diupdate!';
            } else {
                // Create new
                DetailTugas::create([
                    'tugas_id' => $tugas_id,
                    'siswa_id' => $siswa->id_siswa,
                    'file_path' => $filePath,
                    'tgl_kumpul' => now(),
                ]);

                $message = 'Tugas berhasil dikumpulkan!';
            }

            DB::commit();
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengupload tugas: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pengumuman aktif
     */
    public function pengumuman()
    {
        // Gunakan stored procedure untuk get pengumuman aktif untuk siswa
        $pengumuman = DB::select('CALL sp_get_pengumuman_aktif(?)', ['siswa']);
        
        return view('siswa.pengumuman', compact('pengumuman'));
    }
}
