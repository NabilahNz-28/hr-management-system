<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';
    
    // Karena migration tidak punya updated_at, matikan timestamps otomatis jika mau,
    // tapi migration 'leave_types' punya `created_at` saja. Kita matikan default timestamps dan kelola manual.
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'type_code',
        'name',
        'name_en',
        'description',
        'max_days',
        'requires_document',
        'is_active',
    ];

    protected $casts = [
        'requires_document' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];
}
