<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;

class RoleBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. AKUN ORANG TUA
        // Ambil siswa pertama untuk dijadikan reference
        $siswaFirst = Siswa::first();
        
        if ($siswaFirst) {
            User::firstOrCreate(
                ['email' => 'orangtua@sman12.com'],
                [
                    'name' => 'Orang Tua ',
                    'password' => Hash::make('orangtua1'),
                    'role' => 'orangtua',
                    'reference_id' => $siswaFirst->id_siswa,
                    'is_active' => true,
                    'must_change_password' => false,
                ]
            );
            
            $this->command->info('✅ Akun Orang Tua berhasil dibuat:');
            $this->command->info('   Email: orangtua@sman12.com');
            $this->command->info('   Password: orangtua1');
            $this->command->info('   Terhubung dengan siswa: ' . $siswaFirst->nama_lengkap);
        } else {
            $this->command->warn('⚠️  Tidak ada data siswa, akun orangtua dibuat tanpa reference_id');
            User::firstOrCreate(
                ['email' => 'orangtua@sman12.com'],
                [
                    'name' => 'Orang Tua Demo',
                    'password' => Hash::make('orangtua1'),
                    'role' => 'orangtua',
                    'reference_id' => null,
                    'is_active' => true,
                    'must_change_password' => false,
                ]
            );
        }

        // 2. AKUN KEPALA SEKOLAH
        User::firstOrCreate(
            ['email' => 'kepsek@sman12.com'],
            [
                'name' => 'Dr. Kepala Sekolah',
                'password' => Hash::make('kepsek1'),
                'role' => 'kepsek',
                'reference_id' => null,
                'is_active' => true,
                'must_change_password' => false,
            ]
        );
        
        $this->command->info('✅ Akun Kepala Sekolah berhasil dibuat:');
        $this->command->info('   Email: kepsek@sman12.com');
        $this->command->info('   Password: kepsek1');

        // 3. AKUN BENDAHARA
        User::firstOrCreate(
            ['email' => 'bendahara@sman12.com'],
            [
                'name' => 'Bendahara Sekolah',
                'password' => Hash::make('bendahara1'),
                'role' => 'bendahara',
                'reference_id' => null,
                'is_active' => true,
                'must_change_password' => false,
            ]
        );
        
        $this->command->info('✅ Akun Bendahara berhasil dibuat:');
        $this->command->info('   Email: bendahara@sman12.com');
        $this->command->info('   Password: bendahara1');
        
        $this->command->info('');
        $this->command->info('================================================');
        $this->command->info('🎉 SEEDER ROLE BARU BERHASIL DIJALANKAN!');
        $this->command->info('================================================');
        $this->command->info('Akun yang dibuat:');
        $this->command->info('1. Orang Tua    : orangtua@sman12.com   | Password: orangtua1');
        $this->command->info('2. Kepala Sekolah: kepsek@sman12.com    | Password: kepsek1');
        $this->command->info('3. Bendahara    : bendahara@sman12.com  | Password: bendahara1');
        $this->command->info('================================================');
    }
}
