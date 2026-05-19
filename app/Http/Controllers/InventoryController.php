<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\StockOpname;
use App\Models\TransferStock;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    // ─── Helper: hanya role 'pic' yang boleh akses ───────────────
    private function authorizeOnlyPic()
    {
        if (auth()->user()->role !== 'pic') {
            abort(403, 'Hanya PIC yang bisa mengakses halaman ini.');
        }
    }

    // ─────────────────────────────────────────────────────────────
    // STOCK OPNAME — tampilkan semua barang
    // View: stock-opname (filter kategori pakai JS, bukan server-side)
    // ─────────────────────────────────────────────────────────────
    public function stockOpname()
    {
        $this->authorizeOnlyPic();

        // Kirim semua barang, filter dilakukan JS di view
        $barang = Inventory::orderBy('nama_barang')->get();

        return view('inventories.inventory.stock-opname', compact('barang'));
    }

    // ─────────────────────────────────────────────────────────────
    // TAMBAH BARANG — form tambah barang baru
    // View: tambah-barang-inventory
    // Field: nama_barang, kategori, jumlah_pcs, jumlah_carton, catatan
    // ─────────────────────────────────────────────────────────────
    public function create()
    {
        $this->authorizeOnlyPic();

        return view('inventories.inventory.tambah-barang');
    }

    public function store(Request $request)
    {
        $this->authorizeOnlyPic();

        $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'kategori'      => 'required|in:eco,fragile,plastic,thermal,carton,other',
            'jumlah_pcs'    => 'required|integer|min:0',
            'jumlah_carton' => 'nullable|integer|min:0',
            'catatan'       => 'nullable|string|max:500',
        ]);

        Inventory::create([
            'nama_barang'   => $request->nama_barang,
            'kategori'      => $request->kategori,
            'stok_fisik'    => $request->jumlah_pcs,
            'stok_carton'   => $request->jumlah_carton ?? 0,
            'catatan'       => $request->catatan,
        ]);

        return redirect()->route('inventories.inventory.stock.opname')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    // ─────────────────────────────────────────────────────────────
    // INPUT OPNAME — form multi-produk
    // View: input-opname
    // Field: tanggal, catatan, produk (nama text), jumlah
    // ─────────────────────────────────────────────────────────────
    public function inputOpname()
    {
        $this->authorizeOnlyPic();

        $barang = Inventory::orderBy('nama_barang')->get();

        return view('inventories.inventory.input-opname', compact('barang'));
    }

    public function simpanOpname(Request $request)
    {
        $this->authorizeOnlyPic();

        $request->validate([
            'tanggal'        => 'required|date',
            'catatan'        => 'nullable|string|max:500',
            'produk'         => 'required|array|min:1',
            'produk.*.nama'  => 'required|string',
            'produk.*.jumlah'=> 'required|integer|min:1',
        ]);

        foreach ($request->produk as $item) {
            // Cari barang berdasarkan nama
            $inventory = Inventory::where('nama_barang', $item['nama'])->first();

            if ($inventory) {
                $stokSebelum = $inventory->stok_fisik;
                $inventory->update(['stok_fisik' => $item['jumlah']]);

                StockOpname::create([
                    'inventory_id' => $inventory->id,
                    'user_id'      => auth()->id(), // ✅
                    'tanggal'      => $request->tanggal,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $item['jumlah'],
                    'selisih'      => $item['jumlah'] - $stokSebelum,
                    'catatan'      => $request->catatan,
                ]);
            }
        }

        return redirect()->route('inventories.inventory.stock.opname')
            ->with('success', 'Opname berhasil disimpan.');
    }

    // ─────────────────────────────────────────────────────────────
    // TRANSFER STOCK
    // View: transfer-stock
    // Route form action: inventories.transfer-stock.store
    // Field: tanggal, gudang_tujuan, barang_id, jumlah, satuan, catatan
    // ─────────────────────────────────────────────────────────────
    public function transferStock()
    {
        $this->authorizeOnlyPic();

        $barang = Inventory::orderBy('nama_barang')->get();

        $transfer_terbaru = TransferStock::with('barang')
            ->where('pic_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->take(10)
            ->get();

        return view('inventories.inventory.transfer-stock', compact('barang', 'transfer_terbaru'));
    }

    public function simpanTransfer(Request $request)
    {
        $this->authorizeOnlyPic();

        $request->validate([
            'tanggal'       => 'required|date',
            'gudang_tujuan' => 'required|string|max:255',
            'barang_id'     => 'required|exists:inventories,id',
            'jumlah'        => 'required|integer|min:1',
            'satuan'        => 'required|in:pcs,carton,box,pack',
            'catatan'       => 'nullable|string|max:500',
        ]);

        $inventory = Inventory::findOrFail($request->barang_id);

        if ($inventory->stok_fisik < $request->jumlah) {
            return back()
                ->withErrors(['jumlah' => 'Stok tidak mencukupi untuk transfer.'])
                ->withInput();
        }

        $inventory->decrement('stok_fisik', $request->jumlah);

        TransferStock::create([
            'user_id'   => auth()->id(), // ✅
            'barang_id' => $request->barang_id,
            'tanggal'   => $request->tanggal,
            'ke_gudang' => $request->gudang_tujuan,
            'jumlah'    => $request->jumlah,
            'satuan'    => $request->satuan,
            'catatan'   => $request->catatan,
            'status'    => 'Selesai',
        ]);

        return redirect()->route('inventories.inventory.transfer.stock')
            ->with('success', 'Transfer berhasil disimpan.');
    }

    // ─────────────────────────────────────────────────────────────
    // LAPORAN OPNAME
    // View: laporan-opname
    // Kolom: tanggal, nama_barang, kategori, stok
    // ─────────────────────────────────────────────────────────────
    public function laporanOpname()
    {
        $this->authorizeOnlyPic();

        $laporan = StockOpname::with('inventory')
            ->where('user_id', auth()->id()) // ✅
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('inventories.inventory.laporan-opname', compact('laporan'));
    }

    // ─────────────────────────────────────────────────────────────
    // LAPORAN TRANSFER
    // View: laporan-transfer
    // Kolom: tanggal, barang, gudang_utama (default), ke_gudang, jumlah_pcs, catatan
    // ─────────────────────────────────────────────────────────────
    public function laporanTransfer()
    {
        $this->authorizeOnlyPic();

        $laporan = TransferStock::with('barang')
            ->where('user_id', auth()->id()) // ✅
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('inventories.inventory.laporan-transfer', compact('laporan'));
    }
}