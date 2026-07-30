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
    public function stockOpname(Request $request)
    {
        $this->authorizeOnlyPic();

        $query = Inventory::orderBy('nama_barang');
        
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }
        $barang = $query->paginate(10)->withQueryString();

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

        return redirect()->route('inventory.stock-opname')
            ->with('success', 'Produk berhasil ditambahkan.');
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

        return redirect()->route('inventory.stock-opname')
            ->with('success', 'Opname berhasil dibuat.');
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
            ->where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->paginate(10)->withQueryString();

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

        return redirect()->route('inventory.transfer-stock')
            ->with('success', 'Transfer stock berhasil dibuat.');
    }

    // ─────────────────────────────────────────────────────────────
    // LAPORAN OPNAME
    // View: laporan-opname
    // Kolom: tanggal, nama_barang, kategori, stok
    // ─────────────────────────────────────────────────────────────
    // LAPORAN OPNAME (Grouped by Date as 1 Invoice)
    // ─────────────────────────────────────────────────────────────
    public function laporanOpname(Request $request)
    {
        $this->authorizeOnlyPic();

        $raw = StockOpname::with('inventory')
            ->where('user_id', auth()->id())
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('inventory', fn ($q2) => $q2->where('kategori', $request->kategori)))
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $raw->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d'))
            ->map(function ($items, $dateKey) {
                return [
                    'invoice_no'    => 'INV-OPN-' . \Carbon\Carbon::parse($dateKey)->format('Ymd'),
                    'tanggal'       => $dateKey,
                    'items'         => $items,
                    'item_count'    => $items->count(),
                    'produk_names'  => $items->pluck('inventory.nama_barang')->filter()->unique()->implode(', '),
                    'kategori_list' => $items->pluck('inventory.kategori')->filter()->unique()->map(fn($k) => ucfirst($k))->implode(', '),
                    'total_selisih' => $items->sum('selisih'),
                    'catatan'       => $items->pluck('catatan')->filter()->unique()->implode('; '),
                ];
            })->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $laporan = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('inventories.laporan.laporan-opname', compact('laporan'));
    }

    public function batalkanOpname(Request $request)
    {
        $this->authorizeOnlyPic();
        $request->validate(['tanggal' => 'required|date']);

        $items = StockOpname::with('inventory')
            ->where('user_id', auth()->id())
            ->whereDate('tanggal', $request->tanggal)
            ->get();

        foreach ($items as $item) {
            if ($item->inventory) {
                $item->inventory->update(['stok_fisik' => $item->stok_sebelum]);
            }
            $item->delete();
        }

        return redirect()->route('inventory.laporan-opname')
            ->with('success', 'Transaksi opname tanggal ' . \Carbon\Carbon::parse($request->tanggal)->format('d M Y') . ' berhasil dibatalkan dan stok dikembalikan.');
    }

    public function exportOpnameExcel(Request $request)
    {
        $this->authorizeOnlyPic();

        $items = StockOpname::with('inventory')
            ->where('user_id', auth()->id())
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('kategori'), fn ($q) => $q->whereHas('inventory', fn ($q2) => $q2->where('kategori', $request->kategori)))
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = "Laporan_Stock_Opname_" . date('Y-m-d') . ".xls";
        headers_sent() || header("Content-Type: application/vnd.ms-excel");
        headers_sent() || header("Content-Disposition: attachment; filename=\"$filename\"");

        echo '<table border="1">';
        echo '<tr style="background:#1e293b;color:#ffffff;"><th>No</th><th>Tanggal</th><th>Nama Barang</th><th>Kategori</th><th>Stok Sebelum</th><th>Stok Sesudah</th><th>Selisih</th><th>Catatan</th></tr>';
        foreach ($items as $index => $row) {
            $tgl = \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y');
            $nama = $row->inventory->nama_barang ?? '-';
            $kat = ucfirst($row->inventory->kategori ?? '-');
            echo "<tr><td>".($index+1)."</td><td>{$tgl}</td><td>{$nama}</td><td>{$kat}</td><td>{$row->stok_sebelum}</td><td>{$row->stok_sesudah}</td><td>{$row->selisih}</td><td>{$row->catatan}</td></tr>";
        }
        echo '</table>';
        exit;
    }

    // ─────────────────────────────────────────────────────────────
    // LAPORAN TRANSFER (Grouped by Date as 1 Invoice)
    // ─────────────────────────────────────────────────────────────
    public function laporanTransfer(Request $request)
    {
        $this->authorizeOnlyPic();

        $raw = TransferStock::with('barang')
            ->where('user_id', auth()->id())
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $grouped = $raw->groupBy(fn($item) => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d'))
            ->map(function ($items, $dateKey) {
                $hasPending = $items->contains('status', 'Pending');
                $allBatal = $items->every(fn($i) => strtolower($i->status) === 'dibatalkan');
                $status = $allBatal ? 'Dibatalkan' : ($hasPending ? 'Pending' : 'Selesai');

                return [
                    'invoice_no'    => 'INV-TRF-' . \Carbon\Carbon::parse($dateKey)->format('Ymd'),
                    'tanggal'       => $dateKey,
                    'items'         => $items,
                    'item_count'    => $items->count(),
                    'produk_names'  => $items->pluck('barang.nama_barang')->filter()->unique()->implode(', '),
                    'gudang_tujuan' => $items->pluck('ke_gudang')->filter()->unique()->implode(', '),
                    'total_jumlah'  => $items->sum('jumlah'),
                    'status'        => $status,
                    'catatan'       => $items->pluck('catatan')->filter()->unique()->implode('; '),
                ];
            })->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $laporan = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentPageItems,
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('inventories.laporan.laporan-transfer', compact('laporan'));
    }

    public function batalkanTransfer(Request $request)
    {
        $this->authorizeOnlyPic();
        $request->validate(['tanggal' => 'required|date']);

        $items = TransferStock::with('barang')
            ->where('user_id', auth()->id())
            ->whereDate('tanggal', $request->tanggal)
            ->where('status', '!=', 'Dibatalkan')
            ->get();

        foreach ($items as $item) {
            if ($item->barang) {
                $item->barang->increment('stok_fisik', $item->jumlah);
            }
            $item->update(['status' => 'Dibatalkan']);
        }

        return redirect()->route('inventory.laporan-transfer')
            ->with('success', 'Transaksi transfer tanggal ' . \Carbon\Carbon::parse($request->tanggal)->format('d M Y') . ' berhasil dibatalkan dan stok dikembalikan.');
    }

    public function exportTransferExcel(Request $request)
    {
        $this->authorizeOnlyPic();

        $items = TransferStock::with('barang')
            ->where('user_id', auth()->id())
            ->when($request->filled('start_date'), fn ($q) => $q->whereDate('tanggal', '>=', $request->start_date))
            ->when($request->filled('end_date'), fn ($q) => $q->whereDate('tanggal', '<=', $request->end_date))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('tanggal', 'desc')
            ->get();

        $filename = "Laporan_Transfer_Stock_" . date('Y-m-d') . ".xls";
        headers_sent() || header("Content-Type: application/vnd.ms-excel");
        headers_sent() || header("Content-Disposition: attachment; filename=\"$filename\"");

        echo '<table border="1">';
        echo '<tr style="background:#1e293b;color:#ffffff;"><th>No</th><th>Tanggal</th><th>Barang</th><th>Gudang Asal</th><th>Ke Gudang</th><th>Jumlah</th><th>Satuan</th><th>Status</th><th>Catatan</th></tr>';
        foreach ($items as $index => $row) {
            $tgl = \Carbon\Carbon::parse($row->tanggal)->format('d-m-Y');
            $nama = $row->barang->nama_barang ?? '-';
            $st = ucfirst($row->status ?? 'Selesai');
            echo "<tr><td>".($index+1)."</td><td>{$tgl}</td><td>{$nama}</td><td>Gudang Utama</td><td>{$row->ke_gudang}</td><td>{$row->jumlah}</td><td>{$row->satuan}</td><td>{$st}</td><td>{$row->catatan}</td></tr>";
        }
        echo '</table>';
        exit;
    }
}