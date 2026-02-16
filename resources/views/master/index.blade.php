@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto font-[Instrument Sans]" x-data="{ 
    activeTab: 'categories', 
    showModalCat: false, 
    showModalBrand: false 
}">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">⚙️ Master Data</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola klasifikasi kategori dan merk untuk standarisasi barang.</p>
        </div>
        
        <div class="flex bg-gray-100 p-1 rounded-xl">
            <button @click="activeTab = 'categories'" 
                :class="activeTab === 'categories' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500'"
                class="px-6 py-2 rounded-lg text-xs font-bold uppercase transition-all">
                Kategori
            </button>
            <button @click="activeTab = 'brands'" 
                :class="activeTab === 'brands' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500'"
                class="px-6 py-2 rounded-lg text-xs font-bold uppercase transition-all">
                Merk (Brand)
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div x-show="activeTab === 'categories'">
            <div class="p-6 flex justify-between items-center border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Daftar Kategori</h3>
                <button @click="showModalCat = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold text-xs transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kategori
                </button>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $cat->name }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('master.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div x-show="activeTab === 'brands'">
            <div class="p-6 flex justify-between items-center border-b border-gray-50">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Daftar Merk (Brand)</h3>
                <button @click="showModalBrand = true" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl font-bold text-xs transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Merk
                </button>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Nama Merk</th>
                        <th class="px-6 py-4 text-left">Deskripsi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-700 uppercase">{{ $brand->name }}</td>
                        <td class="px-6 py-4 text-gray-400 italic text-xs">{{ $brand->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('master.brand.destroy', $brand->id) }}" method="POST" onsubmit="return confirm('Hapus merk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-10 text-center text-gray-400">Belum ada merk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="showModalCat" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showModalCat = false"></div>
            <div class="relative bg-white w-full max-w-md p-8 rounded-2xl shadow-2xl">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Tambah Kategori</h3>
                <form action="{{ route('master.category.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Kategori</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showModalCat = false" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-500 rounded-xl font-bold text-sm">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="showModalBrand" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showModalBrand = false"></div>
            <div class="relative bg-white w-full max-w-md p-8 rounded-2xl shadow-2xl">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Tambah Merk Baru</h3>
                <form action="{{ route('master.brand.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Merk</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Deskripsi</label>
                        <textarea name="description" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm"></textarea>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="showModalBrand = false" class="flex-1 px-4 py-2.5 border border-gray-100 text-gray-500 rounded-xl font-bold text-sm">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-gray-900 text-white rounded-xl font-bold text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style> [x-cloak] { display: none !important; } </style>
@endsection