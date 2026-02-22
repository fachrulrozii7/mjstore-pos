@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto font-[Instrument Sans]">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🗑️ Tempat Sampah Produk</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar produk yang dihapus. Anda bisa mengembalikan atau menghapusnya permanen.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700 font-bold text-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Data Produk
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Informasi Produk</th>
                    <th class="px-6 py-4">Kategori / Merk</th>
                    <th class="px-6 py-4 text-center">Tanggal Dihapus</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition-colors italic text-gray-400">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold">{{ $product->product_name }}</span>
                            <span class="text-[10px] uppercase tracking-widest">{{ $product->product_id }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs">
                        {{ $product->category->name ?? '-' }} / {{ $product->brand->name ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        {{ $product->deleted_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-3">
                            <form action="{{ route('products.restore', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg font-bold text-[10px] uppercase tracking-wider hover:bg-blue-100 transition-all">
                                    Restore
                                </button>
                            </form>
                            
                            <form action="{{ route('products.forceDelete', $product->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Data akan hilang selamanya dan tidak bisa dikembalikan. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-300 hover:text-red-600 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                        Tempat sampah kosong.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection