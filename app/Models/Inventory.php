<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    protected $fillable = [
        'nama_barang',
        'kategori',
        'stok_fisik',
        'stok_carton',
        'catatan',
        // ✅ TIDAK ADA pic_id di sini
    ];

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'inventory_id');
    }

    public function transferStocks()
    {
        return $this->hasMany(TransferStock::class, 'barang_id');
    }
}