<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Inventory; // Import model Inventory
use App\Models\MasterBranch; // Import model Branch
use Illuminate\Support\Facades\DB;
use \App\Models\Category;
use \App\Models\Brand;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // $products = Product::search($request->search)
        //     ->sortBy($request->get('sort'), $request->get('order'))
        //     ->paginate(10)
        //     ->withQueryString();

        $products = Product::with(['category', 'brand']) // TAMBAHKAN INI: Agar ambil nama kategori & brand sekaligus
                ->search($request->search)           // Tetap gunakan logika pencarian Anda
                ->sortBy($request->get('sort'), $request->get('order')) // Tetap gunakan sorting Anda
                ->paginate(10)
                ->withQueryString();

        // 2. Ambil data Master untuk Modal Tambah Produk
        // Kita hanya ambil yang is_active saja agar dropdown-nya rapi
        $categories = Category::where('is_active', true)->orderBy('name', 'asc')->get();
        $brands = Brand::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('products.index', compact('products','categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            // 'product_id' => 'required|string|unique:product,product_id',
            // 'unit'     => 'required',
            // 'category' => 'required',
            'category_id' => 'required|exists:mj_master_categories,id',
            'brand_id' => 'nullable|numeric',       // Menyimpan ID, bukan teks
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            // 'brand' => 'nullable|string',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            return DB::transaction(function () use ($request) {
                // 2. Generate Product ID Robust
                // Format: MJ-YYMMDD-XXXX (MJ-260212-0001)
                $prefix = 'MJ';
                $date = now()->format('ymd'); 
                $searchPattern = $prefix . '-' . $date . '-%';

                // Ambil ID terakhir hari ini dengan Locking (lockForUpdate) untuk mencegah race condition
                $lastProduct = Product::where('product_id', 'LIKE', $searchPattern)
                    ->lockForUpdate() 
                    ->orderBy('product_id', 'desc')
                    ->first();

                if ($lastProduct) {
                    // Ambil 4 angka terakhir, tambah 1
                    $lastNumber = (int) substr($lastProduct->product_id, -4);
                    $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
                } else {
                    $nextNumber = '0001';
                }

                $generatedId = $prefix . '-' . $date . '-' . $nextNumber;

                // 3. Simpan ke Database
                $product = Product::create([
                    'product_id' => $generatedId,
                    'product_name' => $request->product_name,
                    'brand_id' => $request->brand_id,
                    'category_id' => $request->category_id,
                    'color' => $request->color,
                    'size' => $request->size,
                    // 'unit' => $request->unit ?? 1,
                    'purchase_price' => $request->purchase_price,
                    'selling_price' => $request->selling_price,
                    'is_active' => $request->has('is_active') ? 1 : 0,
                    // 'min_stock' => 5, // Default
                ]);

                
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
                // return redirect()->route('products.index')->with('success', "Produk $generatedId berhasil dibuat!");
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal generate ID: ' . $e->getMessage());
        }

        // $product = Product::create($validated);
        #Product::create($validated);

    }

    public function printBarcode(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        // Ambil jumlah cetak dari input, default-nya 1 jika tidak diisi
        $quantity = $request->query('qty', 1);
        
        // Validasi agar tidak cetak terlalu banyak sekaligus (misal max 100)
        $quantity = min(max($quantity, 1), 100);

        return view('products.barcode', compact('product', 'quantity'));
    }
}
