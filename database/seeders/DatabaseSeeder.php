<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("🌱 MULAI SEEDING DATABASE SMAN12-CONNECT");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        // ============================================
        // 1️⃣ TAHUN AJARAN & KELAS (via Observer)
        // ============================================
        $this->command->info("\n1️⃣ Seeding Tahun Ajaran & Kelas...");
        $this->call(TahunAjaranSeeder::class);

        // ============================================
        // 1.5️⃣ SISWA (36 siswa untuk X-E1)
        // ============================================
        $this->command->info("\n1.5️⃣ Seeding Siswa...");
        $this->call(SiswaSeeder::class);

        // ============================================
        // 2️⃣ KELAS X-E1 COMPLETE (Guru Ester + 36 Siswa)
        // ============================================
        $this->command->info("\n2️⃣ Seeding Kelas X-E1 Complete...");
        $this->call(KelasXE1CompleteSeeder::class);

        // Ambil tahun ajaran aktif untuk digunakan di seeder selanjutnya
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();
        
        if (!$tahunAjaranAktif) {
            $this->command->error("❌ Tidak ada tahun ajaran aktif!");
            return;
        }
        
        $this->command->info("✅ Tahun ajaran aktif: {$tahunAjaranAktif->tahun_mulai}/{$tahunAjaranAktif->tahun_selesai} {$tahunAjaranAktif->semester}");

        // Ambil kelas X-MIPA-1 untuk siswa testing
        $kelasX1 = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
            ->where('nama_kelas', 'X-MIPA-1')
            ->where('tingkat', '10')
            ->where('jurusan', 'MIPA')
            ->first();

        if (!$kelasX1) {
            $this->command->error("❌ Kelas X-MIPA-1 tidak ditemukan!");
            return;
        }

        // ============================================
        // 3️⃣ USERS TAMBAHAN (ADMIN & TESTING)
        // ============================================
        $this->command->info("\n3️⃣ Seeding Users Tambahan...");

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
        
        $this->command->info("✅ Admin: {$admin->email}");

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
        
        $this->command->info("✅ Guru: {$guru->email} (NIP: 11111)");

        // 3. SISWA (siswa1)
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
                'kelas_id' => $kelasX1->id_kelas, // Menggunakan kelas X-1 IPA
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
        
        // ✅ INSERT ke siswa_kelas jika belum ada (untuk relasi many-to-many)
        $siswaKelasExists = DB::table('siswa_kelas')
            ->where('siswa_id', $siswaId)
            ->where('kelas_id', $kelasX1->id_kelas)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
            ->exists();
        
        if (!$siswaKelasExists) {
            DB::table('siswa_kelas')->insert([
                'siswa_id' => $siswaId,
                'kelas_id' => $kelasX1->id_kelas,
                'tahun_ajaran_id' => $tahunAjaranAktif->id_tahun_ajaran,
                'status' => 'Aktif',
                'tanggal_masuk' => now(),
                // TIDAK ADA created_at & updated_at (tabel siswa_kelas tidak punya timestamps)
            ]);
        }
        
        $this->command->info("✅ Siswa: {$siswa->email} (NIS: 12345, Kelas: X-1 IPA)");

        // ============================================
        // 4️⃣ MATA PELAJARAN, JADWAL & PERTEMUAN
        // ============================================
        $this->command->info("\n4️⃣ Seeding Mata Pelajaran, Jadwal & Pertemuan...");
        
        // Buat Mata Pelajaran
        $mapelMatExists = DB::table('mata_pelajaran')->where('kode_mapel', 'MAT001')->first();
        if (!$mapelMatExists) {
            $mapelMat = DB::table('mata_pelajaran')->insertGetId([
                'kode_mapel' => 'MAT001',
                'nama_mapel' => 'Matematika',
            ]);
            $this->command->info("✅ Mata Pelajaran: Matematika");
        } else {
            $mapelMat = $mapelMatExists->id_mapel;
            $this->command->info("ℹ️ Mata Pelajaran: Matematika (sudah ada)");
        }

        $mapelFisExists = DB::table('mata_pelajaran')->where('kode_mapel', 'FIS001')->first();
        if (!$mapelFisExists) {
            $mapelFis = DB::table('mata_pelajaran')->insertGetId([
                'kode_mapel' => 'FIS001',
                'nama_mapel' => 'Fisika',
            ]);
            $this->command->info("✅ Mata Pelajaran: Fisika");
        } else {
            $mapelFis = $mapelFisExists->id_mapel;
            $this->command->info("ℹ️ Mata Pelajaran: Fisika (sudah ada)");
        }

        // Buat Jadwal Pelajaran untuk Kelas X-1 IPA (gunakan Eloquent agar observer trigger)
        $jadwalMatExists = JadwalPelajaran::where('mapel_id', $mapelMat)
            ->where('kelas_id', $kelasX1->id_kelas)
            ->first();
            
        if (!$jadwalMatExists) {
            $jadwalMat = JadwalPelajaran::create([
                'tahun_ajaran_id' => $tahunAjaranAktif->id_tahun_ajaran,
                'kelas_id' => $kelasX1->id_kelas,
                'mapel_id' => $mapelMat,
                'guru_id' => $guruId,
                'hari' => 'Senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:30',
            ]);
            $this->command->info("✅ Jadwal: Matematika - X-1 IPA → Observer create 16 pertemuan");
        } else {
            $jadwalMat = $jadwalMatExists;
            $this->command->info("ℹ️ Jadwal Matematika sudah ada");
        }

        $jadwalFisExists = JadwalPelajaran::where('mapel_id', $mapelFis)
            ->where('kelas_id', $kelasX1->id_kelas)
            ->first();
            
        if (!$jadwalFisExists) {
            $jadwalFis = JadwalPelajaran::create([
                'tahun_ajaran_id' => $tahunAjaranAktif->id_tahun_ajaran,
                'kelas_id' => $kelasX1->id_kelas,
                'mapel_id' => $mapelFis,
                'guru_id' => $guruId,
                'hari' => 'Selasa',
                'jam_mulai' => '08:00',
                'jam_selesai' => '09:30',
            ]);
            $this->command->info("✅ Jadwal: Fisika - X-1 IPA → Observer create 16 pertemuan");
        } else {
            $jadwalFis = $jadwalFisExists;
            $this->command->info("ℹ️ Jadwal Fisika sudah ada");
        }

        // ============================================
        // ⚠️ PERTEMUAN TIDAK DI-SEED
        // - Untuk MATERI: Tetap ada pertemuan 1-16 (akan dibuat otomatis dari JadwalPelajaranObserver)
        // - Untuk ABSENSI: Guru yang buat sendiri via form "Buat Pertemuan Baru"
        // ============================================
        $this->command->info("ℹ️ Pertemuan untuk absensi TIDAK di-seed (guru yang buat sendiri)");

        // ============================================
        // 5️⃣ SUMMARY
        // ============================================
        $this->command->info("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("🎉 SEEDING SELESAI!");
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->command->info("📊 Total Users: " . User::count());
        $this->command->info("📅 Total Tahun Ajaran: " . TahunAjaran::count());
        $this->command->info("📚 Total Kelas: " . Kelas::count());
        $this->command->info("👨‍🏫 Total Guru: " . DB::table('guru')->count());
        $this->command->info("👨‍🎓 Total Siswa: " . DB::table('siswa')->count());
        $this->command->info("📖 Total Mata Pelajaran: " . DB::table('mata_pelajaran')->count());
        $this->command->info("📅 Total Jadwal: " . DB::table('jadwal_pelajaran')->count());
        $this->command->info("📝 Total Pertemuan: " . DB::table('pertemuan')->count());
        $this->command->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
    }
}