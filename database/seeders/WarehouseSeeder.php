<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\TransferStock;
use App\Models\StockOpname;
use App\Models\User;
use Carbon\Carbon;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user PIC untuk dijadikan user_id pada transaksi gudang
        $user = User::where('role', 'pic')->first();
        if (!$user) {
            // Fallback ke user pertama (walaupun harusnya pic)
            $user = User::first();
        }
        $userId = $user->id;

        // 1. Insert 20 Barang (JNT style)
        // Valid kategori: ['eco', 'fragile', 'plastic', 'thermal', 'carton', 'other']
        $barangs = [
            ['nama_barang' => 'Kardus Packing Kecil', 'kategori' => 'carton', 'stok_fisik' => 1500, 'stok_carton' => 50],
            ['nama_barang' => 'Kardus Packing Sedang', 'kategori' => 'carton', 'stok_fisik' => 1200, 'stok_carton' => 40],
            ['nama_barang' => 'Kardus Packing Besar', 'kategori' => 'carton', 'stok_fisik' => 800, 'stok_carton' => 20],
            ['nama_barang' => 'Lakban Bening JNT', 'kategori' => 'plastic', 'stok_fisik' => 300, 'stok_carton' => 10],
            ['nama_barang' => 'Lakban Coklat JNT', 'kategori' => 'plastic', 'stok_fisik' => 400, 'stok_carton' => 12],
            ['nama_barang' => 'Lakban Fragile', 'kategori' => 'fragile', 'stok_fisik' => 250, 'stok_carton' => 8],
            ['nama_barang' => 'Plastik HD JNT Merah Kecil', 'kategori' => 'plastic', 'stok_fisik' => 5000, 'stok_carton' => 100],
            ['nama_barang' => 'Plastik HD JNT Merah Sedang', 'kategori' => 'plastic', 'stok_fisik' => 4500, 'stok_carton' => 90],
            ['nama_barang' => 'Plastik HD JNT Merah Besar', 'kategori' => 'plastic', 'stok_fisik' => 3000, 'stok_carton' => 60],
            ['nama_barang' => 'Kertas Resi Thermal A6', 'kategori' => 'thermal', 'stok_fisik' => 500, 'stok_carton' => 25],
            ['nama_barang' => 'Bubble Wrap Hitam 50m', 'kategori' => 'plastic', 'stok_fisik' => 100, 'stok_carton' => 0],
            ['nama_barang' => 'Bubble Wrap Putih 50m', 'kategori' => 'plastic', 'stok_fisik' => 150, 'stok_carton' => 0],
            ['nama_barang' => 'Karung Goni JNT', 'kategori' => 'eco', 'stok_fisik' => 600, 'stok_carton' => 15],
            ['nama_barang' => 'Segel Plastik (Cable Tie)', 'kategori' => 'plastic', 'stok_fisik' => 10000, 'stok_carton' => 50],
            ['nama_barang' => 'Timbangan Digital 50kg', 'kategori' => 'other', 'stok_fisik' => 25, 'stok_carton' => 0],
            ['nama_barang' => 'Timbangan Duduk 150kg', 'kategori' => 'other', 'stok_fisik' => 10, 'stok_carton' => 0],
            ['nama_barang' => 'Scanner Barcode Wireless', 'kategori' => 'other', 'stok_fisik' => 45, 'stok_carton' => 0],
            ['nama_barang' => 'Trolley Lipat', 'kategori' => 'other', 'stok_fisik' => 20, 'stok_carton' => 0],
            ['nama_barang' => 'Pallet Kayu Standar', 'kategori' => 'other', 'stok_fisik' => 120, 'stok_carton' => 0],
            ['nama_barang' => 'Pallet Plastik', 'kategori' => 'plastic', 'stok_fisik' => 80, 'stok_carton' => 0],
        ];

        $insertedBarangs = [];
        foreach ($barangs as $barang) {
            $insertedBarangs[] = Inventory::create([
                'nama_barang' => $barang['nama_barang'],
                'kategori'    => $barang['kategori'],
                'stok_fisik'  => $barang['stok_fisik'],
                'stok_carton' => $barang['stok_carton'],
                'catatan'     => 'Stok awal gudang utama',
            ]);
        }

        // 2. Insert 15 Transfer Stock
        $gudangs = ['Gudang Bekasi', 'Gudang Jakarta Barat', 'Gudang Jakarta Selatan', 'Gudang Kosambi'];
        // Valid satuan: ['pcs', 'carton', 'box', 'pack']
        $satuans = ['pcs', 'carton', 'box', 'pack'];
        $statuses = ['Selesai', 'Selesai', 'Selesai'];

        for ($i = 0; $i < 15; $i++) {
            $barang = $insertedBarangs[array_rand($insertedBarangs)];
            TransferStock::create([
                'user_id'    => $userId,
                'barang_id'  => $barang->id,
                'tanggal'    => Carbon::now()->subDays(rand(0, 14))->format('Y-m-d'),
                'ke_gudang'  => $gudangs[array_rand($gudangs)],
                'jumlah'     => rand(10, 100),
                'satuan'     => $satuans[array_rand($satuans)],
                'status'     => $statuses[array_rand($statuses)],
                'catatan'    => 'Transfer stok operasional cabang',
            ]);
        }

        // 3. Insert 15 Stock Opname
        for ($i = 0; $i < 15; $i++) {
            $barang = $insertedBarangs[array_rand($insertedBarangs)];
            $stokSebelum = $barang->stok_fisik;
            $selisih = rand(-10, 10);
            $stokSesudah = max(0, $stokSebelum + $selisih);

            StockOpname::create([
                'inventory_id' => $barang->id,
                'user_id'      => $userId,
                'tanggal'      => Carbon::now()->subDays(rand(0, 7))->format('Y-m-d'),
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'selisih'      => $stokSesudah - $stokSebelum,
                'catatan'      => 'Opname mingguan rutin',
            ]);
        }
    }
}
