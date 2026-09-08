<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Loket Penimbangan - SIMKABAR PT KAI</title>
    
    <!-- Google Fonts & Tailwind CSS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js untuk Reaktivitas Toggle Sidebar -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Poppins', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<!-- Inisialisasi State AlpineJS untuk Sidebar (default: terbuka) -->
<body class="bg-gray-50 flex h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

    <!-- SIDEBAR NAVIGASI UTAMA -->
    <aside 
        :class="sidebarOpen ? 'w-64 p-6' : 'w-20 p-4'" 
        class="bg-[#1E50A0] text-white flex flex-col justify-between shadow-xl transition-all duration-300 ease-in-out">
        
        <div>
            <!-- Header Sidebar & Tombol Toggle Hamburger -->
            <div class="flex items-center justify-between mb-10 h-10">
                <h1 x-show="sidebarOpen" x-transition.opacity class="text-xl font-black leading-tight tracking-wide whitespace-nowrap">
                    UPT Terminal<br>Babat
                </h1>
                <!-- Button Toggle Sidebar -->
                <button @click="sidebarOpen = !sidebarOpen" class="text-2xl focus:outline-none hover:text-gray-300 transition mx-auto">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <!-- Menu Navigasi -->
            <nav class="space-y-3">
                <!-- 1. Link Beranda (Aktif Ke Route Home) -->
                <a href="{{ route('home') }}" 
                   class="flex items-center space-x-4 px-4 py-3 rounded-2xl font-extrabold hover:bg-white/10 transition"
                   :class="!sidebarOpen && 'justify-center px-0'"
                   title="Beranda">
                    <i class="fa-solid fa-house text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Beranda</span>
                </a>

                <!-- 2. Link Database (Aktif Ke Route Database Index) -->
                <a href="{{ route('database.index') }}" 
                   class="flex items-center space-x-4 px-4 py-3 rounded-2xl font-extrabold hover:bg-white/10 transition"
                   :class="!sidebarOpen && 'justify-center px-0'"
                   title="Database">
                    <i class="fa-solid fa-folder text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Database</span>
                </a>

                <!-- 3. Link Penimbangan (Active State) -->
                <a href="{{ route('penimbangan.index') }}" 
                   class="flex items-center space-x-4 px-4 py-3 rounded-2xl font-extrabold bg-white/20 text-white shadow-inner"
                   :class="!sidebarOpen && 'justify-center px-0'"
                   title="Penimbangan">
                    <i class="fa-solid fa-scale-balanced text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">Penimbangan</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- KONTEN UTAMA: PEMILIHAN LOKET -->
    <main class="flex-1 flex items-center justify-center p-8 transition-all duration-300">
        <div class="w-full max-w-2xl">
            <!-- Grid 4 Loket Penimbangan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([1, 2, 3, 4] as $loketNum)
                    <a href="{{ route('penimbangan.workspace', ['loket' => $loketNum]) }}" 
                       class="bg-[#FF6B1A] hover:bg-[#E55A0F] text-white text-center py-6 rounded-3xl font-black text-2xl tracking-wider shadow-lg transform hover:-translate-y-1 transition duration-200 active:scale-95 block">
                        LOKET {{ $loketNum }}
                    </a>
                @endforeach
            </div>
        </div>
    </main>

</body>
</html>