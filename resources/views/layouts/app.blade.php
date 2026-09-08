<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIMKABAR - UPT Terminal Babat & Gudang SBI</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kai: {
                            navy: '#0d2d5e',   // Warna Utama Navy KAI
                            orange: '#ff6600', // Warna Aksen Orange KAI
                            gray: '#f4f6f9'
                        }
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js untuk Handling Interaktif Instan -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-kai-gray font-sans antialiased text-gray-800 flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <header class="bg-kai-navy text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="bg-kai-orange font-extrabold px-2.5 py-1 rounded text-white tracking-wider text-sm">KAI</span>
                <h1 class="font-bold text-lg tracking-wide">SIMKABAR <span class="text-xs font-normal opacity-80">| Sistem Monitoring Penimbangan</span></h1>
            </div>
            <div class="text-xs text-right hidden sm:block">
                <p class="font-semibold text-kai-orange">UPT Terminal Babat</p>
                <p class="opacity-75">{{ date('d F Y') }}</p>
            </div>
        </div>
    </header>

    <!-- Main Content Dynamic Section -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 md:p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-3 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero) - Angkutan Barang Retail.
    </footer>

    @stack('scripts')
</body>
</html>