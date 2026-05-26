<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    // Migration hanya punya created_at, tanpa updated_at
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'user_id',
        'employee_name',
        'attendance_type',
        'photo',
        'latitude',
        'longitude',
        'address',
        'attendance_time',
    ];

    protected $casts = [
        'latitude'        => 'decimal:8',
        'longitude'       => 'decimal:8',
        'attendance_time' => 'datetime',
        'created_at'      => 'datetime',
    ];

    // ===== RELASI =====

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}