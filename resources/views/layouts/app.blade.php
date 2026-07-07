<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MINI-ERP Sukro')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine JS CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex-shrink-0 flex flex-col">
        <div class="h-16 flex items-center justify-center border-b border-gray-100">
            <h1 class="text-xl font-bold text-blue-600">SUKRO ERP</h1>
        </div>
        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="/" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">📊</span> Dashboard
                    </a>
                </li>
                <li class="px-6 py-2 mt-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</li>
                <li>
                    <a href="/bahan-baku" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">📦</span> Bahan Baku
                    </a>
                </li>
                <li>
                    <a href="/produk" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">🛍️</span> Produk & Resep
                    </a>
                </li>
                <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Kasir</li>
                <li>
                    <a href="/pos" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">💻</span> Point of Sales
                    </a>
                </li>
                <li>
                    <a href="/transaksi" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">🧾</span> Riwayat Transaksi
                    </a>
                </li>
                <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">HR & Payroll</li>
                <li>
                    <a href="/absensi" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">⏰</span> Absensi Harian
                    </a>
                </li>
                <li>
                    <a href="/penggajian" class="flex items-center px-6 py-3 text-gray-600 hover:bg-blue-50 hover:text-blue-600">
                        <span class="mr-3">💰</span> Penggajian
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Header -->
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 flex-shrink-0 z-10">
            <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600 font-medium">Admin User</span>
                <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold shadow-sm">A</div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-auto p-8 relative">
            @yield('content')
        </div>
    </main>
</body>
</html>
