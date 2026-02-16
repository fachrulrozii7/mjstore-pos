@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="manualSalesSystem()">
    <div class="bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-100">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Input Nota Susulan</h2>
                <p class="text-gray-500 font-medium mt-1">Gunakan form ini untuk mencatat penjualan manual (saat sistem offline/kendala)</p>
            </div>
            <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>

        <div class="bg-blue-50 p-8 rounded-[2.5rem] border-2 border-blue-100 mb-10">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-1">
                    <label class="block text-xs font-black text-blue-600 uppercase tracking-widest mb-3 px-2">Tanggal Transaksi di Nota Kertas:</label>
                    <input type="date" x-model="transactionDate" 
                           class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-4 focus:ring-blue-200 font-bold text-lg text-blue-900 shadow-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-black text-blue-600 uppercase tracking-widest mb-3 px-2">Cari Produk Untuk Ditambahkan:</label>
                    <button @click="showLookup = true" class="w-full flex items-center justify-between px-6 py-4 bg-white border-2 border-dashed border-blue-200 rounded-2xl text-gray-400 hover:border-blue-400 hover:text-blue-600 transition-all font-bold">
                        <span>Klik untuk mencari barang...</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-hidden border border-gray-100 rounded-[2rem] mb-10">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-xs font-black text-gray-400 uppercase tracking-widest">Produk</th>
                        <th class="py-6 px-8 text-xs font-black text-gray-400 uppercase tracking-widest">Harga</th>
                        <th class="py-6 px-8 text-xs font-black text-gray-400 uppercase tracking-widest w-32">Qty</th>
                        <th class="py-6 px-8 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Subtotal</th>
                        <th class="py-6 px-8"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <template x-for="(item, index) in cart" :key="index">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-5 px-8">
                                <p class="font-black text-gray-900" x-text="item.product_name"></p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter" x-text="item.product_id"></p>
                            </td>
                            <td class="py-5 px-8 font-bold text-gray-600" x-text="formatCurrency(item.price)"></td>
                            <td class="py-5 px-8">
                                <input type="number" x-model.number="item.qty" @input="updateSubtotal(index)"
                                       class="w-20 px-3 py-2 bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500 font-bold text-center">
                            </td>
                            <td class="py-5 px-8 font-black text-gray-900 text-right" x-text="formatCurrency(item.subtotal)"></td>
                            <td class="py-5 px-8 text-right">
                                <button @click="removeItem(index)" class="text-red-400 hover:text-red-600 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            <template x-if="cart.length === 0">
                <div class="py-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <p class="text-gray-400 font-bold">Belum ada barang yang ditambahkan.</p>
                </div>
            </template>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-10 border-t border-gray-100">
            <div>
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Pembayaran Nota</p>
                <h2 class="text-5xl font-black text-blue-600 tracking-tighter" x-text="formatCurrency(totalAmount)"></h2>
            </div>
            <button @click="submitSale" 
                    :disabled="cart.length === 0 || !transactionDate"
                    class="w-full md:w-auto px-12 py-5 bg-gray-900 text-white rounded-[1.5rem] font-black uppercase tracking-widest hover:bg-blue-600 disabled:bg-gray-200 disabled:cursor-not-allowed transition-all shadow-xl shadow-gray-200">
                Simpan Nota Susulan
            </button>
        </div>
    </div>

    @include('manual_sales.lookup_modal')
</div>

@push('scripts')
<script>
    function manualSalesSystem() {
        return {
            transactionDate: '{{ date('Y-m-d') }}',
            cart: [],
            showLookup: false,
            searchQuery: '',
            products: @json($products),

            formatCurrency(val) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
            },

            getStock(product) {
                // Cek apakah product ada, inventory ada, dan merupakan array yang punya isi
                console.log('apya', product)
                if (product && product.inventories && Array.isArray(product.inventories) && product.inventories.length > 0) {
                    return product.inventories[0].stock || 0;
                }
                return 0; // Kembalikan 0 jika tidak ada data stok
            },

            addToCartFromLookup(product) {
                // Cek apakah produk sudah ada di keranjang
                let existingItem = this.cart.find(item => item.id === product.id);

                if (existingItem) {
                    existingItem.qty++;
                    existingItem.subtotal = existingItem.qty * existingItem.price;
                } else {
                    this.cart.push({
                        id: product.id,
                        product_id: product.product_id,
                        name: product.product_name,
                        price: product.selling_price,
                        qty: 1,
                        subtotal: product.selling_price
                    });
                }
                
                // Beri notifikasi kecil atau fokus kembali ke input barcode
                // this.$nextTick(() => this.$refs.barcodeInput.focus());
            },

            addItem(product) {
                let existing = this.cart.find(i => i.id === product.id);
                if (existing) {
                    existing.qty++;
                    existing.subtotal = existing.qty * existing.price;
                } else {
                    this.cart.push({
                        id: product.id,
                        name: product.product_name,
                        product_id: product.product_id,
                        price: product.selling_price,
                        qty: 1,
                        subtotal: product.selling_price
                    });
                }
            },

            removeItem(index) {
                this.cart.splice(index, 1);
            },

            updateSubtotal(index) {
                let item = this.cart[index];
                item.subtotal = item.qty * item.price;
            },

            get totalAmount() {
                return this.cart.reduce((sum, item) => sum + item.subtotal, 0);
            },

            async submitSale() {
                try {
                    const response = await fetch('{{ route('manual_sales.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            transaction_date: this.transactionDate,
                            total_amount: this.totalAmount,
                            items: this.cart
                        })
                    });

                    const res = await response.json();
                    if(res.success) {
                        alert('Nota berhasil disimpan!');
                        window.location.reload();
                    }
                } catch (e) {
                    console.error(e); // Lihat di F12 Console
    alert('Terjadi Kesalahan: ' + e.message);
                }
            }
        }
    }
</script>
@endpush
@endsection