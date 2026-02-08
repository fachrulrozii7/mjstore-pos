<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MasterBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class PosController extends Controller
{
    public function index()
    {
        // Kita ambil cabang user yang login (atau default ke pusat)
        $branchId = auth()->user()->branch_id ?? 1; 
        $role = auth()->user()->role; 
        if($role == 'root'){
            $branchId = 1;
        }
        // Ambil produk yang stoknya > 0 di cabang tersebut
        $products = Product::whereHas('inventories', function($q) use ($branchId) {
            $q->where('branch_id', $branchId)->where('stock', '>', 0);
        })->get();

        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'payment' => 'required|numeric',
        ]);

        try {
            $branchId = auth()->user()->branch_id ?? 1;
            
            // Panggil perintah tunggal di Model
            $transaction = Transaction::createTransaction(
                $request->cart, 
                $request->payment, 
                $branchId, 
                Auth::id()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi Berhasil!',
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function print($id)
    {
        $transaction = Transaction::with(['details.product', 'user', 'branch'])->findOrFail($id);
        
        return view('pos.print', compact('transaction'));
    }
}