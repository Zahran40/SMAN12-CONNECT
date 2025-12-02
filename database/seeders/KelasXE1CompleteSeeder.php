<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;

class KelasXE1CompleteSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // 1. Ambil tahun ajaran aktif
            $tahunAjaran = TahunAjaran::where('status', 'Aktif')->first();
            if (!$tahunAjaran) {
                throw new \Exception('Tidak ada tahun ajaran aktif. Jalankan seeder tahun ajaran terlebih dahulu.');
            }

            // 2. Buat atau ambil guru Ester (wali kelas + guru B. Inggris)
            $userGuru = User::where('email', 'ester.simanjuntak@guru.sman12.sch.id')->first();
            if (!$userGuru) {
                $userGuru = User::create([
                    'name' => 'Ester Donna Simanjuntak S.Pd.M.Pd',
                    'email' => 'ester.simanjuntak@guru.sman12.sch.id',
                    'password' => Hash::make('guru1234'),
                    'role' => 'guru',
                ]);
            }

            $guru = Guru::where('user_id', $userGuru->id)->first();
            if (!$guru) {
                $guru = Guru::create([
                    'user_id' => $userGuru->id,
                    'nip' => '198501152010012001',
                    'nama_lengkap' => 'Ester Donna Simanjuntak S.Pd.M.Pd',
                    'jenis_kelamin' => 'Perempuan',
                    'tempat_lahir' => 'Medan',
                    'tanggal_lahir' => '1985-01-15',
                    'alamat' => 'Jl. Pendidikan No. 12, Medan',
                    'no_telepon' => '081234567890',
                ]);
            }

            // 3. Buat kelas X-E1 (tanpa jurusan)
            $kelas = Kelas::where('nama_kelas', 'X-E1')
                ->where('tahun_ajaran_id', $tahunAjaran->id_tahun_ajaran)
                ->first();
            
            if (!$kelas) {
                $kelas = Kelas::create([
                    'nama_kelas' => 'X-E1',
                    'tingkat' => '10',
                    'jurusan' => 'Umum',
                    'wali_kelas_id' => $guru->id_guru,
                    'tahun_ajaran_id' => $tahunAjaran->id_tahun_ajaran,
                ]);
            } else {
                $kelas->update(['wali_kelas_id' => $guru->id_guru]);
            }

            // 4. Buat/ambil semua mata pelajaran yang diperlukan
            $mataPelajaranData = [
                'Upacara' => 'Upacara Bendera',
                'Geografi' => 'Geografi',
                'Penjaskes' => 'Pendidikan Jasmani dan Kesehatan',
                'Ekonomi' => 'Ekonomi',
                'Fisika' => 'Fisika',
                'B. Indonesia' => 'Bahasa Indonesia',
                'PKN' => 'Pendidikan Kewarganegaraan',
                'B. Inggris' => 'Bahasa Inggris',
                'Sejarah' => 'Sejarah Indonesia',
                'Kimia' => 'Kimia',
                'Matematika' => 'Matematika',
                'Sosiologi' => 'Sosiologi',
                'Agama' => 'Pendidikan Agama',
                'Biologi' => 'Biologi',
                'Seni Budaya' => 'Seni Budaya',
                'Informatika' => 'Informatika',
            ];

            $mapelIds = [];
            foreach ($mataPelajaranData as $kode => $nama) {
                $mapel = MataPelajaran::where('nama_mapel', $nama)->first();
                if (!$mapel) {
                    $mapel = MataPelajaran::create([
                        'kode_mapel' => strtoupper(str_replace([' ', '.'], '', $kode)),
                        'nama_mapel' => $nama,
                    ]);
                }
                $mapelIds[$kode] = $mapel->id_mapel;
            }

            // 5. Buat jadwal pelajaran untuk kelas X-E1
            $jadwalData = [
                // SENIN
                ['hari' => 'Senin', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:00:00', 'mapel' => 'Upacara'],
                ['hari' => 'Senin', 'jam_mulai' => '08:00:00', 'jam_selesai' => '08:45:00', 'mapel' => 'Geografi'],
                ['hari' => 'Senin', 'jam_mulai' => '08:45:00', 'jam_selesai' => '09:30:00', 'mapel' => 'Geografi'],
                ['hari' => 'Senin', 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'mapel' => 'Geografi'],
                ['hari' => 'Senin', 'jam_mulai' => '10:30:00', 'jam_selesai' => '11:15:00', 'mapel' => 'Penjaskes'],
                ['hari' => 'Senin', 'jam_mulai' => '11:15:00', 'jam_selesai' => '12:00:00', 'mapel' => 'Penjaskes'],
                ['hari' => 'Senin', 'jam_mulai' => '12:00:00', 'jam_selesai' => '12:45:00', 'mapel' => 'Penjaskes'],
                ['hari' => 'Senin', 'jam_mulai' => '13:45:00', 'jam_selesai' => '14:30:00', 'mapel' => 'Ekonomi'],
                ['hari' => 'Senin', 'jam_mulai' => '14:30:00', 'jam_selesai' => '15:15:00', 'mapel' => 'Fisika'],
                ['hari' => 'Senin', 'jam_mulai' => '15:15:00', 'jam_selesai' => '16:00:00', 'mapel' => 'Fisika'],
                
                // SELASA
                ['hari' => 'Selasa', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:00:00', 'mapel' => 'B. Indonesia'],
                ['hari' => 'Selasa', 'jam_mulai' => '08:00:00', 'jam_selesai' => '08:45:00', 'mapel' => 'B. Indonesia'],
                ['hari' => 'Selasa', 'jam_mulai' => '08:45:00', 'jam_selesai' => '09:30:00', 'mapel' => 'PKN'],
                ['hari' => 'Selasa', 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'mapel' => 'PKN'],
                ['hari' => 'Selasa', 'jam_mulai' => '10:30:00', 'jam_selesai' => '11:15:00', 'mapel' => 'B. Inggris'],
                ['hari' => 'Selasa', 'jam_mulai' => '11:15:00', 'jam_selesai' => '12:00:00', 'mapel' => 'B. Inggris'],
                ['hari' => 'Selasa', 'jam_mulai' => '12:00:00', 'jam_selesai' => '12:45:00', 'mapel' => 'B. Inggris'],
                ['hari' => 'Selasa', 'jam_mulai' => '13:45:00', 'jam_selesai' => '14:30:00', 'mapel' => 'Sejarah'],
                ['hari' => 'Selasa', 'jam_mulai' => '14:30:00', 'jam_selesai' => '15:15:00', 'mapel' => 'Sejarah'],
                ['hari' => 'Selasa', 'jam_mulai' => '15:15:00', 'jam_selesai' => '16:00:00', 'mapel' => 'Sejarah'],
                
                // RABU
                ['hari' => 'Rabu', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:00:00', 'mapel' => 'Kimia'],
                ['hari' => 'Rabu', 'jam_mulai' => '08:00:00', 'jam_selesai' => '08:45:00', 'mapel' => 'Kimia'],
                ['hari' => 'Rabu', 'jam_mulai' => '08:45:00', 'jam_selesai' => '09:30:00', 'mapel' => 'Kimia'],
                ['hari' => 'Rabu', 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'mapel' => 'B. Indonesia'],
                ['hari' => 'Rabu', 'jam_mulai' => '10:30:00', 'jam_selesai' => '11:15:00', 'mapel' => 'B. Indonesia'],
                ['hari' => 'Rabu', 'jam_mulai' => '11:15:00', 'jam_selesai' => '12:00:00', 'mapel' => 'Matematika'],
                ['hari' => 'Rabu', 'jam_mulai' => '12:00:00', 'jam_selesai' => '12:45:00', 'mapel' => 'Matematika'],
                ['hari' => 'Rabu', 'jam_mulai' => '13:45:00', 'jam_selesai' => '14:30:00', 'mapel' => 'Sosiologi'],
                ['hari' => 'Rabu', 'jam_mulai' => '14:30:00', 'jam_selesai' => '15:15:00', 'mapel' => 'Sosiologi'],
                ['hari' => 'Rabu', 'jam_mulai' => '15:15:00', 'jam_selesai' => '16:00:00', 'mapel' => 'Sosiologi'],
                
                // KAMIS
                ['hari' => 'Kamis', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:00:00', 'mapel' => 'Agama'],
                ['hari' => 'Kamis', 'jam_mulai' => '08:00:00', 'jam_selesai' => '08:45:00', 'mapel' => 'Agama'],
                ['hari' => 'Kamis', 'jam_mulai' => '08:45:00', 'jam_selesai' => '09:30:00', 'mapel' => 'Agama'],
                ['hari' => 'Kamis', 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'mapel' => 'Biologi'],
                ['hari' => 'Kamis', 'jam_mulai' => '10:30:00', 'jam_selesai' => '11:15:00', 'mapel' => 'Biologi'],
                ['hari' => 'Kamis', 'jam_mulai' => '11:15:00', 'jam_selesai' => '12:00:00', 'mapel' => 'Biologi'],
                ['hari' => 'Kamis', 'jam_mulai' => '12:00:00', 'jam_selesai' => '12:45:00', 'mapel' => 'Matematika'],
                ['hari' => 'Kamis', 'jam_mulai' => '13:45:00', 'jam_selesai' => '14:30:00', 'mapel' => 'Matematika'],
                ['hari' => 'Kamis', 'jam_mulai' => '14:30:00', 'jam_selesai' => '15:15:00', 'mapel' => 'Seni Budaya'],
                ['hari' => 'Kamis', 'jam_mulai' => '15:15:00', 'jam_selesai' => '16:00:00', 'mapel' => 'Seni Budaya'],
                
                // JUMAT
                ['hari' => 'Jumat', 'jam_mulai' => '07:15:00', 'jam_selesai' => '08:00:00', 'mapel' => 'Informatika'],
                ['hari' => 'Jumat', 'jam_mulai' => '08:00:00', 'jam_selesai' => '08:45:00', 'mapel' => 'Informatika'],
                ['hari' => 'Jumat', 'jam_mulai' => '08:45:00', 'jam_selesai' => '09:30:00', 'mapel' => 'Fisika'],
                ['hari' => 'Jumat', 'jam_mulai' => '09:30:00', 'jam_selesai' => '10:15:00', 'mapel' => 'Ekonomi'],
                ['hari' => 'Jumat', 'jam_mulai' => '10:30:00', 'jam_selesai' => '11:15:00', 'mapel' => 'Ekonomi'],
            ];

            foreach ($jadwalData as $jadwal) {
                // Untuk B. Inggris, gunakan guru Ester. Untuk yang lain, gunakan guru dummy
                $guruId = ($jadwal['mapel'] === 'B. Inggris') ? $guru->id_guru : $guru->id_guru;
                
                JadwalPelajaran::create([
                    'kelas_id' => $kelas->id_kelas,
                    'mapel_id' => $mapelIds[$jadwal['mapel']],
                    'guru_id' => $guruId,
                    'hari' => $jadwal['hari'],
                    'jam_mulai' => $jadwal['jam_mulai'],
                    'jam_selesai' => $jadwal['jam_selesai'],
                    'tahun_ajaran_id' => $tahunAjaran->id_tahun_ajaran,
                ]);
            }

            // 6. Ambil semua siswa yang sudah ada dari seeder sebelumnya
            $namaSiswa = [
                'Abidah Safrida Aini',
                'Cahaya Qiara Deva',
                'Chintia Auliya',
                'Fadinah Paratama',
                'Fadyan Khairunnisa',
                'Fajry Abra Idwansyah',
                'Farah Anindina',
                'Gabriella Napitupulu',
                'Gyand Pranata Sijabat',
                'Ibrahim Muhammad',
                'Ikhsan Prayoga',
                'Jansent Sihombing',
                'Jonatan Sihombing',
                'Joshua Fransisco',
                'Kayla Febiola Pasaribu',
                'Khaznah Khayla',
                'Maximus Perdana',
                'Medira Bunga Fatihah',
                'Muhammad Alfi Syahrin',
                'Muhammad Bhadika Salam',
                'Muammar Al Kahfi Siregar',
                'Muhammad Restu Yafi',
                'Nasywa Wintya Raihana',
                'Naysina Sofie Putri',
                'Prajha Batora Hutapea',
                'Priscina Lamria Joanne',
                'Pruislin Sianturi',
                'Rabi Manulang',
                'Rahel Wahyuni Hutabarat',
                'Raja Yohanes Cristiano',
                'Rianti Sirait',
                'Sava Nugraha Refsyah',
                'Stefhanie Aurelia Sinaga',
                'Tristan Jaya Christoffel',
                'Vanesha Adelin Casisila',
                'Verlita Evelyne Siahaan',
            ];

            // 7. Masukkan siswa ke kelas X-E1
            foreach ($namaSiswa as $nama) {
                $siswa = Siswa::whereHas('user', function($query) use ($nama) {
                    $query->where('name', $nama);
                })->first();

                if ($siswa) {
                    // Cek apakah sudah ada di kelas ini
                    $existingSiswaKelas = SiswaKelas::where('siswa_id', $siswa->id_siswa)
                        ->where('kelas_id', $kelas->id_kelas)
                        ->where('tahun_ajaran_id', $tahunAjaran->id_tahun_ajaran)
                        ->first();

                    if (!$existingSiswaKelas) {
                        SiswaKelas::create([
                            'siswa_id' => $siswa->id_siswa,
                            'kelas_id' => $kelas->id_kelas,
                            'tahun_ajaran_id' => $tahunAjaran->id_tahun_ajaran,
                            'status' => 'Aktif',
                        ]);
                    }

                    // Update kelas_id di tabel siswa
                    $siswa->update(['kelas_id' => $kelas->id_kelas]);
                }
            }

            DB::commit();
            
            $this->command->info('✅ Kelas X-E1 berhasil dibuat dengan lengkap!');
            $this->command->info('✅ Guru Ester Donna Simanjuntak ditetapkan sebagai wali kelas dan guru B. Inggris');
            $this->command->info('✅ 36 siswa berhasil dimasukkan ke kelas X-E1');
            $this->command->info('✅ Jadwal pelajaran lengkap berhasil dibuat (Senin-Jumat)');

        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error('❌ Error: ' . $e->getMessage());
        }
    }
}
