<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockOpname extends Model
{
    use HasFactory;

    protected $table = 'stock_opnames';

    protected $fillable = [
        'inventory_id',
        'user_id',      // ✅ pakai user_id, bukan pic_id
        'tanggal',
        'stok_sebelum',
        'stok_sesudah',
        'selisih',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}