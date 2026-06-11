<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * Kolom dasar (auth): name, email, password, role
     * Kolom profile (dari profile.blade.php):
     *   nik, departemen, jabatan, no_hp, alamat, tgl_bergabung, foto_profile
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        // Kolom profile karyawan
        'nik',
        'departemen',
        'jabatan',
        'no_hp',
        'alamat',
        'tgl_bergabung',
        'foto_profile',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'tgl_bergabung'     => 'date',
    ];

    // ===== RELASI =====

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'karyawan_id');
    }
}
