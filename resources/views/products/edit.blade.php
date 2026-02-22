@extends('layouts.app')

@section('content')
<div class="p-6" x-data="{ 
    purchase_price: {{ $product->purchase_price }}, 
    selling_price: {{ $product->selling_price }},
    get margin() {
        if (this.selling_price <= 0) return 0;
        return Math.round(((this.selling_price - this.purchase_price) / this.selling_price) * 100);
    }
}">
    <div class="mb-8">
        <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 font-bold text-xs uppercase tracking-widest flex items-center gap-2 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-black text-gray-900 uppercase">Edit Produk: {{ $product->product_name }}</h1>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-10">
        <form action="{{ route('products.update', $product->id) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Produk</label>
                        <input type="text" name="product_name" value="{{ $product->product_name }}" required 
                            class="w-full px-5 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-gray-700">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Merk (Brand)</label>
                            <select id="select-brand" name="brand_id" required>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kategori</label>
                            <select id="select-category" name="category_id" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-700">Status Aktif</h4>
                            <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Munculkan produk di kasir</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                </div>

                <div class="bg-blue-50/50 rounded-[2.5rem] p-8 border border-blue-100 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Harga Beli (Modal)</label>
                            <input type="number" name="purchase_price" x-model.number="purchase_price" required 
                                class="w-full px-5 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-black text-blue-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Harga Jual</label>
                            <input type="number" name="selling_price" x-model.number="selling_price" required 
                                class="w-full px-5 py-3 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-black text-blue-700">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-blue-100 flex justify-between items-center">
                        <div>
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Estimasi Profit</span>
                            <h2 class="text-2xl font-black text-blue-700" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selling_price - purchase_price)"></h2>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Margin Laba</span>
                            <div class="flex items-center justify-end gap-1">
                                <span class="text-3xl font-black text-blue-700" x-text="margin + '%'"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const settings = { create: true, persist: false };
        new TomSelect("#select-brand", settings);
        new TomSelect("#select-category", settings);
    });
</script>
@endsection