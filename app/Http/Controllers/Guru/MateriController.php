<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\JadwalPelajaran;
use App\Models\Pertemuan;
use App\Models\Tugas;
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
        
        // Ambil semua jadwal mengajar guru dengan relasi mata pelajaran dan kelas
        $jadwalMengajar = JadwalPelajaran::where('guru_id', $guru->id_guru)
            ->with(['mataPelajaran', 'kelas', 'tahunAjaran'])
            ->get()
            ->groupBy(function($item) {
                return $item->mapel_id . '-' . $item->kelas_id;
            });

        return view('Guru.materi', compact('jadwalMengajar'));
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

        return view('Guru.detailMateri', compact('jadwal', 'pertemuans'));
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
     * Simpan materi atau tugas baru
     */
    public function store(Request $request, $jadwal_id)
    {
        Log::info('Store method called', ['request_data' => $request->all(), 'jadwal_id' => $jadwal_id]);
        
        $validated = $request->validate([
            'id_pertemuan' => 'required|exists:pertemuan,id_pertemuan',
            'tipe_berkas' => 'required|in:materi,tugas',
            'judul' => 'required|string|max:250',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240', // max 10MB
            'deadline' => 'nullable|date',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
        ], [
            'id_pertemuan.required' => 'Pertemuan wajib dipilih',
            'file.required' => 'File wajib diupload',
            'file.mimes' => 'File harus berformat PDF, DOC, DOCX, PPT, PPTX, ZIP, atau RAR',
            'file.max' => 'Ukuran file maksimal 10MB',
            'jam_tutup.after' => 'Jam tutup harus setelah jam buka',
        ]);

        Log::info('Validation passed', ['validated' => $validated]);

        DB::beginTransaction();
        try {
            // Upload file
            $file = $request->file('file');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('materi', $fileName, 'public');

            if ($validated['tipe_berkas'] == 'materi') {
                // Simpan sebagai materi
                Materi::create([
                    'jadwal_id' => $jadwal_id,
                    'pertemuan_id' => $validated['id_pertemuan'],
                    'judul_materi' => $validated['judul'],
                    'deskripsi' => $validated['deskripsi'],
                    'file_path' => $filePath,
                ]);
            } else {
                // Simpan sebagai tugas
                $pertemuan = Pertemuan::findOrFail($validated['id_pertemuan']);
                
                Tugas::create([
                    'jadwal_id' => $jadwal_id,
                    'pertemuan_id' => $validated['id_pertemuan'],
                    'judul_tugas' => $validated['judul'],
                    'deskripsi_tugas' => $validated['deskripsi'],
                    'file_tugas' => $filePath,
                    'tgl_upload' => now(),
                    'deadline' => $validated['deadline'] ?? null,
                    'jam_buka' => $validated['jam_buka'] ?? '00:00',
                    'jam_tutup' => $validated['jam_tutup'] ?? '23:59',
                ]);
            }

            DB::commit();
            return redirect()->route('guru.detail_materi', $jadwal_id)
                ->with('success', ucfirst($validated['tipe_berkas']) . ' berhasil diupload!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengupload: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan form edit materi/tugas
     */
    public function edit($jadwal_id, $type, $id)
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

        if ($type == 'materi') {
            $item = Materi::findOrFail($id);
        } else {
            $item = Tugas::findOrFail($id);
        }

        return view('Guru.editMateri', compact('jadwal', 'pertemuans', 'item', 'type'));
    }

    /**
     * Update materi atau tugas
     */
    public function update(Request $request, $jadwal_id, $type, $id)
    {
        $validated = $request->validate([
            'id_pertemuan' => 'required|exists:pertemuan,id_pertemuan',
            'judul' => 'required|string|max:250',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:10240',
            'deadline' => 'nullable|date',
            'jam_buka' => 'nullable|date_format:H:i',
            'jam_tutup' => 'nullable|date_format:H:i|after:jam_buka',
        ]);

        DB::beginTransaction();
        try {
            if ($type == 'materi') {
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
            } else {
                $item = Tugas::findOrFail($id);
                
                $item->pertemuan_id = $validated['id_pertemuan'];
                $item->judul_tugas = $validated['judul'];
                $item->deskripsi_tugas = $validated['deskripsi'];
                $item->deadline = $validated['deadline'] ?? $item->deadline;
                $item->jam_buka = $validated['jam_buka'] ?? $item->jam_buka;
                $item->jam_tutup = $validated['jam_tutup'] ?? $item->jam_tutup;
                
                if ($request->hasFile('file')) {
                    if ($item->file_tugas && Storage::disk('public')->exists($item->file_tugas)) {
                        Storage::disk('public')->delete($item->file_tugas);
                    }
                    
                    $file = $request->file('file');
                    $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    $item->file_tugas = $file->storeAs('tugas', $fileName, 'public');
                }
                
                $item->save();
            }

            DB::commit();
            return redirect()->route('guru.detail_materi', $jadwal_id)
                ->with('success', ucfirst($type) . ' berhasil diupdate!');
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
                
                if ($item->file_tugas && Storage::disk('public')->exists($item->file_tugas)) {
                    Storage::disk('public')->delete($item->file_tugas);
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
        if ($type == 'materi') {
            $item = Materi::findOrFail($id);
            $filePath = $item->file_path;
            $fileName = $item->judul_materi;
        } else {
            $item = Tugas::findOrFail($id);
            $filePath = $item->file_tugas;
            $fileName = $item->judul_tugas;
        }

        if (!Storage::disk('public')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($filePath, $fileName . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
}
