<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Inventory; // Import model Inventory
use App\Models\MasterBranch; // Import model Branch

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::search($request->search)
            ->sortBy($request->get('sort'), $request->get('order'))
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'product_id' => 'required|string|unique:product,product_id',
            'unit'     => 'required',
            'category' => 'required',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        Product::create($validated);
        // 2. Ambil semua ID cabang yang ada
        $branches = MasterBranch::all();

        // 3. Daftarkan produk ini ke tabel inventory untuk SETIAP cabang
        foreach ($branches as $branch) {
            Inventory::create([
                'product_id' => $product->id,
                'branch_id' => $branch->id,
                'stock' => 0, // Stok awal di semua cabang adalah 0
                'min_stock' => 10, // Default stok minimum untuk peringatan
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }
}
