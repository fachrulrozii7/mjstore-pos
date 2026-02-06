@extends('layouts.app')
<!-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring - MJ Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
</head>
<body class="bg-gray-50 font-[Instrument Sans] antialiased"> -->
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">MJ Department Store</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau performa keuangan dan stok seluruh cabang.</p>
        </div>
        
        <form action="{{ url('/dashboard') }}" method="GET" class="mt-6 md:mt-0 flex flex-wrap gap-3 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <div class="flex flex-col">
                <label class="text-[10px] font-bold uppercase text-gray-400 mb-1 ml-1">Cabang</label>
                <select name="branch_id" class="rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 py-2">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branch_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-[10px] font-bold uppercase text-gray-400 mb-1 ml-1">Dari</label>
                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 py-2">
            </div>
            <div class="flex flex-col">
                <label class="text-[10px] font-bold uppercase text-gray-400 mb-1 ml-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500 py-2">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors">Terapkan</button>
                <a href="{{ url('/dashboard') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Omzet</p>
            <h2 class="text-3xl font-extrabold text-gray-800 mt-2">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden border-l-4 border-l-emerald-500">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Keuntungan Bersih</p>
            <h2 class="text-3xl font-extrabold text-emerald-600 mt-2">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</h2>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Jumlah Transaksi</p>
            <h2 class="text-3xl font-extrabold text-gray-800 mt-2">{{ number_format($revenuePerBranch->sum('total_transactions'), 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Performa Keuangan per Cabang</h3>
            <div class="h-[350px]">
                <canvas id="branchComparisonChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-6">Produk Terlaris (Visual)</h3>
            <div class="h-[350px]">
                <canvas id="bestProductChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-orange-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">⚠️ Top 10 Produk Kurang Laku (Slow Moving)</h3>
                    <p class="text-xs text-gray-500 mt-1">Produk dengan perputaran terendah pada periode ini.</p>
                </div>
                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Perlu Evaluasi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4 text-center">Qty Terjual</th>
                            <th class="px-6 py-4 text-right">Total Omzet</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($slowMoving as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->product_name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold {{ $item->total_qty == 0 ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ number_format($item->total_qty, 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-600">
                                Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->total_qty == 0)
                                    <span class="text-[10px] bg-red-100 text-red-600 px-2 py-1 rounded font-bold uppercase text-nowrap">Tidak Ada Penjualan</span>
                                @else
                                    <span class="text-[10px] bg-orange-100 text-orange-600 px-2 py-1 rounded font-bold uppercase text-nowrap">Sangat Lambat</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-green-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">🔥 Top 10 Produk Terlaris</h3>
                    <p class="text-xs text-gray-500 mt-1">Produk dengan perputaran tertinggi pada periode ini.</p>
                </div>
                <!-- <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">Perlu Evaluasi</span> -->
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4 text-center">Qty Terjual</th>
                            <th class="px-6 py-4 text-right">Total Omzet</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($bestSelling as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->product->product_name }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold {{ $item->total_qty == 0 ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ number_format($item->total_qty, 0) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-600">
                                Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->total_qty == 0)
                                    <span class="text-[10px] bg-red-100 text-red-600 px-2 py-1 rounded font-bold uppercase text-nowrap">Tidak Ada Penjualan</span>
                                @else
                                    <span class="text-[10px] bg-green-100 text-black-600 px-2 py-1 rounded font-bold uppercase text-nowrap">Penjualan Bagus</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-lg font-bold text-gray-800">📊 Detail Produk Terlaris</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Produk</th>
                            <th class="px-6 py-4 text-center">Qty</th>
                            <th class="px-6 py-4 text-right">Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($bestSelling as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $item->product->product_name }}</td>
                            <td class="px-6 py-4 text-center"><span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full font-bold text-xs">{{ $item->total_qty }}</span></td>
                            <td class="px-6 py-4 text-right font-semibold">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div> -->

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">📈 Tren Penjualan Harian</h3>
                <span id="page-info" class="text-xs text-gray-500 font-medium"></span>
            </div>
            
            <div class="overflow-x-auto flex-grow">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Omzet</th>
                        </tr>
                    </thead>
                    <tbody id="daily-sales-body" class="divide-y divide-gray-100 text-sm text-gray-600 font-medium">
                        </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <button id="prev-btn" class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-xs font-bold hover:bg-gray-50 disabled:opacity-50 invisible">PREV</button> <!-- di hide dulu-->
                <div id="page-numbers" class="flex gap-1"></div>
                <button id="next-btn" class="px-3 py-1 bg-white border border-gray-200 rounded-lg text-xs font-bold hover:bg-gray-50 disabled:opacity-50 invisible">NEXT</button> <!-- di hide dulu-->
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-lg font-bold text-gray-800">🏢 Performa per Cabang</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Nama Cabang</th>
                            <th class="px-6 py-4 text-center">Jumlah Transaksi</th>
                            <th class="px-6 py-4 text-right">Total Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($revenuePerBranch as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $row->branch->branch_name }}</td>
                            <td class="px-6 py-4 text-center">{{ number_format($row->total_transactions, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right text-blue-600 font-bold">Rp {{ number_format($row->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Chart Keuangan per Cabang
        const ctxFinance = document.getElementById('branchComparisonChart').getContext('2d');
        const rawChartData = @json($chartData);
        
        new Chart(ctxFinance, {
            type: 'bar',
            data: {
                labels: rawChartData.map(i => i.label),
                datasets: [
                    { label: 'Omzet', data: rawChartData.map(i => i.revenue), backgroundColor: '#3b82f6', borderRadius: 8 },
                    { label: 'Profit', data: rawChartData.map(i => i.profit), backgroundColor: '#10b981', borderRadius: 8 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });

        // 2. Chart Produk Terlaris
        const ctxProduct = document.getElementById('bestProductChart').getContext('2d');
        const bestSellingData = @json($bestSelling);
        
        new Chart(ctxProduct, {
            type: 'doughnut',
            data: {
                labels: bestSellingData.map(i => i.product.product_name),
                datasets: [{
                    data: bestSellingData.map(i => i.total_qty),
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari Laravel dikirim ke JS sebagai array objek
        const allDailyData = @json($dailySales);
        const rowsPerPage = 5;
        let currentPage = 1;

        function renderTable(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            const paginatedData = allDailyData.slice(start, end);
            
            const tbody = document.getElementById('daily-sales-body');
            tbody.innerHTML = '';

            if (paginatedData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="2" class="px-6 py-10 text-center text-gray-400">Tidak ada data.</td></tr>';
                return;
            }

            paginatedData.forEach(item => {
                const formattedSales = new Intl.NumberFormat('id-ID').format(item.total_sales);
                tbody.innerHTML += `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">${item.period}</td>
                        <td class="px-6 py-4 text-right text-gray-900 font-bold">Rp ${formattedSales}</td>
                    </tr>
                `;
            });

            updatePaginationControls();
        }

        function updatePaginationControls() {
            const totalPages = Math.ceil(allDailyData.length / rowsPerPage);
            document.getElementById('page-info').innerText = `Halaman ${currentPage} dari ${totalPages || 1}`;
            
            document.getElementById('prev-btn').disabled = currentPage === 1;
            document.getElementById('next-btn').disabled = currentPage === totalPages || totalPages === 0;

            // Render nomor halaman sederhana
            const pageNumbers = document.getElementById('page-numbers');
            pageNumbers.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                if (i <= 3 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    const btn = document.createElement('button');
                    btn.innerText = i;
                    btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-xs font-bold transition-colors ${i === currentPage ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'}`;
                    btn.onclick = () => { currentPage = i; renderTable(currentPage); };
                    pageNumbers.appendChild(btn);
                }
            }
        }

        document.getElementById('prev-btn').onclick = () => { if (currentPage > 1) { currentPage--; renderTable(currentPage); } };
        document.getElementById('next-btn').onclick = () => { if (currentPage < Math.ceil(allDailyData.length / rowsPerPage)) { currentPage++; renderTable(currentPage); } };

        // Jalankan render pertama kali
        renderTable(currentPage);
    
    });
</script>
@endpush
<!-- </body>
</html> -->