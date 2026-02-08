@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto font-[Instrument Sans]" x-data="scannerSystem()">
    <div class="grid grid-cols-12 gap-6">
        
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                    <div class="relative">
                        <input type="text" 
                            x-ref="barcodeInput"
                            @keydown.enter.prevent="findProduct($el.value)"
                            placeholder="Scan Barcode atau Ketik Product ID di sini..." 
                            class="w-full pl-12 pr-4 py-4 bg-white border-2 border-blue-100 rounded-2xl focus:ring-4 focus:ring-blue-50 focus:border-blue-500 text-lg font-bold tracking-tight transition-all">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="overflow-y-auto h-[500px]">
                    <table class="w-full text-left">
                        <thead class="bg-white text-gray-400 text-[10px] uppercase font-black tracking-widest sticky top-0 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Nama Produk</th>
                                <th class="px-6 py-4 text-center">Harga</th>
                                <th class="px-6 py-4 text-center">Qty</th>
                                <th class="px-6 py-4 text-right">Subtotal</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <template x-for="(item, index) in cart" :key="index">
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-900 block" x-text="item.name"></span>
                                        <span class="text-[10px] text-gray-400 font-mono" x-text="item.product_id"></span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-gray-600" x-text="formatCurrency(item.price)"></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <input type="number" x-model.number="item.qty" 
                                                class="w-16 text-center border-none bg-gray-100 rounded-lg py-1 font-bold focus:ring-2 focus:ring-blue-500">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-blue-600" x-text="formatCurrency(item.price * item.qty)"></td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="removeItem(index)" class="text-red-300 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-gray-300 py-20">
                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="font-bold italic">Belum ada barang di-scan</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-200 sticky top-24">
                <span class="text-blue-200 text-xs font-black uppercase tracking-[0.2em]">Total Transaksi</span>
                <h2 class="text-5xl font-black mt-2 mb-8" x-text="formatCurrency(totalPrice)"></h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Uang Bayar (F8)</label>
                        <input type="number" x-model.number="payment" 
                            class="w-full mt-2 bg-blue-500 border-none rounded-2xl py-4 px-6 text-2xl font-black text-white placeholder-blue-300 focus:ring-4 focus:ring-blue-400 transition-all">
                    </div>

                    <div class="bg-blue-700/50 rounded-2xl p-6">
                        <div class="flex justify-between items-center text-blue-200 mb-1">
                            <span class="text-xs font-bold uppercase tracking-widest">Kembalian</span>
                        </div>
                        <p class="text-3xl font-black" x-text="formatCurrency(payment - totalPrice > 0 ? payment - totalPrice : 0)"></p>
                    </div>

                    <button @click="processPayment" :disabled="cart.length === 0 || payment < totalPrice"
                        class="w-full bg-white text-blue-600 font-black py-5 rounded-2xl shadow-lg hover:bg-blue-50 transition-all transform active:scale-95 disabled:opacity-50 disabled:active:scale-100">
                        SIMPAN & CETAK (F10)
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function scannerSystem() {
    return {
        cart: [],
        payment: 0,
        products: @json($products), // Data produk dikirim dari controller
        
        init() {
            this.$refs.barcodeInput.focus();
            // Shortcut Keyboard
            window.addEventListener('keydown', (e) => {
                if (e.key === 'F8') this.$refs.paymentInput.focus();
                if (e.key === 'F10') this.processPayment();
            });
        },

        findProduct(code) {
            if (!code) return;
            // Cari berdasarkan product_id/Barcode
            const product = this.products.find(p => p.product_id === code);
            if (product) {
                let existing = this.cart.find(i => i.product_id === code);
                if (existing) {
                    existing.qty++;
                } else {
                    this.cart.unshift({ // Tambah ke baris paling atas
                        id: product.id,
                        product_id: product.product_id,
                        name: product.product_name,
                        price: product.selling_price,
                        qty: 1
                    });
                }
                this.$refs.barcodeInput.value = ''; // Reset input
            } else {
                alert('Produk tidak ditemukan!');
                this.$refs.barcodeInput.value = '';
            }
        },

        removeItem(index) {
            this.cart.splice(index, 1);
        },

        get totalPrice() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },

        formatCurrency(val) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);
        },

        // Di dalam script scannerSystem()
        processPayment() {
            if (this.payment < this.totalPrice) {
                alert("Uang bayar kurang!");
                return;
            }

            if (!confirm("Konfirmasi Pembayaran?")) return;

            fetch("{{ route('pos.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    cart: this.cart,
                    payment: this.payment
                })
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok) {
                    // 1. Buka halaman print di tab baru
                    window.open("/pos/print/" + data.transaction_id, "_blank", "width=400,height=600");
                    
                    // 2. Notifikasi sukses
                    alert("Transaksi Berhasil!");

                    // 3. Reset halaman kasir
                    this.cart = [];
                    this.payment = 0;
                    this.$refs.barcodeInput.focus();
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan sistem.");
            });
        }
    }
}
</script>
@endsection