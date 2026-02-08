<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MJ Store POS - Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-[Instrument Sans] antialiased text-gray-900">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white border-r border-gray-100 transition-all duration-300 flex flex-col sticky top-0 h-screen">
            <div class="p-6 flex items-center gap-3 border-b border-gray-50">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex-shrink-0 flex items-center justify-center text-white font-bold">MJ</div>
                <span x-show="sidebarOpen" class="font-bold text-lg tracking-tight transition-opacity duration-300">DEPT STORE</span>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                @if(\App\Models\RolePermission::canAccess('dashboard'))
                <p x-show="sidebarOpen" class="text-[10px] font-bold text-gray-400 uppercase px-3 mb-2">Monitoring</p>
                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('dashboard*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Dashboard</span>
                </a>
                @endif
                @if(\App\Models\RolePermission::canAccess('pos'))
                <p x-show="sidebarOpen" class="text-[10px] font-bold text-gray-400 uppercase px-3 mt-6 mb-2">Transaksi</p>
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Menu Kasir</span>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Penjualan Manual</span>
                </a>
                @endif

                @if(\App\Models\RolePermission::canAccess('products'))
                <p x-show="sidebarOpen" class="text-[10px] font-bold text-gray-400 uppercase px-3 mt-6 mb-2">Master Data</p>
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('products*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V5"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Data Produk</span>
                </a>
                <a href="{{ route('inventory.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('inventory*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Stock Produk</span>
                </a>
                @endif
                
                @if(\App\Models\RolePermission::canAccess('reports'))
                <p x-show="sidebarOpen" class="text-[10px] font-bold text-gray-400 uppercase px-3 mt-6 mb-2">Laporan</p>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl text-gray-500 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Reporting</span>
                </a>
                @endif

                @if(auth()->user()->role === 'root')
                <p x-show="sidebarOpen" class="text-[10px] font-bold text-gray-400 uppercase px-3 mt-6 mb-2">System</p>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 p-3 rounded-xl {{ Request::is('users*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span x-show="sidebarOpen" class="text-sm font-semibold">Manajemen User</span>
                </a>
                @endif
            </nav>

            <!-- <div class="p-4 border-t border-gray-50">
                <div class="flex items-center gap-3 p-2 rounded-xl bg-gray-50">
                    <div class="w-8 h-8 rounded-lg bg-gray-300 flex-shrink-0"></div>
                    <div x-show="sidebarOpen" class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-900 truncate">Root User</p>
                        <p class="text-[10px] text-gray-500">Super Admin</p>
                    </div>
                </div>
            </div> -->
            <div class="p-4 border-t border-gray-50">
            <div class="flex items-center gap-3 p-2 rounded-xl bg-gray-50">
                <div class="w-8 h-8 rounded-lg bg-green-300 flex-shrink-0 flex items-center justify-center text-white text-sm">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                    <input type="hidden"> <family class="fa-solid fa-user"></family>
                </div>
                <div x-show="sidebarOpen" class="overflow-hidden">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 uppercase">{{ Auth::user()->role }}</p>
                </div>
            </div>
        </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-10">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                
                <div class="flex items-center gap-4">
                    <span class="text-xs font-medium text-gray-400">{{ date('l, d F Y') }}</span>
                    <div class="h-4 w-px bg-gray-200"></div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="cursor-pointer text-sm font-semibold text-red-500 hover:text-red-600 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>