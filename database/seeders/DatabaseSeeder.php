<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ============================================
        // BUAT 3 USER UNTUK TESTING: ADMIN, GURU, SISWA
        // ============================================

        // 1. ADMIN (admin2)
        $admin = User::firstOrCreate(
            ['email' => 'admin2@sman12.com'],
            [
                'name' => 'Admin Kedua',
                'password' => Hash::make('admin2'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        
        // ✅ FORCE CREATE GRANTS jika belum ada
        if (!$admin->db_user) {
            $admin->createDatabaseUser();
            $admin->applyRoleGrants();
        }

        // 2. GURU (guru1)
        // Buat data di tabel guru dulu
        $guruData = DB::table('guru')->where('nip', '11111')->first();
        if (!$guruData) {
            $guruId = DB::table('guru')->insertGetId([
                'nip' => '11111',
                'nama_lengkap' => 'Siti Nurhaliza S.Pd',
                'jenis_kelamin' => 'Perempuan',
                'tempat_lahir' => 'Medan',
                'tgl_lahir' => '1990-06-15',
                'alamat' => 'Jl. Guru Raya No. 456',
                'no_telepon' => '081298765432',
                'email' => 'siti.nurhaliza@sman12.com',
                'agama' => 'Islam',
                'golongan_darah' => 'A',
            ]);
        } else {
            $guruId = $guruData->id_guru;
        }

        // Buat user guru
        $guru = User::firstOrCreate(
            ['email' => 'siti.nurhaliza@sman12.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('guru1'),
                'role' => 'guru',
                'reference_id' => $guruId,
                'is_active' => true,
            ]
        );

        // ✅ UPDATE user_id di tabel guru
        DB::table('guru')->where('id_guru', $guruId)->update(['user_id' => $guru->id]);
        
        // ✅ FORCE CREATE GRANTS jika belum ada
        if (!$guru->db_user) {
            $guru->createDatabaseUser();
            $guru->applyRoleGrants();
        }

        // 3. SISWA (siswa1)
        // Pastikan kelas ada
        $kelas = DB::table('kelas')->first();
        if (!$kelas) {
            // Buat tahun ajaran dulu
            $tahunAjaran = DB::table('tahun_ajaran')
                ->where('tahun_mulai', '2024')
                ->where('tahun_selesai', '2025')
                ->where('semester', 'Ganjil')
                ->first();
            
            if (!$tahunAjaran) {
                $tahunAjaranId = DB::table('tahun_ajaran')->insertGetId([
                    'tahun_mulai' => '2024',
                    'tahun_selesai' => '2025',
                    'semester' => 'Ganjil',
                    'status' => 'Aktif',
                ]);
            } else {
                $tahunAjaranId = $tahunAjaran->id_tahun_ajaran;
            }

            // Buat kelas
            $kelasId = DB::table('kelas')->insertGetId([
                'nama_kelas' => 'X-2',
                'tingkat' => '10',
                'jurusan' => 'IPA',
                'tahun_ajaran_id' => $tahunAjaranId,
            ]);
        } else {
            $kelasId = $kelas->id_kelas;
        }

        // Buat data di tabel siswa
        $siswaData = DB::table('siswa')->where('nisn', '12345')->first();
        if (!$siswaData) {
            $siswaId = DB::table('siswa')->insertGetId([
                'nis' => '12345',
                'nisn' => '12345',
                'nama_lengkap' => 'Budi Santoso',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Medan',
                'tgl_lahir' => '2008-03-20',
                'alamat' => 'Jl. Siswa Cerdas No. 789',
                'no_telepon' => '082187654321',
                'email' => 'budi.santoso@sman12.com',
                'agama' => 'Islam',
                'golongan_darah' => 'O',
                'kelas_id' => $kelasId,
            ]);
        } else {
            $siswaId = $siswaData->id_siswa;
        }

        // Buat user siswa
        $siswa = User::firstOrCreate(
            ['email' => 'budi.santoso@sman12.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('siswa1'),
                'role' => 'siswa',
                'reference_id' => $siswaId,
                'is_active' => true,
            ]
        );

        // ✅ UPDATE user_id di tabel siswa
        DB::table('siswa')->where('id_siswa', $siswaId)->update(['user_id' => $siswa->id]);
        
        // ✅ FORCE CREATE GRANTS jika belum ada
        if (!$siswa->db_user) {
            $siswa->createDatabaseUser();
            $siswa->applyRoleGrants();
        }
    }
}