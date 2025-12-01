<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GuruEsterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("========================================");
        $this->command->info("🧑‍🏫 SEEDING GURU & KELAS X-E1");
        $this->command->info("========================================\n");

        DB::beginTransaction();
        try {
            // 1. Cek atau buat Kelas X-E1
            $kelas = DB::table('kelas')->where('nama_kelas', 'X-E1')->first();
            
            if (!$kelas) {
                // Ambil tahun ajaran aktif untuk kelas
                $tahunAjaranAktif = DB::table('tahun_ajaran')->where('status', 'Aktif')->first();
                
                $kelasId = DB::table('kelas')->insertGetId([
                    'nama_kelas' => 'X-E1',
                    'tingkat' => '10',
                    'jurusan' => 'IPA',
                    'tahun_ajaran_id' => $tahunAjaranAktif->id_tahun_ajaran ?? null,
                    'wali_kelas_id' => null, // akan di-update setelah guru dibuat
                ]);
                $this->command->info("✓ Kelas X-E1 berhasil dibuat (ID: {$kelasId})");
            } else {
                $kelasId = $kelas->id_kelas;
                $this->command->info("✓ Kelas X-E1 sudah ada (ID: {$kelasId})");
            }

            // 2. Buat User untuk Guru
            $email = 'ester.simanjuntak@guru.sman12.sch.id';
            $password = 'guru1234'; // Password default untuk guru
            
            // Cek apakah user sudah ada
            $existingUser = DB::table('users')->where('email', $email)->first();
            
            if ($existingUser) {
                $this->command->warn("⚠ User dengan email {$email} sudah ada. Skip pembuatan guru.");
                DB::commit();
                return;
            }

            $userId = DB::table('users')->insertGetId([
                'name' => 'Ester Donna Simanjuntak S.Pd.M.Pd',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'guru',
                'is_active' => true,
                'must_change_password' => false,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->command->info("✓ User guru berhasil dibuat (ID: {$userId})");

            // 3. Buat data Guru
            // Ambil ID mata pelajaran Matematika (atau bisa disesuaikan)
            $mataPelajaran = DB::table('mata_pelajaran')->where('nama_mapel', 'Matematika')->first();
            $mapelId = $mataPelajaran->id_mapel ?? null;

            $guruId = DB::table('guru')->insertGetId([
                'user_id' => $userId,
                'nip' => '198501152010012001', // NIP contoh
                'nama_lengkap' => 'Ester Donna Simanjuntak S.Pd.M.Pd',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Medan',
                'tgl_lahir' => '1985-01-15',
                'agama' => 'Kristen',
                'alamat' => 'Medan',
                'no_telepon' => '081234567890',
                'email' => $email,
                'mapel_id' => $mapelId,
            ]);

            $this->command->info("✓ Data guru berhasil dibuat (ID: {$guruId})");

            // 4. Update wali kelas di tabel kelas
            DB::table('kelas')
                ->where('id_kelas', $kelasId)
                ->update(['wali_kelas_id' => $guruId]);

            $this->command->info("✓ Guru Ester ditetapkan sebagai wali kelas X-E1");

            // 5. Assign 36 siswa ke kelas X-E1
            // Ambil 36 siswa yang baru dibuat (dengan NIS 240001-240036)
            $siswaList = DB::table('siswa')
                ->whereBetween('nis', ['240001', '240036'])
                ->orderBy('nis')
                ->get();

            if ($siswaList->count() > 0) {
                // Ambil tahun ajaran aktif
                $tahunAjaran = DB::table('tahun_ajaran')
                    ->where('status', 'Aktif')
                    ->first();

                if ($tahunAjaran) {
                    $tahunAjaranId = $tahunAjaran->id_tahun_ajaran;
                    
                    foreach ($siswaList as $siswa) {
                        // Cek apakah sudah ada di siswa_kelas
                        $existing = DB::table('siswa_kelas')
                            ->where('siswa_id', $siswa->id_siswa)
                            ->where('kelas_id', $kelasId)
                            ->where('tahun_ajaran_id', $tahunAjaranId)
                            ->exists();

                        if (!$existing) {
                            DB::table('siswa_kelas')->insert([
                                'siswa_id' => $siswa->id_siswa,
                                'kelas_id' => $kelasId,
                                'tahun_ajaran_id' => $tahunAjaranId,
                                'status' => 'Aktif',
                                'tanggal_masuk' => Carbon::now(),
                            ]);
                        }

                        // Update kolom kelas_id di tabel siswa (deprecated tapi masih dipakai)
                        DB::table('siswa')
                            ->where('id_siswa', $siswa->id_siswa)
                            ->update(['kelas_id' => $kelasId]);
                    }

                    $this->command->info("✓ {$siswaList->count()} siswa berhasil di-assign ke kelas X-E1");
                } else {
                    $this->command->warn("⚠ Tidak ada tahun ajaran aktif. Siswa tidak bisa di-assign ke kelas.");
                }
            } else {
                $this->command->warn("⚠ Tidak ada siswa dengan NIS 240001-240036. Jalankan SiswaSeeder terlebih dahulu.");
            }

            DB::commit();

            // Summary
            $this->command->info("\n========================================");
            $this->command->info("📊 SUMMARY");
            $this->command->info("========================================");
            $this->command->info("✅ Kelas X-E1 berhasil dibuat/ditemukan");
            $this->command->info("✅ Guru Ester Donna Simanjuntak berhasil dibuat");
            $this->command->info("✅ Guru Ester ditetapkan sebagai wali kelas X-E1");
            $this->command->info("✅ {$siswaList->count()} siswa di-assign ke kelas X-E1");
            $this->command->info("\n📝 LOGIN CREDENTIALS GURU:");
            $this->command->info("Email: {$email}");
            $this->command->info("Password: {$password}");
            $this->command->info("========================================\n");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("✗ Error: {$e->getMessage()}");
        }
    }
}
