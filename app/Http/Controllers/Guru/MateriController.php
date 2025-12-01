<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\JadwalPelajaran;
use App\Models\Pertemuan;
use App\Models\Tugas;
use App\Models\DetailTugas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MateriController extends Controller
{
    /**
     * Tampilkan list mata pelajaran yang diampu guru
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
        
        // Ambil semua jadwal mengajar guru dengan relasi mata pelajaran dan kelas
        $jadwalMengajar = JadwalPelajaran::where('guru_id', $guru->id_guru)
            ->where('tahun_ajaran_id', $tahunAjaranForQuery)
            ->with(['mataPelajaran', 'kelas', 'tahunAjaran'])
            ->get()
            ->groupBy(function($item) {
                return $item->mapel_id . '-' . $item->kelas_id;
            });

        return view('Guru.materi', compact('jadwalMengajar', 'tahunAjaranAktif'));
    }

    /**
     * Tampilkan detail materi per mata pelajaran
     */
    public function detail($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])
            ->findOrFail($jadwal_id);
        
        // Pastikan jadwal ini milik guru yang login
        $guru = Auth::user()->guru;
        if ($jadwal->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua pertemuan untuk jadwal ini
        $pertemuans = Pertemuan::where('jadwal_id', $jadwal_id)
            ->with(['materi', 'tugas'])
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        // Ambil semua materi guru dari view untuk tab "Semua Materi Saya"
        // Gunakan DB::select untuk memastikan tidak ada caching
        $allMateriGuru = DB::select("
            SELECT * FROM view_materi_guru 
            WHERE id_guru = ? AND id_jadwal = ? 
            ORDER BY tgl_upload DESC
        ", [$guru->id_guru, $jadwal_id]);

        return view('Guru.detailMateri', compact('jadwal', 'pertemuans', 'allMateriGuru'));
    }

    /**
     * Tampilkan form upload materi
     */
    public function create($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwal_id);
        
        // Pastikan jadwal ini milik guru yang login
        $guru = Auth::user()->guru;
        if ($jadwal->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua pertemuan untuk dropdown
        $pertemuans = Pertemuan::where('jadwal_id', $jadwal_id)
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        return view('Guru.uploadMateri', compact('jadwal', 'pertemuans'));
    }

    /**
     * Tampilkan form upload materi multiple (upload2Materi)
     */
    public function createMultiple($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwal_id);
        
        // Pastikan jadwal ini milik guru yang login
        $guru = Auth::user()->guru;
        if ($jadwal->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua pertemuan untuk dropdown
        $pertemuans = Pertemuan::where('jadwal_id', $jadwal_id)
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        // Ambil materi yang sudah ada untuk ditampilkan
        $existingMateri = Materi::whereHas('pertemuan', function($q) use ($jadwal_id) {
            $q->where('jadwal_id', $jadwal_id);
        })->with('pertemuan')->get();

        $existingTugas = Tugas::whereHas('pertemuan', function($q) use ($jadwal_id) {
            $q->where('jadwal_id', $jadwal_id);
        })->with('pertemuan')->get();

        return view('Guru.upload2Materi', compact('jadwal', 'pertemuans', 'existingMateri', 'existingTugas'));
    }

    /**
     * Simpan materi baru (HANYA MATERI, tugas punya method sendiri)
     */
    public function store(Request $request, $jadwal_id)
    {
        Log::info('Store materi method called', ['request_data' => $request->all(), 'jadwal_id' => $jadwal_id]);
        
        $validated = $request->validate([
            'id_pertemuan' => 'required|exists:pertemuan,id_pertemuan',
            'judul' => 'required|string|max:250',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // max 10MB
        ], [
            'id_pertemuan.required' => 'Pertemuan wajib dipilih',
            'judul.required' => 'Judul materi wajib diisi',
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, ZIP, atau RAR',
            'file.max' => 'Ukuran file maksimal 10MB',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            // Upload file
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('materi', $fileName, 'public');
            
            Log::info("File uploaded to: {$filePath}");

            // Simpan sebagai materi
            Materi::create([
                'jadwal_id' => $jadwal_id,
                'pertemuan_id' => $validated['id_pertemuan'],
                'judul_materi' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
            ]);
            
            Log::info("Materi created with file_path: {$filePath}");

            DB::commit();
            return redirect()->route('guru.detail_materi', $jadwal_id)
                ->with('success', 'Materi berhasil diupload!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to store materi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengupload materi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan form upload tugas (TERPISAH dari materi)
     */
    public function createTugas($jadwal_id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwal_id);
        
        // Pastikan jadwal ini milik guru yang login
        $guru = Auth::user()->guru;
        if ($jadwal->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua pertemuan untuk dropdown
        $pertemuans = Pertemuan::where('jadwal_id', $jadwal_id)
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        return view('Guru.uploadTugas', compact('jadwal', 'pertemuans'));
    }

    /**
     * Simpan tugas baru (TERPISAH dari materi)
     */
    public function storeTugas(Request $request, $jadwal_id)
    {
        Log::info('StoreTugas method called', ['request_data' => $request->all(), 'jadwal_id' => $jadwal_id]);
        
        $validated = $request->validate([
            'id_pertemuan' => 'required|exists:pertemuan,id_pertemuan',
            'semester' => 'required|in:Ganjil,Genap',
            'judul' => 'required|string|max:250',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // max 10MB
            'tanggal_dibuka' => 'required|date',
            'tanggal_ditutup' => 'required|date|after_or_equal:tanggal_dibuka',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
        ], [
            'id_pertemuan.required' => 'Pertemuan wajib dipilih',
            'semester.required' => 'Semester wajib dipilih',
            'semester.in' => 'Semester harus Ganjil atau Genap',
            'judul.required' => 'Judul tugas wajib diisi',
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, ZIP, atau RAR',
            'file.max' => 'Ukuran file maksimal 10MB',
            'tanggal_dibuka.required' => 'Tanggal dibuka wajib diisi',
            'tanggal_ditutup.required' => 'Tanggal ditutup wajib diisi',
            'tanggal_ditutup.after_or_equal' => 'Tanggal ditutup harus sama atau setelah tanggal dibuka',
            'jam_buka.required' => 'Jam buka wajib diisi',
            'jam_tutup.required' => 'Jam tutup wajib diisi',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            // Upload file
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('tugas', $fileName, 'public');
            
            Log::info("File uploaded to: {$filePath}");

            // Buat waktu_dibuka dan waktu_ditutup (dateTime format)
            $tanggalDibuka = $validated['tanggal_dibuka'];
            $tanggalDitutup = $validated['tanggal_ditutup'];
            $waktuDibuka = $tanggalDibuka . ' ' . $validated['jam_buka'] . ':00';
            $waktuDitutup = $tanggalDitutup . ' ' . $validated['jam_tutup'] . ':59';
            
            $tugas = Tugas::create([
                'jadwal_id' => $jadwal_id,
                'pertemuan_id' => $validated['id_pertemuan'],
                'semester' => $validated['semester'],
                'judul_tugas' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'file_path' => $filePath,
                'waktu_dibuka' => $tanggalDibuka . ' ' . $validated['jam_buka'],
                'waktu_ditutup' => $tanggalDitutup . ' ' . $validated['jam_tutup'],
                'deadline' => $tanggalDitutup . ' ' . $validated['jam_tutup'],
            ]);
            
            Log::info("Tugas created - ID: {$tugas->id_tugas}, file_path: {$filePath}");

            DB::commit();
            return redirect()->route('guru.detail_materi', $jadwal_id)
                ->with('success', 'Tugas berhasil diupload!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to store tugas', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal mengupload tugas: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan form edit materi
     */
    public function edit($jadwal_id, $id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($jadwal_id);
        
        // Pastikan jadwal ini milik guru yang login
        $guru = Auth::user()->guru;
        if ($jadwal->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        $pertemuans = Pertemuan::where('jadwal_id', $jadwal_id)
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        $item = Materi::findOrFail($id);

        return view('Guru.editMateri', compact('jadwal', 'pertemuans', 'item'));
    }

    /**
     * Update materi
     */
    public function update(Request $request, $jadwal_id, $id)
    {
        $validated = $request->validate([
            'id_pertemuan' => 'required|exists:pertemuan,id_pertemuan',
            'judul' => 'required|string|max:250',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $item = Materi::findOrFail($id);
            
            // Update data
            $item->pertemuan_id = $validated['id_pertemuan'];
            $item->judul_materi = $validated['judul'];
            $item->deskripsi = $validated['deskripsi'];
            
            // Upload file baru jika ada
            if ($request->hasFile('file')) {
                // Hapus file lama
                if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                    Storage::disk('public')->delete($item->file_path);
                }
                
                $file = $request->file('file');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $item->file_path = $file->storeAs('materi', $fileName, 'public');
            }
            
            $item->save();

            DB::commit();
            return redirect()->route('guru.detail_materi', $jadwal_id)
                ->with('success', 'Materi berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus materi atau tugas
     */
    public function destroy($jadwal_id, $type, $id)
    {
        DB::beginTransaction();
        try {
            if ($type == 'materi') {
                $item = Materi::findOrFail($id);
                
                // Hapus file
                if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                    Storage::disk('public')->delete($item->file_path);
                }
                
                $item->delete();
            } else {
                $item = Tugas::findOrFail($id);
                
                if ($item->file_path && Storage::disk('public')->exists($item->file_path)) {
                    Storage::disk('public')->delete($item->file_path);
                }
                
                $item->delete();
            }

            DB::commit();
            return redirect()->route('guru.detail_materi', $jadwal_id)
                ->with('success', ucfirst($type) . ' berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    /**
     * Download file materi
     */
    public function download($type, $id)
    {
        try {
            if ($type == 'materi') {
                $item = Materi::findOrFail($id);
                $filePath = $item->file_path;
                $fileName = $item->judul_materi;
            } else {
                $item = Tugas::findOrFail($id);
                $filePath = $item->file_path;
                $fileName = $item->judul_tugas;
            }

            // Debug logging
            Log::info("Download {$type} - ID: {$id}");
            Log::info("Download {$type} - File Path: {$filePath}");

            if (!$filePath) {
                return back()->with('error', 'File tidak tersedia');
            }

            $fullPath = storage_path('app/public/' . $filePath);
            
            Log::info("Download {$type} - Full Path: {$fullPath}");
            Log::info("Download {$type} - File Exists: " . (file_exists($fullPath) ? 'YES' : 'NO'));
            
            if (!file_exists($fullPath)) {
                return back()->with('error', 'File tidak ditemukan di: ' . $filePath);
            }

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            return response()->download($fullPath, $fileName . '.' . $extension);
        } catch (\Exception $e) {
            Log::error("Download {$type} Error: " . $e->getMessage());
            return back()->with('error', 'Gagal download file: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pengumuman aktif
     */
    public function pengumuman()
    {
        // Query langsung tanpa stored procedure
        $pengumuman = DB::table('pengumuman as p')
            ->join('users as u', 'p.author_id', '=', 'u.id')
            ->select(
                'p.id_pengumuman',
                'p.judul',
                'p.isi_pengumuman',
                'p.tgl_publikasi',
                'p.hari',
                'p.file_lampiran',
                'u.name as author_name',
                'u.role as author_role'
            )
            ->where(function($query) {
                $query->where('p.target_role', 'guru')
                      ->orWhere('p.target_role', 'Semua');
            })
            ->orderBy('p.tgl_publikasi', 'desc')
            ->get();
        
        return view('Guru.pengumuman', compact('pengumuman'));
    }

    /**
     * Tampilkan detail tugas dengan daftar siswa
     */
    public function detailTugas($tugas_id)
    {
        $guru = Auth::user()->guru;
        
        $tugas = Tugas::with(['pertemuan', 'jadwalPelajaran.kelas.siswa'])
            ->findOrFail($tugas_id);
        
        // Pastikan tugas ini milik guru yang login
        if ($tugas->jadwalPelajaran->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua siswa dari kelas dan detail tugas mereka
        $siswaList = $tugas->jadwalPelajaran->kelas->siswa->map(function($siswa) use ($tugas) {
            $detailTugas = $tugas->detailTugas->where('siswa_id', $siswa->id_siswa)->first();
            
            return [
                'siswa' => $siswa,
                'detail_tugas' => $detailTugas,
            ];
        });

        return view('Guru.detailTugas', compact('tugas', 'siswaList'));
    }

    /**
     * Update nilai tugas siswa
     */
    public function updateNilaiTugas(Request $request, $detail_tugas_id)
    {
        Log::info('Update Nilai Tugas Called', [
            'detail_tugas_id' => $detail_tugas_id,
            'request_data' => $request->all()
        ]);

        $validated = $request->validate([
            'nilai' => 'required|integer|min:1|max:100',
        ], [
            'nilai.required' => 'Nilai wajib diisi',
            'nilai.min' => 'Nilai minimal adalah 1',
            'nilai.max' => 'Nilai maksimal adalah 100',
            'nilai.integer' => 'Nilai harus berupa angka bulat',
        ]);

        $detailTugas = DetailTugas::findOrFail($detail_tugas_id);
        
        Log::info('DetailTugas Before Update', [
            'id' => $detailTugas->id_detail_tugas,
            'nilai_lama' => $detailTugas->nilai,
            'nilai_baru' => $validated['nilai']
        ]);
        
        $detailTugas->nilai = $validated['nilai'];
        $saved = $detailTugas->save();
        
        Log::info('DetailTugas After Save', [
            'saved' => $saved,
            'nilai_final' => $detailTugas->nilai
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    /**
     * Update komentar guru untuk tugas siswa
     */
    public function updateKomentarTugas(Request $request, $detail_tugas_id)
    {
        $validated = $request->validate([
            'komentar_guru' => 'nullable|string|max:500',
        ]);

        $detailTugas = DetailTugas::findOrFail($detail_tugas_id);
        $detailTugas->komentar_guru = $validated['komentar_guru'];
        $detailTugas->save();

        return back()->with('success', 'Komentar berhasil disimpan!');
    }

    /**
     * Tampilkan form edit tugas
     */
    public function editTugas($tugas_id)
    {
        $guru = Auth::user()->guru;
        
        $tugas = Tugas::with(['pertemuan', 'jadwalPelajaran'])
            ->findOrFail($tugas_id);
        
        // Pastikan tugas ini milik guru yang login
        if ($tugas->jadwalPelajaran->guru_id != $guru->id_guru) {
            abort(403, 'Unauthorized');
        }

        // Ambil semua pertemuan untuk dropdown
        $pertemuans = Pertemuan::where('jadwal_id', $tugas->jadwal_id)
            ->orderBy('nomor_pertemuan', 'asc')
            ->get();

        return view('Guru.editTugas', compact('tugas', 'pertemuans'));
    }

    /**
     * Update tugas yang sudah ada
     */
    public function updateTugas(Request $request, $tugas_id)
    {
        Log::info('UpdateTugas method called', ['request_data' => $request->all(), 'tugas_id' => $tugas_id]);
        
        $validated = $request->validate([
            'id_pertemuan' => 'required|exists:pertemuan,id_pertemuan',
            'judul' => 'required|string|max:250',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // optional on update
            'tanggal_dibuka' => 'required|date',
            'tanggal_ditutup' => 'required|date|after_or_equal:tanggal_dibuka',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
        ], [
            'id_pertemuan.required' => 'Pertemuan wajib dipilih',
            'judul.required' => 'Judul tugas wajib diisi',
            'file.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, ZIP, atau RAR',
            'file.max' => 'Ukuran file maksimal 10MB',
            'tanggal_dibuka.required' => 'Tanggal dibuka wajib diisi',
            'tanggal_ditutup.required' => 'Tanggal ditutup wajib diisi',
            'tanggal_ditutup.after_or_equal' => 'Tanggal ditutup harus sama atau setelah tanggal dibuka',
            'jam_buka.required' => 'Jam buka wajib diisi',
            'jam_tutup.required' => 'Jam tutup wajib diisi',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            $tugas = Tugas::findOrFail($tugas_id);
            
            // Pastikan tugas ini milik guru yang login
            $guru = Auth::user()->guru;
            if ($tugas->jadwalPelajaran->guru_id != $guru->id_guru) {
                abort(403, 'Unauthorized');
            }

            // Upload file baru jika ada
            if ($request->hasFile('file')) {
                // Hapus file lama
                if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
                    Storage::disk('public')->delete($tugas->file_path);
                }
                
                $file = $request->file('file');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $tugas->file_path = $file->storeAs('tugas', $fileName, 'public');
                
                Log::info("New file uploaded to: {$tugas->file_path}");
            }

            // Update semua field
            $tugas->pertemuan_id = $validated['id_pertemuan'];
            $tugas->judul_tugas = $validated['judul'];
            $tugas->deskripsi = $validated['deskripsi'];
            $tugas->waktu_dibuka = $validated['tanggal_dibuka'] . ' ' . $validated['jam_buka'];
            $tugas->waktu_ditutup = $validated['tanggal_ditutup'] . ' ' . $validated['jam_tutup'];
            $tugas->deadline = $validated['tanggal_ditutup'] . ' ' . $validated['jam_tutup'];
            
            $tugas->save();
            
            Log::info("Tugas updated - ID: {$tugas->id_tugas}");

            DB::commit();
            return redirect()->route('guru.detail_tugas', $tugas_id)
                ->with('success', 'Tugas berhasil diupdate! Perubahan waktu akan berlaku segera.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to update tugas', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal update tugas: ' . $e->getMessage())->withInput();
        }
    }
}
