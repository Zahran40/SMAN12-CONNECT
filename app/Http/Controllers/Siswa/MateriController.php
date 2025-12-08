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
                
                // Gunakan accessor waktu_ditutup dari model
                $waktuTutup = $tugas->waktu_ditutup;
                $now = now();
                
                // Tentukan status pengumpulan
                if (!$detailTugas) {
                    $statusPengumpulan = ($waktuTutup && $now->greaterThan($waktuTutup)) ? 'Belum Dikumpulkan' : 'Belum Dikumpulkan';
                } else {
                    $tglKumpul = \Carbon\Carbon::parse($detailTugas->tgl_kumpul);
                    $statusPengumpulan = ($waktuTutup && $tglKumpul->lessThanOrEqualTo($waktuTutup)) ? 'Tepat Waktu' : 'Terlambat';
                }
                
                return (object)[
                    'id_tugas' => $tugas->id_tugas,
                    'judul_tugas' => $tugas->judul_tugas,
                    'deskripsi' => $tugas->deskripsi,
                    'nomor_pertemuan' => $tugas->pertemuan->nomor_pertemuan ?? '-',
                    'deadline' => $tugas->deadline,
                    'waktu_ditutup' => $waktuTutup ? $waktuTutup->format('Y-m-d H:i:s') : null,
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
        // Debug: Pastikan method ini dipanggil
        Log::info('Upload Tugas Called', [
            'tugas_id' => $tugas_id,
            'has_file' => $request->hasFile('file'),
            'user_id' => Auth::id()
        ]);
        
        try {
            $validated = $request->validate([
                'file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:20480', // max 20MB
                'teks_jawaban' => 'nullable|string',
            ], [
                'file.mimes' => 'File harus berformat PDF, DOC, DOCX, ZIP, atau RAR',
                'file.max' => 'Ukuran file maksimal 20MB',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Failed', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        }

        $tugas = Tugas::findOrFail($tugas_id);
        $siswa = Auth::user()->siswa;
        
        if (!$siswa) {
            Log::error('Siswa not found', ['user_id' => Auth::id()]);
            return back()->with('error', 'Data siswa tidak ditemukan');
        }
        
        Log::info('Siswa found', ['siswa_id' => $siswa->id_siswa]);

        // Cek apakah masih dalam waktu pengumpulan
        $now = now();
        
        // Parse waktu_dibuka dan waktu_ditutup langsung dari database
        $waktuBuka = $tugas->waktu_dibuka ? \Carbon\Carbon::parse($tugas->waktu_dibuka) : now();
        $waktuTutup = $tugas->waktu_ditutup ? \Carbon\Carbon::parse($tugas->waktu_ditutup) : now();

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

            // Upload file jika ada
            $filePath = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $siswa->nis . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('tugas_siswa', $fileName, 'public');
            }

            if ($detailTugas) {
                // Update (replace file lama jika ada file baru)
                if ($filePath && $detailTugas->file_path && Storage::disk('public')->exists($detailTugas->file_path)) {
                    Storage::disk('public')->delete($detailTugas->file_path);
                }
                
                if ($filePath) {
                    $detailTugas->file_path = $filePath;
                }
                if ($request->teks_jawaban !== null) {
                    $detailTugas->teks_jawaban = $request->teks_jawaban;
                }
                $detailTugas->tgl_kumpul = now();
                $detailTugas->save();

                $message = 'Tugas berhasil diupdate!';
            } else {
                // Create new
                DetailTugas::create([
                    'tugas_id' => $tugas_id,
                    'siswa_id' => $siswa->id_siswa,
                    'file_path' => $filePath,
                    'teks_jawaban' => $request->teks_jawaban,
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
        // Gunakan stored procedure untuk performa lebih baik
        $pengumuman = DB::select('CALL sp_get_pengumuman_aktif(?)', ['siswa']);
        
        return view('siswa.pengumuman', compact('pengumuman'));
    }
}
