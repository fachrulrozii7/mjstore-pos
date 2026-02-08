<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory; 
use App\Models\MasterBranch; 

class InventoryController extends Controller
{
    public function index(Request $request) { 
        $branches = MasterBranch::all();
        $selectedBranch = $request->get('branch_id', $branches->first()->id ?? null);

        $inventory = Inventory::withProductData() // Ambil data join dari Model
            ->forBranch($selectedBranch)          // Filter cabang dari Model
            ->searchProduct($request->search)      // Search dari Model
            ->sortInventory($request->sort, $request->order) // Sort dari Model
            ->paginate(15)
            ->withQueryString();

        return view('inventory.index', compact('inventory', 'branches', 'selectedBranch'));
    }
}
