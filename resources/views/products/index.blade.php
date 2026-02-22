@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto font-[Instrument Sans]" x-data="{ 
    showModal: false, 
    purchase_price: 0, 
    selling_price: 0,
    get margin() {
        if (this.selling_price <= 0) return 0;
        let profit = this.selling_price - this.purchase_price;
        return Math.round((profit / this.selling_price) * 100);
    }
}">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">📦 Data Produk</h1>
            <p class="text-sm text-gray-400 mt-1">Kelola stok, harga, dan informasi barang di seluruh cabang.</p>
        </div>
        <div class="flex items-center gap-3">
    <a href="{{ route('products.trashed') }}" class="p-2.5 text-gray-400 hover:text-red-500 bg-gray-50 rounded-xl transition-all" title="Lihat Tempat Sampah">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
    </a>
    
    <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2 shadow-sm">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Produk
    </button>
</div>
    </div>

    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('products.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari Nama Produk atau SKU..." 
                    @input.debounce.750ms="$el.form.submit()"
                    class="bg-gray-50 border-none text-sm rounded-xl px-12 py-3 w-full focus:ring-2 focus:ring-blue-500 font-semibold text-gray-700">
            </div>
        </form>
    </div>

    <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                    <tr>
                        <th class="px-8 py-5">Informasi Produk</th>
                        <th class="px-8 py-5">Kategori & Brand</th>
                        <th class="px-8 py-5 text-right">Harga Jual</th>
                        <th class="px-8 py-5 text-center">Stock</th>
                        <th class="px-8 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900">{{ $product->product_name }}</span>
                                <span class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-0.5">{{ $product->product_id }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md text-[9px] font-black uppercase w-fit tracking-tighter">
                                    {{ $product->brand->name ?? 'NO BRAND' }}
                                </span>
                                <span class="text-gray-400 text-[11px] font-bold italic">
                                    {{ $product->category->name ?? 'Umum' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right font-black text-gray-700">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="font-black text-gray-800">{{ number_format($product->stock, 0) }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($product->is_active)
                                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Active</span>
                            @else
                                <span class="bg-gray-100 text-gray-400 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Inactive</span>
                            @endif
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center justify-center gap-1">
                                <!-- <button class="p-2 text-gray-300 hover:text-blue-600 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button> -->
                                <button 
                                    onclick="window.location.href='{{ route('products.edit', $product->id) }}'"
                                    class="p-2 text-gray-300 hover:text-blue-600 transition-colors " 
                                    title="Edit" style="cursor: pointer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </button>
                                <button @click="let qty = prompt('Jumlah label:', '1'); if(qty) window.open('{{ route('products.barcode', $product->id) }}?qty=' + qty, '_blank')" class="p-2 text-gray-300 hover:text-gray-900 transition-colors" style="cursor: pointer" title="Cetak Barcode">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-300 hover:text-red-600 transition-colors" style="cursor: pointer">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-4">🔍</span>
                                <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Produk tidak ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm" x-cloak>
        <div class="bg-white w-full max-w-2xl rounded-[3rem] p-10 shadow-2xl overflow-y-auto max-h-[85] scrollbar-hide">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Tambah Produk Baru</h3>
                <button @click="showModal = false" class="text-gray-300 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap Produk</label>
                    <input type="text" name="product_name" required placeholder="Contoh: Jeans Slim Fit Blue" class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-gray-700">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Merk (Brand)</label>
                        <select name="brand_id" required class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-sm">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Kategori</label>
                        <select name="category_id" required class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div> -->

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kategori</label>
                        <select id="select-category" name="category_id" class="tom-tailwind w-full" placeholder="Cari kategori..." autocomplete="off">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Brand / Merk</label>
                        <select id="select-brand" name="brand_id" class="tom-tailwind w-full" placeholder="Cari merk..." autocomplete="off">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Warna</label>
                        <input type="text" name="color" class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ukuran (Size)</label>
                        <input type="text" name="size" class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-bold text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                    <div>
                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Harga Modal</label>
                        <input type="number" name="purchase_price" x-model.number="purchase_price" required class="w-full px-5 py-3.5 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-black text-blue-700">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-blue-400 uppercase tracking-widest mb-2">Harga Jual</label>
                        <input type="number" name="selling_price" x-model.number="selling_price" required class="w-full px-5 py-3.5 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-black text-blue-700">
                    </div>
                </div>

                <div class="flex justify-between items-center px-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Estimasi Profit</span>
                        <span class="text-gray-900 font-black text-lg" x-text="new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selling_price - purchase_price)"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Margin Laba</span>
                        <div class="flex items-center justify-end gap-1">
                            <span class="text-blue-600 font-black text-2xl" x-text="margin + '%'"></span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 border border-gray-100 rounded-2xl font-black text-xs uppercase tracking-widest text-gray-400 hover:bg-gray-50 transition-all">Batal</button>
                    <button type="submit" class="flex-1 px-6 py-4 bg-blue-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style> [x-cloak] { display: none !important; } </style>
<style>
    .tom-clean + .ts-wrapper .ts-control {
    border: none !important;
    box-shadow: none !important;
    background-color: #f9fafb;
}
</style>
<style>
/* Wrapper full width */
.tom-tailwind + .ts-wrapper {
    width: 100%;
}

/* Main control */
.tom-tailwind + .ts-wrapper .ts-control {
    background-color: #f9fafb; /* bg-gray-50 */
    border: none !important;
    box-shadow: none !important;
    border-radius: 1rem; /* rounded-2xl */
    padding: 0.875rem 1.25rem; /* py-3.5 px-5 */
    font-weight: 700;
    font-size: 0.875rem; /* text-sm */
}

/* Remove default border */
.tom-tailwind + .ts-wrapper .ts-control input {
    font-weight: 700;
}

/* Focus ring like Tailwind */
.tom-tailwind + .ts-wrapper.focus .ts-control {
    box-shadow: 0 0 0 2px #040404 !important; /* focus:ring-blue-500 */
    background-color: #f9fafb;
}

/* Dropdown style */
.tom-tailwind + .ts-wrapper .ts-dropdown {
    border-radius: 1rem;
    border: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    font-weight: 600;
}
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Konfigurasi untuk Brand
        new TomSelect("#select-brand", {
            create: true, // Mengizinkan input teks baru yang tidak ada di list
            persist: false,
            createFilter: function(input) {
                return input.length >= 2; // Minimal 2 karakter untuk buat merk baru
            },
            render: {
                option_create: function(data, escape) {
                    return '<div class="create">Tambah merk baru: <strong>' + escape(data.input) + '</strong>...</div>';
                }
            }
        });

        // Konfigurasi untuk Kategori
        new TomSelect("#select-category", {
            create: true,
            persist: false,
            render: {
                option_create: function(data, escape) {
                    return '<div class="create">Tambah kategori baru: <strong>' + escape(data.input) + '</strong>...</div>';
                }
            }
        });
    });
</script>
@endsection