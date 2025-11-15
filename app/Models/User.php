<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'reference_id',
        'last_login',
        'is_active',
        'must_change_password',
        'db_user',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'must_change_password' => 'boolean',
            'last_login' => 'datetime',
        ];
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }

    // ============================================
    // EVENTS - Auto Grant Management
    // ============================================

    protected static function booted()
    {
        // Setelah user baru dibuat, apply MySQL grants
        static::created(function ($user) {
            if ($user->is_active) {
                $user->createDatabaseUser();
                $user->applyRoleGrants();
            }
        });

        // Saat role berubah, update grants
        static::updated(function ($user) {
            if ($user->isDirty('role') && $user->is_active) {
                $user->revokeAllGrants();
                $user->applyRoleGrants();
            }
            
            // Jika user di-nonaktifkan, revoke grants
            if ($user->isDirty('is_active') && !$user->is_active) {
                $user->revokeAllGrants();
            }
            
            // Jika user diaktifkan kembali
            if ($user->isDirty('is_active') && $user->is_active && !$user->db_user) {
                $user->createDatabaseUser();
                $user->applyRoleGrants();
            }
        });

        // Saat user dihapus, drop MySQL user
        static::deleting(function ($user) {
            $user->dropDatabaseUser();
        });
    }

    // ============================================
    // GRANT MANAGEMENT METHODS
    // ============================================

    public function createDatabaseUser()
    {
        try {
            $username = strtolower($this->role) . '_user_' . $this->id;
            $password = Str::random(16);
            
            $this->update(['db_user' => $username]);
            DB::statement("CREATE USER IF NOT EXISTS '{$username}'@'localhost' IDENTIFIED BY '{$password}'");
            
            Log::info("Database user created: {$username}");
        } catch (\Exception $e) {
            Log::error("Error creating database user: " . $e->getMessage());
        }
    }

    public function applyRoleGrants()
    {
        $username = $this->db_user;
        if (!$username) return;

        try {
            $database = env('DB_DATABASE', 'sman12-connect');
            
            switch ($this->role) {
                case 'siswa':
                    $this->applySiswaGrants($username, $database);
                    break;
                case 'guru':
                    $this->applyGuruGrants($username, $database);
                    break;
                case 'admin':
                    $this->applyAdminGrants($username, $database);
                    break;
            }
            
            DB::statement("FLUSH PRIVILEGES");
            Log::info("Grants applied for {$username} with role: {$this->role}");
        } catch (\Exception $e) {
            Log::error("Error applying grants: " . $e->getMessage());
        }
    }

    private function applySiswaGrants($username, $database)
    {
        $grants = [
            "GRANT SELECT ON `{$database}`.`materi` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`tugas` TO '{$username}'@'localhost'",
            "GRANT SELECT, INSERT, UPDATE ON `{$database}`.`detail_tugas` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`nilai` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`detail_absensi` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`pembayaran_spp` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`jadwal_pelajaran` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`pengumuman` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`siswa` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`kelas` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`pertemuan` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`tahun_ajaran` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`mata_pelajaran` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`users` TO '{$username}'@'localhost'",
        ];

        foreach ($grants as $grant) DB::statement($grant);
    }

    private function applyGuruGrants($username, $database)
    {
        $grants = [
            "GRANT SELECT, INSERT, UPDATE, DELETE ON `{$database}`.`materi` TO '{$username}'@'localhost'",
            "GRANT SELECT, INSERT, UPDATE, DELETE ON `{$database}`.`tugas` TO '{$username}'@'localhost'",
            "GRANT SELECT, UPDATE ON `{$database}`.`detail_tugas` TO '{$username}'@'localhost'",
            "GRANT SELECT, INSERT, UPDATE, DELETE ON `{$database}`.`nilai` TO '{$username}'@'localhost'",
            "GRANT SELECT, INSERT, UPDATE, DELETE ON `{$database}`.`detail_absensi` TO '{$username}'@'localhost'",
            "GRANT SELECT, INSERT, UPDATE, DELETE ON `{$database}`.`pertemuan` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`siswa` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`kelas` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`jadwal_pelajaran` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`pengumuman` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`guru` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`tahun_ajaran` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`mata_pelajaran` TO '{$username}'@'localhost'",
            "GRANT SELECT ON `{$database}`.`users` TO '{$username}'@'localhost'",
        ];

        foreach ($grants as $grant) DB::statement($grant);
    }

    private function applyAdminGrants($username, $database)
    {
        DB::statement("GRANT ALL PRIVILEGES ON `{$database}`.* TO '{$username}'@'localhost'");
    }

    public function revokeAllGrants()
    {
        $username = $this->db_user;
        if (!$username) return;

        try {
            $database = env('DB_DATABASE', 'sman12-connect');
            DB::statement("REVOKE ALL PRIVILEGES ON `{$database}`.* FROM '{$username}'@'localhost'");
            DB::statement("FLUSH PRIVILEGES");
            Log::info("All grants revoked for: {$username}");
        } catch (\Exception $e) {
            Log::error("Error revoking grants: " . $e->getMessage());
        }
    }

    public function dropDatabaseUser()
    {
        $username = $this->db_user;
        if (!$username) return;

        try {
            DB::statement("DROP USER IF EXISTS '{$username}'@'localhost'");
            DB::statement("FLUSH PRIVILEGES");
            Log::info("Database user dropped: {$username}");
        } catch (\Exception $e) {
            Log::error("Error dropping database user: " . $e->getMessage());
        }
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles)
    {
        return in_array($this->role, $roles);
    }

    public function isActive()
    {
        return $this->is_active === true;
    }

    public function getIdentifier()
    {
        if ($this->role === 'guru' && $this->guru) {
            return $this->guru->nip;
        }
        
        if ($this->role === 'siswa' && $this->siswa) {
            return $this->siswa->nisn;
        }
        
        return $this->email;
    }

    public function getFullName()
    {
        if ($this->role === 'guru' && $this->guru) {
            return $this->guru->nama_lengkap;
        }
        
        if ($this->role === 'siswa' && $this->siswa) {
            return $this->siswa->nama_lengkap;
        }
        
        return $this->name;
    }
}
