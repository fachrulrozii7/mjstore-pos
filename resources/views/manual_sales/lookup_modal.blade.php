<div x-show="showLookup" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
        x-cloak
        @keydown.window.escape="showLookup = false"
        @keydown.window.f1.prevent="showLookup = true; $nextTick(() => $refs.searchField.focus())">
        
        <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-100" @click.away="showLookup = false">
            <div class="p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-black text-gray-900">Cari Produk (F1)</h3>
                    <button @click="showLookup = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="relative mb-6">
                    <input type="text" 
                        x-ref="searchField"
                        x-model="searchQuery"
                        placeholder="Ketik nama atau id produk ..." 
                        class="w-full px-8 py-5 bg-gray-50 border-none rounded-[1.5rem] focus:ring-4 focus:ring-blue-100 font-bold text-lg">
                </div>

                <div class="max-h-[400px] overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                    <template x-for="product in products.filter(p => p.product_name.toLowerCase().includes(searchQuery.toLowerCase()) || p.product_id.toLowerCase().includes(searchQuery.toLowerCase()))" :key="product.id">
                        <div @click="addToCartFromLookup(product); showLookup = false; searchQuery = ''" 
                            class="group flex items-center justify-between p-5 rounded-[1.5rem] cursor-pointer border-2 border-transparent hover:border-blue-500 hover:bg-blue-50 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 flex items-center justify-center text-blue-600 font-black group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    <span x-text="product.product_name.substring(0,1)"></span>
                                </div>
                                <div>
                                    <p class="font-black text-gray-900 group-hover:text-blue-700" x-text="product.product_name"></p>
                                    <p class="text-xs font-bold text-gray-400 tracking-widest" x-text="product.product_id"></p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-blue-600 text-lg" x-text="formatCurrency(product.selling_price)"></p>
                                <p class="text-[10px] font-black uppercase tracking-tighter" :class="getStock(product) > 5 ? 'text-gray-400' : 'text-red-500'">
                                    Stok: <span x-text="getStock(product) || 0"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>