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

    protected $appends = [
        'hadir_count',
        'izin_count',
        'cuti_count',
        'terlambat_count',
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

    // ===== ACCESSORS STATISTIK BULAN INI =====

    public function getHadirCountAttribute()
    {
        $awal  = \Carbon\Carbon::now()->startOfMonth();
        $akhir = \Carbon\Carbon::now()->endOfMonth();

        return $this->attendances()
            ->where('attendance_type', 'masuk')
            ->whereBetween('attendance_time', [$awal, $akhir])
            ->get()
            ->groupBy(fn ($a) => \Carbon\Carbon::parse($a->attendance_time)->toDateString())
            ->count();
    }

    public function getTerlambatCountAttribute()
    {
        return 0; // Logika keterlambatan dihapus
    }

    public function getIzinCountAttribute()
    {
        $awal  = \Carbon\Carbon::now()->startOfMonth();
        $akhir = \Carbon\Carbon::now()->endOfMonth();

        $leaves = $this->leaves()
            ->where('status', 'approved')
            ->where('jenis', 'izin')
            ->whereDate('start_date', '<=', $akhir->toDateString())
            ->where(function ($q) use ($awal) {
                $q->whereDate('end_date', '>=', $awal->toDateString())
                  ->orWhere(function ($q2) use ($awal) {
                      $q2->whereNull('end_date')
                         ->whereDate('start_date', '>=', $awal->toDateString());
                  });
            })
            ->get();

        return $leaves->sum(function ($item) use ($awal, $akhir) {
            $start = \Carbon\Carbon::parse($item->start_date)->max($awal);
            $end = $item->end_date ? \Carbon\Carbon::parse($item->end_date)->min($akhir) : $start;
            return max(1, $start->diffInDays($end) + 1);
        });
    }

    public function getCutiCountAttribute()
    {
        $awal  = \Carbon\Carbon::now()->startOfMonth();
        $akhir = \Carbon\Carbon::now()->endOfMonth();

        $leaves = $this->leaves()
            ->where('status', 'approved')
            ->where('jenis', 'cuti')
            ->whereDate('start_date', '<=', $akhir->toDateString())
            ->where(function ($q) use ($awal) {
                $q->whereDate('end_date', '>=', $awal->toDateString())
                  ->orWhere(function ($q2) use ($awal) {
                      $q2->whereNull('end_date')
                         ->whereDate('start_date', '>=', $awal->toDateString());
                  });
            })
            ->get();

        return $leaves->sum(function ($item) use ($awal, $akhir) {
            $start = \Carbon\Carbon::parse($item->start_date)->max($awal);
            $end = $item->end_date ? \Carbon\Carbon::parse($item->end_date)->min($akhir) : $start;
            return max(1, $start->diffInDays($end) + 1);
        });
    }
}
