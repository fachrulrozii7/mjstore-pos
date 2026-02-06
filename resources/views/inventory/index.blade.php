@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto font-[Instrument Sans]">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">📦 Manajemen Stok</h1>
        <p class="text-sm text-gray-500 mt-1">Cari dan urutkan stok barang di setiap cabang.</p>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
           <div x-data="{ open: false }" class="relative inline-block text-left">
                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Lokasi Cabang</span>
                    
                    <button @click="open = !open" type="button" 
                        class="group inline-flex items-center justify-between gap-x-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-100 hover:bg-gray-50 transition-all">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.5)]"></div>
                            @foreach($branches as $branch)
                                @if($selectedBranch == $branch->id)
                                    {{ $branch->branch_name }}
                                @endif
                            @endforeach
                        </div>
                        <svg class="-mr-1 h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div x-show="open" 
                    @click.outside="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute left-24 z-50 mt-2 w-56 origin-top-right rounded-2xl bg-white shadow-xl ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden" 
                    x-cloak>
                    <div class="py-1">
                        @foreach($branches as $branch)
                        <form action="{{ route('inventory.index') }}" method="GET">
                            <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                            <input type="hidden" name="search" value="{{ request('search') }}">
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                            
                            <button type="submit" 
                                class="flex items-center w-full px-4 py-3 text-sm {{ $selectedBranch == $branch->id ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }} transition-colors group">
                                <span class="flex-1 text-left font-bold">{{ $branch->branch_name }}</span>
                                @if($selectedBranch == $branch->id)
                                    <svg class="h-4 w-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>

            <form action="{{ route('inventory.index') }}" method="GET" x-data class="relative w-full md:w-64">
                <input type="hidden" name="branch_id" value="{{ $selectedBranch }}">
                
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari Nama / ID Product ..." 
                    @input.debounce.750ms="$el.form.submit()"
                    class="bg-gray-50 border-none text-sm rounded-xl px-10 py-2.5 w-full focus:ring-2 focus:ring-blue-500 shadow-sm font-semibold text-gray-700">
                
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-widest border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'product_name', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center gap-1 hover:text-blue-600 transition-colors">
                            Nama Barang
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        </a>
                    </th>
                    <th class="px-6 py-4">Product ID</th>
                    <th class="px-6 py-4 text-center">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}" class="flex items-center justify-center gap-1 hover:text-blue-600 transition-colors">
                            Stok Fisik
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        </a>
                    </th>
                    <th class="px-6 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($inventory as $item)
                <tr class="hover:bg-blue-50/10 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-bold text-gray-900 block">{{ $item->product_name }}</span>
                        <span class="text-[9px] text-gray-400 font-bold uppercase">{{ $item->category }}</span>
                    </td>
                    <td class="px-6 py-4 text-xs font-mono text-gray-400">{{ $item->product_id }}</td>
                    <td class="px-6 py-4 text-center font-black text-lg {{ $item->stock <= $item->min_stock ? 'text-red-500' : 'text-gray-700' }}">
                        {{ number_format($item->stock, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->stock <= 0)
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-[9px] font-bold uppercase shadow-sm">Habis</span>
                        @elseif($item->stock <= $item->min_stock)
                            <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-[9px] font-bold uppercase font-medium">Kritis</span>
                        @else
                            <span class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-[9px] font-bold uppercase">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-20 text-center text-gray-400 italic">Produk tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($inventory->hasPages())
        <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-100 uppercase text-[10px] font-bold">
            {{ $inventory->links() }}
        </div>
        @endif
    </div>
</div>
@endsection