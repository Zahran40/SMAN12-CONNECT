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

        return view('siswa.detailMateri', compact('jadwal', 'pertemuans'));
    }

    /**
     * Download file materi
     */
    public function downloadMateri($id)
    {
        $materi = Materi::findOrFail($id);

        if (!Storage::disk('public')->exists($materi->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $materi->file_path, 
            $materi->judul_materi . '.' . pathinfo($materi->file_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Download file tugas (soal)
     */
    public function downloadTugas($id)
    {
        $tugas = Tugas::findOrFail($id);

        if (!$tugas->file_tugas || !Storage::disk('public')->exists($tugas->file_tugas)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download(
            $tugas->file_tugas, 
            $tugas->judul_tugas . '.' . pathinfo($tugas->file_tugas, PATHINFO_EXTENSION)
        );
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

        // Cek apakah masih dalam waktu pengumpulan
        $now = now();
        $jamBuka = \Carbon\Carbon::parse($tugas->tgl_upload->format('Y-m-d') . ' ' . $tugas->jam_buka);
        $jamTutup = \Carbon\Carbon::parse($tugas->tgl_upload->format('Y-m-d') . ' ' . $tugas->jam_tutup);

        if ($now->lt($jamBuka)) {
            return back()->with('error', 'Tugas belum dibuka untuk pengumpulan');
        }

        if ($now->gt($jamTutup)) {
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
        $pengumuman = \App\Models\Pengumuman::where('status', 'aktif')
            ->orderBy('tanggal_dibuat', 'desc')
            ->get();
        
        return view('siswa.pengumuman', compact('pengumuman'));
    }
}
