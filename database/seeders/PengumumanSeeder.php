<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil admin user untuk author_id
        $admin = DB::table('users')->where('role', 'admin')->first();
        
        if (!$admin) {
            echo "⚠️ Admin user tidak ditemukan. Jalankan DatabaseSeeder terlebih dahulu.\n";
            return;
        }

        $pengumuman = [
            [
                'judul' => 'Rapat Bersama Siswa dan Dewan Guru',
                'isi_pengumuman' => 'Diberitahukan kepada seluruh siswa dan dewan guru SMA Negeri 12 Medan bahwa akan diadakan rapat bersama yang bertujuan untuk mempererat komunikasi antara pihak guru dan siswa dalam membahas berbagai hal yang berkaitan dengan kegiatan sekolah, baik dalam bidang akademik maupun non-akademik. Melalui rapat ini diharapkan tercipta kerja sama yang lebih baik, saling pengertian, serta peningkatan kualitas lingkungan belajar di sekolah. Rapat ini juga menjadi wadah bagi siswa untuk menyampaikan aspirasi, saran, dan gagasan yang membangun demi kemajuan bersama.',
                'tgl_publikasi' => '2025-11-10',
                'hari' => 'Minggu',
                'target_role' => 'Semua',
                'author_id' => $admin->id,
                'status' => 'aktif',
                'tanggal_dibuat' => Carbon::parse('2025-11-10 08:00:00'),
            ],
            [
                'judul' => 'Ujian Tengah Semester Ganjil 2024/2025',
                'isi_pengumuman' => 'Dengan hormat, diberitahukan kepada seluruh siswa SMA Negeri 12 Medan bahwa Ujian Tengah Semester (UTS) Ganjil tahun ajaran 2024/2025 akan dilaksanakan pada tanggal 20-24 November 2025. Siswa diharapkan untuk mempersiapkan diri dengan baik, membawa perlengkapan ujian yang diperlukan, dan datang tepat waktu. Jadwal detail ujian akan diumumkan melalui pengumuman terpisah.',
                'tgl_publikasi' => '2025-11-15',
                'hari' => 'Jumat',
                'target_role' => 'siswa',
                'author_id' => $admin->id,
                'status' => 'aktif',
                'tanggal_dibuat' => Carbon::parse('2025-11-15 09:00:00'),
            ],
            [
                'judul' => 'Rapat Koordinasi Guru - Evaluasi Pembelajaran',
                'isi_pengumuman' => 'Kepada Yth. Bapak/Ibu Guru SMA Negeri 12 Medan, dimohon kehadirannya dalam Rapat Koordinasi Guru yang akan membahas evaluasi pembelajaran semester ganjil dan persiapan semester genap. Rapat akan dilaksanakan pada hari Senin, 18 November 2025 pukul 13.00 WIB di Ruang Aula. Kehadiran semua guru sangat diharapkan.',
                'tgl_publikasi' => '2025-11-16',
                'hari' => 'Sabtu',
                'target_role' => 'guru',
                'author_id' => $admin->id,
                'status' => 'aktif',
                'tanggal_dibuat' => Carbon::parse('2025-11-16 07:30:00'),
            ],
        ];

        foreach ($pengumuman as $item) {
            DB::table('pengumuman')->insert($item);
        }

        echo "✅ Berhasil membuat 3 sample pengumuman\n";
    }
}
