<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penimbangan Loket UPT Terminal Babat</title>

    <!-- Google Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFFFF;
        }

        /* Custom Keyframe untuk Efek Membal Halus Saat Tombol Diklik */
        @keyframes clickBounce {
            0% { transform: scale(1); }
            50% { transform: scale(0.93); }
            100% { transform: scale(1); }
        }

        .btn-animate-click:active {
            animation: clickBounce 0.15s ease-in-out;
        }
    </style>
</head>
<body class="bg-white min-h-screen flex flex-col justify-between antialiased selection:bg-blue-600 selection:text-white">

    <!-- HEADER / LOGO BAR -->
    <header class="w-full px-8 py-6 flex flex-row justify-between items-center max-w-7xl mx-auto">
        <!-- Logo Kiri: Danantara Indonesia -->
        <div class="flex items-center transition-transform duration-300 hover:scale-105">
            <img src="{{ asset('images/danantara.png') }}" 
                 alt="Danantara Indonesia" 
                 class="h-10 md:h-12 w-auto object-contain">
        </div>

        <!-- Group Logo Kanan: CITAR, Semakin Melayani, KAI -->
        <div class="flex items-center space-x-4 md:space-x-6">
            <img src="{{ asset('images/citar.png') }}" 
                 alt="CITAR" 
                 class="h-8 md:h-10 w-auto object-contain transition-transform duration-300 hover:scale-105">
            <img src="{{ asset('images/semakin-melayani.png') }}" 
                 alt="Semakin Melayani" 
                 class="h-8 md:h-10 w-auto object-contain transition-transform duration-300 hover:scale-105">
            <img src="{{ asset('images/kai.png') }}" 
                 alt="Logo KAI" 
                 class="h-8 md:h-10 w-auto object-contain transition-transform duration-300 hover:scale-105">
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <main class="flex-1 flex flex-col items-center justify-center text-center px-4 -mt-10">
        
        <!-- Judul Utama (Bold Extra & Fade In) -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-black tracking-tight leading-tight max-w-4xl select-none">
            Penimbangan Loket<br>UPT Terminal Babat
        </h1>

        <!-- Container Tombol Utama dengan Interaksi Animasi -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-5 w-full max-w-md">
            
            <!-- Tombol Database -->
            <!-- Penjelasan Efek: 
                 1. bg-[#1E50A0] -> Biru Utama sesuai desain
                 2. hover:bg-[#153A75] & hover:-translate-y-1 -> Saat kursor menempel: berubah biru lebih gelap & terangkat ke atas 4px
                 3. hover:shadow-lg hover:shadow-blue-900/30 -> Efek bayangan menyala halus saat di-hover
                 4. active:scale-95 -> Efek saat diklik: tombol membal mengecil (efek tombol fisik dipencet)
                 5. transition-all duration-200 -> Semua perubahan warna & gerakan berjalan sangat halus (200ms)
            -->
            <a href="{{ url('/database') }}" 
               class="btn-animate-click w-full sm:w-48 py-3.5 bg-[#1E50A0] hover:bg-[#153A75] active:bg-[#0D2650] text-white font-bold text-xl rounded-xl transition-all duration-200 ease-in-out shadow-md hover:shadow-lg hover:shadow-blue-900/30 hover:-translate-y-1 text-center select-none flex items-center justify-center space-x-2">
                <span>Database</span>
            </a>

            <!-- Tombol Penimbangan -->
            <a href="{{ url('/penimbangan') }}" 
               class="btn-animate-click w-full sm:w-48 py-3.5 bg-[#1E50A0] hover:bg-[#153A75] active:bg-[#0D2650] text-white font-bold text-xl rounded-xl transition-all duration-200 ease-in-out shadow-md hover:shadow-lg hover:shadow-blue-900/30 hover:-translate-y-1 text-center select-none flex items-center justify-center space-x-2">
                <span>Penimbangan</span>
            </a>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="py-6 text-center text-gray-400 text-xs">
        &copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero) - Angkutan Barang Retail.
    </footer>

</body>
</html>