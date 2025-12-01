<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataSiswa = [
            ['nama' => 'Abidah Safrida Aini', 'nis' => '240001', 'nisn' => '0071234001', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Cahaya Qiara Deva', 'nis' => '240002', 'nisn' => '0071234002', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Chintia Auliya', 'nis' => '240003', 'nisn' => '0071234003', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Fadinah Paratama', 'nis' => '240004', 'nisn' => '0071234004', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Fadyan Khairunnisa', 'nis' => '240005', 'nisn' => '0071234005', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Fajry Abra Idwansyah', 'nis' => '240006', 'nisn' => '0071234006', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Farah Anindina', 'nis' => '240007', 'nisn' => '0071234007', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Gabriella Napitupulu', 'nis' => '240008', 'nisn' => '0071234008', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Gyand Pranata Sijabat', 'nis' => '240009', 'nisn' => '0071234009', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Ibrahim Muhammad', 'nis' => '240010', 'nisn' => '0071234010', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Ikhsan Prayoga', 'nis' => '240011', 'nisn' => '0071234011', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Jansent Sihombing', 'nis' => '240012', 'nisn' => '0071234012', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Jonatan Sihombing', 'nis' => '240013', 'nisn' => '0071234013', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Joshua Fransisco', 'nis' => '240014', 'nisn' => '0071234014', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Kayla Febiola Pasaribu', 'nis' => '240015', 'nisn' => '0071234015', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Khaznah Khayla', 'nis' => '240016', 'nisn' => '0071234016', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Maximus Perdana', 'nis' => '240017', 'nisn' => '0071234017', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Medira Bunga Fatihah', 'nis' => '240018', 'nisn' => '0071234018', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Muhammad Alfi Syahrin', 'nis' => '240019', 'nisn' => '0071234019', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Muhammad Bhadika Salam', 'nis' => '240020', 'nisn' => '0071234020', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Muammar Al Kahfi Siregar', 'nis' => '240021', 'nisn' => '0071234021', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Muhammad Restu Yafi', 'nis' => '240022', 'nisn' => '0071234022', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Nasywa Wintya Raihana', 'nis' => '240023', 'nisn' => '0071234023', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Naysina Sofie Putri', 'nis' => '240024', 'nisn' => '0071234024', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Prajha Batora Hutapea', 'nis' => '240025', 'nisn' => '0071234025', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Priscina Lamria Joanne', 'nis' => '240026', 'nisn' => '0071234026', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Pruislin Sianturi', 'nis' => '240027', 'nisn' => '0071234027', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Rabi Manulang', 'nis' => '240028', 'nisn' => '0071234028', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Rahel Wahyuni Hutabarat', 'nis' => '240029', 'nisn' => '0071234029', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Raja Yohanes Cristiano', 'nis' => '240030', 'nisn' => '0071234030', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Rianti Sirait', 'nis' => '240031', 'nisn' => '0071234031', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Sava Nugraha Refsyah', 'nis' => '240032', 'nisn' => '0071234032', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Stefhanie Aurelia Sinaga', 'nis' => '240033', 'nisn' => '0071234033', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Tristan Jaya Christoffel', 'nis' => '240034', 'nisn' => '0071234034', 'jenis_kelamin' => 'Laki-laki'],
            ['nama' => 'Vanesha Adelin Casisila', 'nis' => '240035', 'nisn' => '0071234035', 'jenis_kelamin' => 'Perempuan'],
            ['nama' => 'Verlita Evelyne Siahaan', 'nis' => '240036', 'nisn' => '0071234036', 'jenis_kelamin' => 'Perempuan'],
        ];

        foreach ($dataSiswa as $index => $siswa) {
            $nomorUrut = $index + 1;
            $password8Digit = str_pad($nomorUrut, 8, '0', STR_PAD_LEFT); // 00000001, 00000002, dst
            
            // Buat email dari nama (lowercase, tanpa spasi)
            $email = strtolower(str_replace(' ', '', $siswa['nama'])) . '@siswa.sman12.sch.id';
            
            DB::beginTransaction();
            try {
                // 1. Buat User terlebih dahulu
                $userId = DB::table('users')->insertGetId([
                    'name' => $siswa['nama'],
                    'email' => $email,
                    'password' => Hash::make($password8Digit),
                    'role' => 'siswa',
                    'is_active' => true,
                    'must_change_password' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // 2. Buat data Siswa
                DB::table('siswa')->insert([
                    'user_id' => $userId,
                    'nis' => $siswa['nis'],
                    'nisn' => $siswa['nisn'],
                    'nama_lengkap' => $siswa['nama'],
                    'jenis_kelamin' => $siswa['jenis_kelamin'],
                    'email' => $email,
                    'tempat_lahir' => 'Medan',
                    'tgl_lahir' => '2008-01-01',
                    'agama' => 'Islam',
                    'alamat' => 'Medan',
                    'no_telepon' => '0812' . str_pad($nomorUrut, 8, '0', STR_PAD_LEFT),
                ]);

                DB::commit();
                
                $this->command->info("✓ Siswa {$nomorUrut}/36: {$siswa['nama']} (Password: {$password8Digit})");
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("✗ Gagal membuat siswa: {$siswa['nama']} - Error: {$e->getMessage()}");
            }
        }

        $this->command->info("\n========================================");
        $this->command->info("📊 SUMMARY");
        $this->command->info("========================================");
        $this->command->info("Total Siswa: 36 siswa");
        $this->command->info("\n📝 LOGIN CREDENTIALS:");
        $this->command->info("Email format: [nama_tanpa_spasi]@siswa.sman12.sch.id");
        $this->command->info("Password: 8 digit (00000001 - 00000036)");
        $this->command->info("\nContoh:");
        $this->command->info("- Email: abidahsafridaaini@siswa.sman12.sch.id");
        $this->command->info("- Password: 00000001");
        $this->command->info("========================================\n");
    }
}
