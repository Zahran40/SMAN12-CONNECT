<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('tgl_publikasi', 'desc')->get();
        return view('Admin.pengumuman', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'hari' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'isi' => 'required|string',
            'target_role' => 'required|in:Semua,guru,siswa',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Use tanggal from form or current date
            $tanggal = $validated['tanggal'] ?? now()->format('Y-m-d');
            // Use hari from form or auto detect from date
            $hari = $validated['hari'] ?? \Carbon\Carbon::parse($tanggal)->translatedFormat('l');

            $filePath = null;
            if ($request->hasFile('file_lampiran')) {
                $file = $request->file('file_lampiran');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs('pengumuman', $fileName, 'public');
            }

            Pengumuman::create([
                'judul' => $validated['judul'],
                'isi_pengumuman' => $validated['isi'],
                'tgl_publikasi' => $tanggal,
                'hari' => $hari,
                'target_role' => $validated['target_role'],
                'author_id' => Auth::id(),
                'tanggal_dibuat' => now(),
                'file_lampiran' => $filePath,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menambahkan pengumuman: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        DB::beginTransaction();
        try {
            $pengumuman = Pengumuman::findOrFail($id);

            $updateData = [
                'judul' => $validated['judul'],
                'isi_pengumuman' => $validated['isi'],
                'tgl_publikasi' => now()->format('Y-m-d'),
                'hari' => now()->translatedFormat('l'),
                'status' => $validated['status'],
            ];

            if ($request->hasFile('file_lampiran')) {
                // Delete old file
                if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                    Storage::disk('public')->delete($pengumuman->file_lampiran);
                }

                $file = $request->file('file_lampiran');
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $pengumuman->file_lampiran = $file->storeAs('pengumuman', $fileName, 'public');
            }

            $pengumuman->judul = $validated['judul'];
            $pengumuman->isi_pengumuman = $validated['isi'];
            $pengumuman->tgl_publikasi = now()->format('Y-m-d');
            $pengumuman->hari = now()->translatedFormat('l');
            $pengumuman->status = $validated['status'];
            $pengumuman->save();

            DB::commit();
            return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengupdate pengumuman: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pengumuman = Pengumuman::findOrFail($id);

            // Delete file if exists
            if ($pengumuman->file_lampiran && Storage::disk('public')->exists($pengumuman->file_lampiran)) {
                Storage::disk('public')->delete($pengumuman->file_lampiran);
            }

            $pengumuman->delete();

            DB::commit();
            return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus pengumuman: ' . $e->getMessage());
        }
    }
}
