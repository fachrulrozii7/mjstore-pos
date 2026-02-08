@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ showModal: false }">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">📦 Data Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola stok, harga, dan informasi barang di seluruh cabang.</p>
        </div>
        <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Produk
        </button>
    </div>
    <div x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="showModal = false"></div>

            <div x-show="showModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative z-10 inline-block w-full max-w-lg p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-2xl">
                
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Tambah Produk Baru</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Produk</label>
                        <input type="text" name="product_name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Product ID</label>
                            <input type="text" name="product_id" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Unit</label>
                            <input type="number" name="unit" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Harga Beli</label>
                            <input type="number" name="purchase_price" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kategori</label>
                            <select name="category" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                                <option value="Umum">Umum</option>
                                <option value="Makanan">Makanan</option>
                                <option value="Minuman">Minuman</option>
                                <option value="Kebutuhan Rumah">Kebutuhan Rumah</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Harga Jual</label>
                            <input type="number" name="selling_price" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-bold text-blue-600">
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showModal = false" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-sm shadow-blue-200 transition-all">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari Nama / ID Product ..." 
                @input.debounce.750ms="$el.form.submit()"
                class="bg-gray-50 border-none text-sm rounded-xl px-10 py-2.5 w-full focus:ring-2 focus:ring-blue-500 shadow-sm font-semibold text-gray-700">
            </div>
            
            <!-- <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition-all">
                Cari
            </button> -->
        </form>
    </div>



    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">
                            <a 
                                href="{{ request()->fullUrlWithQuery(['sort' => 'product_name', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                                Informasi Produk
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </a>
                        </th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4 text-right">
                            <a 
                                href="{{ request()->fullUrlWithQuery(['sort' => 'selling_price', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-end gap-1 hover:text-blue-600 transition-colors">
                                Harga Jual
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </a>
                        </th>
                        <th class="px-6 py-4 text-center">
                            Stock Global
                            <!-- <a 
                                href="{{ request()->fullUrlWithQuery(['sort' => 'selling_price', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-end gap-1 hover:text-blue-600 transition-colors">
                                Stock Global
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                            </a> -->
                        </th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900">{{ $product->product_name }}</span>
                                <span class="text-xs text-gray-400 uppercase">{{ $product->product_id }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-[11px] font-bold uppercase">
                                {{ $product->category ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-700">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center font-semibold">
                            {{ number_format($product->stock, 0) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->stock <= $product->min_stock)
                                <span class="bg-red-100 text-red-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">Stok Menipis</span>
                            @else
                                <span class="bg-green-100 text-green-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">Tersedia</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button class="p-2 text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <button class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Tidak ada produk ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
<style> [x-cloak] { display: none !important; } </style>
@endsection