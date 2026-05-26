<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leaves';
    protected $primaryKey = 'id';

    protected $fillable = [
        'karyawan_id',
        'jenis',
        'jenis_detail',   // tambahan: detail jenis dari dropdown blade
        'start_date',
        'end_date',       // nullable (izin 1 hari tidak perlu end_date)
        'keterangan',
        'file_path',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // ===== RELASI =====

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }
}