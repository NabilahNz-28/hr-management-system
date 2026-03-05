<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\WarehouseTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{
    // Warehouse dashboard
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $user = auth()->user();
        
        // Get statistics
        $totalItems = Inventory::count();
        $lowStockItems = Inventory::whereColumn('quantity', '<=', 'min_stock')->count();
        $todayTransactions = WarehouseTransaction::whereDate('created_at', today())->count();
        
        // Get recent transactions
        $recentTransactions = WarehouseTransaction::with(['inventory', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('warehouse.index', compact(
            'totalItems', 
            'lowStockItems', 
            'todayTransactions',
            'recentTransactions'
        ));
    }

    // Display all transactions
    public function transactions()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $user = auth()->user();
        
        if ($user->hasRole('PIC')) {
            // PIC only sees transactions for their items
            $transactions = WarehouseTransaction::whereHas('inventory', function($q) use ($user) {
                $q->where('pic_id', $user->id);
            })
            ->with(['inventory', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        } else {
            // Admin & Admin Gudang see all transactions
            $transactions = WarehouseTransaction::with(['inventory', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }
        
        return view('warehouse.transactions', compact('transactions'));
    }

    // Show form to create transaction
    public function createTransaction()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $inventories = Inventory::orderBy('name')->get();
        
        return view('warehouse.create-transaction', compact('inventories'));
    }

    // Store new transaction
    public function storeTransaction(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            $inventory = Inventory::findOrFail($request->inventory_id);
            
            // PIC can only transact their own items
            $user = auth()->user();
            if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
                abort(403, 'Unauthorized to transact this item.');
            }
            
            // Check stock for outgoing transactions
            if ($request->type == 'out' && $inventory->quantity < $request->quantity) {
                return back()->with('error', 'Insufficient stock. Available: ' . $inventory->quantity);
            }
            
            // Update inventory quantity
            if ($request->type == 'in') {
                $inventory->quantity += $request->quantity;
            } elseif ($request->type == 'out') {
                $inventory->quantity -= $request->quantity;
            } else {
                // adjustment - quantity is set directly
                $inventory->quantity = $request->quantity;
            }
            
            // Update total value
            $inventory->total_value = $inventory->quantity * $inventory->price;
            $inventory->save();
            
            // Create transaction record
            $transaction = WarehouseTransaction::create([
                'inventory_id' => $request->inventory_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'user_id' => $user->id,
                'reference_number' => 'TRX-' . strtoupper(Str::random(8)),
            ]);
            
            DB::commit();
            
            return redirect()->route('warehouse.transactions')
                ->with('success', 'Transaction completed successfully. Ref: ' . $transaction->reference_number);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Transaction failed: ' . $e->getMessage());
        }
    }

    // =============== STOCK OPERATIONS ===============

    // Show stock in form
    public function showStockIn(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only stock in their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to stock in this item.');
        }
        
        return view('warehouse.stock-in', compact('inventory'));
    }

    // Handle stock in
    public function stockIn(Request $request, Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only stock in their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to stock in this item.');
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update inventory quantity
            $inventory->quantity += $request->quantity;
            $inventory->total_value = $inventory->quantity * $inventory->price;
            $inventory->save();
            
            // Create transaction record
            WarehouseTransaction::create([
                'inventory_id' => $inventory->id,
                'type' => 'in',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'user_id' => $user->id,
                'reference_number' => 'IN-' . strtoupper(Str::random(8)),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventories.show', $inventory)
                ->with('success', 'Stock in successful. New quantity: ' . $inventory->quantity);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Stock in failed: ' . $e->getMessage());
        }
    }

    // Show stock out form
    public function showStockOut(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only stock out their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to stock out this item.');
        }
        
        return view('warehouse.stock-out', compact('inventory'));
    }

    // Handle stock out
    public function stockOut(Request $request, Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only stock out their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to stock out this item.');
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $inventory->quantity,
            'notes' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update inventory quantity
            $inventory->quantity -= $request->quantity;
            $inventory->total_value = $inventory->quantity * $inventory->price;
            $inventory->save();
            
            // Create transaction record
            WarehouseTransaction::create([
                'inventory_id' => $inventory->id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'user_id' => $user->id,
                'reference_number' => 'OUT-' . strtoupper(Str::random(8)),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventories.show', $inventory)
                ->with('success', 'Stock out successful. Remaining quantity: ' . $inventory->quantity);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Stock out failed: ' . $e->getMessage());
        }
    }

    // Show adjustment form
    public function showAdjustment(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang'])) {
            abort(403, 'Unauthorized');
        }
        
        // Only admin and admin gudang can adjust
        return view('warehouse.adjustment', compact('inventory'));
    }

    // Handle adjustment
    public function adjustment(Request $request, Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang'])) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:500',
        ]);
        
        DB::beginTransaction();
        
        try {
            $oldQuantity = $inventory->quantity;
            $adjustmentQuantity = $request->new_quantity - $oldQuantity;
            
            // Update inventory quantity
            $inventory->quantity = $request->new_quantity;
            $inventory->total_value = $inventory->quantity * $inventory->price;
            $inventory->save();
            
            // Create transaction record
            WarehouseTransaction::create([
                'inventory_id' => $inventory->id,
                'type' => 'adjustment',
                'quantity' => $adjustmentQuantity,
                'notes' => 'Adjustment: ' . $request->reason . ' (From: ' . $oldQuantity . ' To: ' . $request->new_quantity . ')',
                'user_id' => auth()->id(),
                'reference_number' => 'ADJ-' . strtoupper(Str::random(8)),
            ]);
            
            DB::commit();
            
            return redirect()->route('inventories.show', $inventory)
                ->with('success', 'Adjustment successful. New quantity: ' . $inventory->quantity);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Adjustment failed: ' . $e->getMessage());
        }
    }
}