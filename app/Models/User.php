<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * Get database connection name based on user role
     */
    public function getDatabaseConnection(): string
    {
        return match($this->role) {
            'admin' => 'mysql_admin',
            'guru' => 'mysql_guru',
            'siswa' => 'mysql_siswa',
            default => 'mysql',
        };
    }
}

