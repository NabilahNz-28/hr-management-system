<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // Display all inventory items
    public function index()
    {
        // Check role
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $user = auth()->user();
        
        if ($user->hasRole('PIC')) {
            // PIC only sees their assigned items
            $inventories = Inventory::where('pic_id', $user->id)
                ->orderBy('name')
                ->paginate(20);
        } else {
            // Admin & Admin Gudang see all items
            $inventories = Inventory::with('pic')
                ->orderBy('name')
                ->paginate(20);
        }
        
        return view('inventories.index', compact('inventories'));
    }

    // Show form to create inventory item
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $pics = User::whereHas('roles', function($q) {
            $q->where('name', 'PIC');
        })->get();
        
        $categories = [
            'Electronics',
            'Office Supplies',
            'Raw Materials',
            'Finished Goods',
            'Tools & Equipment',
            'Furniture',
            'Stationery',
            'Others'
        ];
        
        $units = ['Unit', 'Pcs', 'Box', 'Kg', 'Liter', 'Meter', 'Set', 'Pack'];
        
        return view('inventories.create', compact('pics', 'categories', 'units'));
    }

    // Store new inventory item
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        $request->validate([
            'code' => 'required|string|max:50|unique:inventories',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:100',
            'pic_id' => 'nullable|exists:users,id',
        ]);

        // Calculate total value
        $totalValue = $request->quantity * $request->price;

        Inventory::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price' => $request->price,
            'total_value' => $totalValue,
            'min_stock' => $request->min_stock,
            'location' => $request->location,
            'pic_id' => $request->pic_id,
        ]);

        return redirect()->route('inventories.index')
            ->with('success', 'Inventory item created successfully.');
    }

    // Display specific inventory item
    public function show(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only view their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to view this item.');
        }
        
        return view('inventories.show', compact('inventory'));
    }

    // Show edit form
    public function edit(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only edit their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to edit this item.');
        }
        
        $pics = User::whereHas('roles', function($q) {
            $q->where('name', 'PIC');
        })->get();
        
        $categories = [
            'Electronics',
            'Office Supplies',
            'Raw Materials',
            'Finished Goods',
            'Tools & Equipment',
            'Furniture',
            'Stationery',
            'Others'
        ];
        
        $units = ['Unit', 'Pcs', 'Box', 'Kg', 'Liter', 'Meter', 'Set', 'Pack'];
        
        return view('inventories.edit', compact('inventory', 'pics', 'categories', 'units'));
    }

    // Update inventory item
    public function update(Request $request, Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only update their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to update this item.');
        }
        
        $request->validate([
            'code' => 'required|string|max:50|unique:inventories,code,' . $inventory->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'location' => 'nullable|string|max:100',
            'pic_id' => 'nullable|exists:users,id',
        ]);

        // Calculate total value
        $totalValue = $request->quantity * $request->price;

        $inventory->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'price' => $request->price,
            'total_value' => $totalValue,
            'min_stock' => $request->min_stock,
            'location' => $request->location,
            'pic_id' => $request->pic_id,
        ]);

        return redirect()->route('inventories.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    // Delete inventory item
    public function destroy(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC cannot delete items
        if (auth()->user()->hasRole('PIC')) {
            abort(403, 'Unauthorized');
        }
        
        $inventory->delete();

        return redirect()->route('inventories.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    // =============== ADDITIONAL METHODS ===============

    // Show transactions history for specific item
    public function transactions(Inventory $inventory)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'admin gudang', 'PIC'])) {
            abort(403, 'Unauthorized');
        }
        
        // PIC can only view their own items
        $user = auth()->user();
        if ($user->hasRole('PIC') && $inventory->pic_id != $user->id) {
            abort(403, 'Unauthorized to view this item.');
        }
        
        $transactions = $inventory->warehouseTransactions()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('inventories.transactions', compact('inventory', 'transactions'));
    }

    // Show PIC's own items
    public function myItems()
    {
        if (!auth()->user()->hasRole('PIC')) {
            abort(403, 'Unauthorized');
        }
        
        $user = auth()->user();
        $inventories = Inventory::where('pic_id', $user->id)
            ->orderBy('name')
            ->paginate(20);
        
        return view('inventories.my-items', compact('inventories'));
    }
}