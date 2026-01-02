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
            User::create([
                'name' => 'Orang Tua ',
                'email' => 'orangtua@sman12.com',
                'password' => Hash::make('orangtua1'),
                'role' => 'orangtua',
                'reference_id' => $siswaFirst->id, // Terhubung ke siswa pertama
                'is_active' => true,
                'must_change_password' => false,
            ]);
            
            $this->command->info('✅ Akun Orang Tua berhasil dibuat:');
            $this->command->info('   Email: orangtua@demo.com');
            $this->command->info('   Password: password123');
            $this->command->info('   Terhubung dengan siswa: ' . $siswaFirst->nama_lengkap);
        } else {
            $this->command->warn('⚠️  Tidak ada data siswa, akun orangtua dibuat tanpa reference_id');
            User::create([
                'name' => 'Orang Tua Demo',
                'email' => 'orangtua@demo.com',
                'password' => Hash::make('password123'),
                'role' => 'orangtua',
                'reference_id' => null,
                'is_active' => true,
                'must_change_password' => false,
            ]);
        }

        // 2. AKUN KEPALA SEKOLAH
        User::create([
            'name' => 'Dr. Kepala Sekolah',
            'email' => 'kepsek@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'kepsek',
            'reference_id' => null,
            'is_active' => true,
            'must_change_password' => false,
        ]);
        
        $this->command->info('✅ Akun Kepala Sekolah berhasil dibuat:');
        $this->command->info('   Email: kepsek@demo.com');
        $this->command->info('   Password: password123');

        // 3. AKUN BENDAHARA
        User::create([
            'name' => 'Bendahara Sekolah',
            'email' => 'bendahara@demo.com',
            'password' => Hash::make('password123'),
            'role' => 'bendahara',
            'reference_id' => null,
            'is_active' => true,
            'must_change_password' => false,
        ]);
        
        $this->command->info('✅ Akun Bendahara berhasil dibuat:');
        $this->command->info('   Email: bendahara@demo.com');
        $this->command->info('   Password: password123');
        
        $this->command->info('');
        $this->command->info('================================================');
        $this->command->info('🎉 SEEDER ROLE BARU BERHASIL DIJALANKAN!');
        $this->command->info('================================================');
        $this->command->info('Akun yang dibuat:');
        $this->command->info('1. Orang Tua    : orangtua@demo.com');
        $this->command->info('2. Kepala Sekolah: kepsek@demo.com');
        $this->command->info('3. Bendahara    : bendahara@demo.com');
        $this->command->info('Password semua  : password123');
        $this->command->info('================================================');
    }
}
