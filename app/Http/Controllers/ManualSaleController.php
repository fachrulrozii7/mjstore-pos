<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockMovement;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManualSaleController extends Controller
{
    public function index()
    {
        // Ambil produk untuk lookup
        // $products = Product::all();
        
        $branchId = auth()->user()->branch_id ?? 1;
        $role = auth()->user()->role;
        if($role == 'root'){
            $branchId = 1;
        }
        $products = Product::with(['inventories' =>function($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        }])->get();
        return view('manual_sales.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date|before_or_equal:today',
            'items' => 'required|array|min:1',
        ]);
        
        try {
            return DB::transaction(function () use ($request) {
                // 1. Simpan Header Transaksi dengan tanggal pilihan
                $branchId = auth()->user()->branch_id ?? 1;
                $role = auth()->user()->role;
                if($role == 'root'){
                    $branchId = 1;
                }
                
                $transaction = Transaction::create([
                    'transaction_id' => 'TRX-' . now()->format('YmdHis'),
                    'transaction_date' => $request->transaction_date . ' ' . now()->format('H:i:s'),
                    'branch_id' => $branchId,
                    'user_id' => auth()->id(),
                    'total_amount' => $request->total_amount,
                    'paid_amount' => $request->total_amount,
                    'change_amount' => 0,
                    'payment_method' => 'cash',
                    'status' => 'PAID',
                    'created_at' => now()
                    // 'notes' => 'Input Manual Susulan'
                ]);
                foreach ($request->items as $item) {
                    // 2. Simpan Detail
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['qty'] * $item['price']
                    ]);

                    // 3. Potong Stok
                    $inventory = Inventory::where('product_id', $item['id'])
                        ->where('branch_id', $branchId)
                        ->first();

                    if ($inventory) {
                        $inventory->decrement('stock', $item['qty']);
                        
                        // 4. Catat History Stok
                        StockMovement::create([
                            'branch_id' => $branchId,
                            'product_id' => $item['id'],
                            'type' => 'OUT',
                            'qty' => $item['qty'],
                            'note' => 'Penjualan Manual Susulan #' . $transaction->id,
                            'created_at' => $transaction->transaction_date
                        ]);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Nota susulan berhasil disimpan!']);
            });
        }catch(\Exception $e) {
            // Ini akan mengirimkan pesan error asli ke console log browser Anda
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}