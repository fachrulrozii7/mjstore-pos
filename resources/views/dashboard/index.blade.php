<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring - MJ Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
</head>
<body class="bg-gray-50 font-[Instrument Sans] antialiased">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard Monitoring</h1>
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
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-lg font-bold text-gray-800">📈 Tren Penjualan Harian</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-right">Omzet</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-600 font-medium">
                        @foreach ($dailySales as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->period }}</td>
                            <td class="px-6 py-4 text-right text-gray-900">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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

<script>
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
</script>
</body>
</html>