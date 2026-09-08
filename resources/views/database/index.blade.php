<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Database Penimbangan - UPT Terminal Babat</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <!-- Alpine.js -->
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js">
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FAFC;
        }

        [x-cloak] {
            display: none !important;
        }

        .card-shadow {
            box-shadow: 0px 8px 24px rgba(0, 0, 0, 0.06);
        }

        .row-shadow {
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body
    class="bg-slate-50 min-h-screen overflow-x-hidden"
    x-data="databaseManager()"
>

    <!-- ========================================================= -->
    <!-- MOBILE HEADER -->
    <!-- ========================================================= -->

    <header
        class="md:hidden bg-[#1E50A0] text-white p-4
               flex justify-between items-center
               sticky top-0 z-50 shadow-md"
    >
        <h1 class="text-lg font-black tracking-wide">
            UPT Terminal Babat
        </h1>

        <button
            @click="sidebarOpen = !sidebarOpen"
            class="text-2xl focus:outline-none px-2 py-1"
        >
            <i class="fa-solid fa-bars"></i>
        </button>
    </header>


    <!-- ========================================================= -->
    <!-- OVERLAY MOBILE -->
    <!-- ========================================================= -->

    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-30 md:hidden backdrop-blur-sm"
        x-transition.opacity
    ></div>


    <!-- ========================================================= -->
    <!-- SIDEBAR -->
    <!-- ========================================================= -->

    <aside
        class="fixed md:fixed inset-y-0 left-0 z-40
               bg-[#1E50A0] text-white
               flex flex-col
               shadow-xl
               transition-all duration-300 ease-in-out"

        :class="sidebarOpen
            ? 'w-64 p-6'
            : 'w-20 p-4'"
    >

        <!-- ================= HEADER SIDEBAR ================= -->

        <div
            class="flex items-center justify-between
                   mb-10 h-10"
        >

            <!-- Nama UPT -->
            <h1
                x-show="sidebarOpen"
                x-transition.opacity
                class="text-xl font-black
                       leading-tight tracking-wide
                       whitespace-nowrap"
            >
                UPT Terminal<br>
                Babat
            </h1>


            <!-- Tombol Hamburger -->
            <button
                @click="sidebarOpen = !sidebarOpen"
                class="text-2xl
                       focus:outline-none
                       hover:text-gray-300
                       transition
                       mx-auto"
                title="Buka/Tutup Sidebar"
            >
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>


        <!-- ================= MENU NAVIGASI ================= -->

        <nav class="space-y-3">

            <!-- BERANDA -->
            <a
                href="{{ route('home') }}"
                class="flex items-center
                       space-x-4
                       px-4 py-3
                       rounded-2xl
                       font-extrabold
                       text-base
                       hover:bg-white/10
                       transition"

                :class="!sidebarOpen && 'justify-center px-0'"

                title="Beranda"
            >

                <i
                    class="fa-solid fa-house
                           text-lg
                           w-6
                           text-center"
                ></i>

                <span
                    x-show="sidebarOpen"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Beranda
                </span>

            </a>


            <!-- DATABASE -->
            <a
                href="{{ route('database.index') }}"
                class="flex items-center
                       space-x-4
                       px-4 py-3
                       rounded-2xl
                       font-extrabold
                       text-base
                       bg-white/20
                       text-white
                       shadow-inner
                       transition"

                :class="!sidebarOpen && 'justify-center px-0'"

                title="Database"
            >

                <i
                    class="fa-solid fa-folder
                           text-lg
                           w-6
                           text-center"
                ></i>

                <span
                    x-show="sidebarOpen"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Database
                </span>

            </a>


            <!-- PENIMBANGAN -->
            <a
                href="{{ route('penimbangan.index') }}"
                class="flex items-center
                       space-x-4
                       px-4 py-3
                       rounded-2xl
                       font-extrabold
                       text-base
                       hover:bg-white/10
                       transition"

                :class="!sidebarOpen && 'justify-center px-0'"

                title="Penimbangan"
            >

                <i
                    class="fa-solid fa-scale-balanced
                           text-lg
                           w-6
                           text-center"
                ></i>

                <span
                    x-show="sidebarOpen"
                    x-transition.opacity
                    class="whitespace-nowrap"
                >
                    Penimbangan
                </span>

            </a>

        </nav>

    </aside>


    <!-- ========================================================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================================================= -->

    <main
        class="min-h-screen
               transition-all duration-300 ease-in-out
               p-4 md:p-10
               bg-slate-50
               overflow-x-hidden"

        :class="sidebarOpen
            ? 'md:ml-64'
            : 'md:ml-20'"
    >

        <!-- ===================================================== -->
        <!-- ALERT SUCCESS -->
        <!-- ===================================================== -->

        @if(session('success'))

            <div
                class="mb-6
                       bg-emerald-600
                       text-white
                       p-4
                       rounded-2xl
                       font-bold
                       flex
                       justify-between
                       items-center
                       shadow-lg"
            >

                <span class="flex items-center">

                    <i
                        class="fa-solid fa-circle-check
                               text-xl mr-3"
                    ></i>

                    {{ session('success') }}

                </span>

                <button
                    onclick="this.parentElement.remove()"
                    class="font-black text-2xl hover:opacity-75"
                >
                    &times;
                </button>

            </div>

        @endif


        <!-- ===================================================== -->
        <!-- ALERT ERROR -->
        <!-- ===================================================== -->

        @if(session('error'))

            <div
                class="mb-6
                       bg-rose-600
                       text-white
                       p-4
                       rounded-2xl
                       font-bold
                       flex
                       justify-between
                       items-center
                       shadow-lg"
            >

                <span class="flex items-center">

                    <i
                        class="fa-solid fa-triangle-exclamation
                               text-xl mr-3"
                    ></i>

                    {{ session('error') }}

                </span>

                <button
                    onclick="this.parentElement.remove()"
                    class="font-black text-2xl hover:opacity-75"
                >
                    &times;
                </button>

            </div>

        @endif


        <!-- ===================================================== -->
        <!-- VIEW 1 : DATA UTAMA -->
        <!-- ===================================================== -->

        <div
            x-show="!showSearchModal"
            x-transition
        >

            <!-- HEADER HALAMAN -->

            <div
                class="flex flex-col sm:flex-row
                       sm:items-center
                       justify-between
                       gap-4
                       mb-8"
            >

                <div>

                    <h2
                        class="text-2xl md:text-3xl
                               font-black
                               text-gray-900
                               tracking-wide"
                    >
                        Data Utama Penimbangan
                    </h2>

                </div>


                <!-- TOMBOL CARI -->

                <button
                    @click="showSearchModal = true"
                    class="bg-[#228B22]
                           hover:bg-[#1C731C]
                           text-white
                           font-black
                           px-6 py-3
                           rounded-2xl
                           flex
                           items-center
                           justify-center
                           space-x-2
                           transition
                           shadow-md
                           active:scale-95"
                >

                    <i
                        class="fa-solid fa-magnifying-glass
                               text-lg"
                    ></i>

                    <span
                        class="text-base md:text-lg"
                    >
                        Cari Data
                    </span>

                </button>

            </div>


            <!-- ================================================= -->
            <!-- CONTAINER DATA -->
            <!-- ================================================= -->

            <div
                class="bg-gray-200/50
                       p-4 md:p-8
                       rounded-[28px]
                       md:rounded-[35px]
                       card-shadow"
            >

                <!-- HEADER TABEL DESKTOP -->

                <div
                    class="hidden md:grid
                           grid-cols-12
                           gap-4
                           bg-gray-300/60
                           px-8 py-3.5
                           rounded-2xl
                           font-black
                           text-gray-700
                           text-center
                           text-base
                           mb-4"
                >

                    <div
                        class="col-span-5
                               text-left
                               pl-4"
                    >
                        Nama File Rekap
                    </div>

                    <div class="col-span-3 text-center">
                        Waktu Dibuat
                    </div>

                    <div class="col-span-4 text-center">
                        Status & Aksi
                    </div>

                </div>


                <!-- LIST DATA -->

                <div class="space-y-4">

                    <template
                        x-for="row in listRekap"
                        :key="row.id"
                    >

                        <div
                            class="grid
                                   grid-cols-1
                                   md:grid-cols-12
                                   gap-4
                                   bg-white
                                   p-5
                                   md:px-8
                                   md:py-4
                                   rounded-2xl
                                   row-shadow
                                   items-center
                                   font-extrabold
                                   text-gray-800
                                   transition
                                   hover:border-gray-300
                                   border
                                   border-transparent"
                        >

                            <!-- NAMA FILE -->

                            <div
                                class="md:col-span-5
                                       flex items-center
                                       space-x-3
                                       text-left"
                            >

                                <div
                                    class="p-2.5
                                           bg-blue-50
                                           text-[#1E50A0]
                                           rounded-xl
                                           hidden md:block"
                                >

                                    <i
                                        class="fa-regular
                                               fa-file-excel
                                               text-xl"
                                    ></i>

                                </div>

                                <span
                                    class="text-base
                                           md:text-lg
                                           tracking-wide
                                           text-gray-900
                                           font-bold
                                           break-all"
                                    x-text="row.nama_file"
                                ></span>

                            </div>


                            <!-- WAKTU -->

                            <div
                                class="md:col-span-3
                                       text-left
                                       md:text-center
                                       text-sm
                                       md:text-base
                                       text-gray-600
                                       flex items-center
                                       md:justify-center"
                            >

                                <i
                                    class="fa-regular
                                           fa-clock
                                           mr-2
                                           md:hidden
                                           text-gray-400"
                                ></i>

                                <span
                                    x-text="row.waktu_formatted"
                                ></span>

                            </div>


                            <!-- STATUS & AKSI -->

                            <div
                                class="md:col-span-4
                                       flex
                                       items-center
                                       justify-between
                                       md:justify-center
                                       space-x-4
                                       relative"

                                x-data="{ openMenu: false }"
                            >

                                <!-- STATUS WAIT -->

                                <template
                                    x-if="row.status &&
                                          row.status.toLowerCase() === 'wait'"
                                >

                                    <span
                                        class="bg-[#FFDE59]
                                               text-gray-900
                                               font-black
                                               px-6 py-1.5
                                               rounded-xl
                                               text-xs
                                               md:text-sm
                                               min-w-[90px]
                                               text-center
                                               shadow-sm"
                                    >
                                        Wait
                                    </span>

                                </template>


                                <!-- STATUS DONE -->

                                <template
                                    x-if="row.status &&
                                          row.status.toLowerCase() === 'done'"
                                >

                                    <span
                                        class="bg-[#228B22]
                                               text-white
                                               font-black
                                               px-6 py-1.5
                                               rounded-xl
                                               text-xs
                                               md:text-sm
                                               min-w-[90px]
                                               text-center
                                               shadow-sm"
                                    >
                                        Done
                                    </span>

                                </template>


                                <!-- MENU KEBAB -->

                                <div class="relative">

                                    <button
                                        @click="openMenu = !openMenu"
                                        class="text-gray-700
                                               font-black
                                               text-2xl
                                               hover:bg-gray-100
                                               w-10 h-10
                                               rounded-xl
                                               flex
                                               items-center
                                               justify-center
                                               focus:outline-none
                                               transition"
                                    >
                                        ⋮
                                    </button>


                                    <!-- DROPDOWN -->

                                    <div
                                        x-show="openMenu"
                                        @click.outside="openMenu = false"
                                        x-cloak
                                        x-transition

                                        class="absolute
                                               right-0
                                               top-12
                                               w-52
                                               bg-white
                                               border
                                               border-gray-100
                                               rounded-2xl
                                               shadow-2xl
                                               z-50
                                               p-2
                                               text-left"
                                    >

                                        <!-- DOWNLOAD -->

                                        <a
                                            :href="'{{ url('database/download') }}/' + row.id"
                                            class="w-full
                                                   text-left
                                                   text-gray-700
                                                   font-bold
                                                   px-3 py-2.5
                                                   hover:bg-blue-50
                                                   hover:text-[#1E50A0]
                                                   rounded-xl
                                                   text-xs
                                                   flex items-center
                                                   transition"
                                        >

                                            <i
                                                class="fa-solid
                                                       fa-download
                                                       mr-2.5
                                                       text-base"
                                            ></i>

                                            Unduh File Excel

                                        </a>


                                        <!-- DELETE -->

                                        <form
                                            :action="'{{ url('database/delete') }}/' + row.id"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus file rekap ini?')"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="w-full
                                                       text-left
                                                       text-rose-600
                                                       font-bold
                                                       px-3 py-2.5
                                                       hover:bg-rose-50
                                                       rounded-xl
                                                       text-xs
                                                       flex items-center
                                                       transition"
                                            >

                                                <i
                                                    class="fa-solid
                                                           fa-trash
                                                           mr-2.5
                                                           text-base"
                                                ></i>

                                                Hapus File

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </template>


                    <!-- ================================================= -->
                    <!-- EMPTY STATE -->
                    <!-- ================================================= -->

                    <template
                        x-if="listRekap.length === 0"
                    >

                        <div
                            class="text-center
                                   py-16
                                   text-gray-400
                                   font-bold
                                   text-base
                                   bg-white
                                   rounded-2xl
                                   shadow-sm
                                   border
                                   border-dashed
                                   border-gray-200"
                        >

                            <i
                                class="fa-solid
                                       fa-folder-open
                                       text-4xl
                                       mb-3
                                       text-gray-300
                                       block"
                            ></i>

                            Belum ada file rekap penimbangan.
                            Klik tombol "Cari Data"
                            untuk membuat rekap baru.

                        </div>

                    </template>

                </div>

            </div>

        </div>


        <!-- ===================================================== -->
        <!-- VIEW 2 : CARI DATA -->
        <!-- ===================================================== -->

        <div
            x-show="showSearchModal"
            x-cloak
            x-transition
            class="max-w-3xl mx-auto pt-4"
        >

            <h2
                class="text-2xl md:text-3xl
                       font-black
                       text-gray-900
                       text-center
                       mb-8
                       tracking-wide"
            >
                Cari Data Penimbangan
            </h2>


            <div
                class="bg-gray-200/50
                       p-6 md:p-10
                       rounded-[28px]
                       md:rounded-[35px]
                       card-shadow"
            >

                <form
                    action="{{ route('database.generate') }}"
                    method="POST"
                    class="space-y-6"
                >

                    @csrf


                    <!-- TANGGAL -->

                    <div
                        class="flex flex-col
                               sm:flex-row
                               sm:items-center
                               gap-2 sm:gap-6"
                    >

                        <label
                            class="sm:w-36
                                   font-black
                                   text-sm
                                   md:text-base
                                   text-gray-800
                                   uppercase
                                   tracking-wider"
                        >
                            TANGGAL
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            x-model="filter.tanggal"
                            required

                            class="flex-1
                                   bg-white
                                   border
                                   border-gray-200
                                   rounded-2xl
                                   px-5 py-3.5
                                   font-bold
                                   text-base
                                   text-gray-800
                                   shadow-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-[#1E50A0]
                                   transition"
                        >

                    </div>


                    <!-- GERBONG -->

                    <div
                        class="flex flex-col
                               sm:flex-row
                               sm:items-center
                               gap-2 sm:gap-6"
                    >

                        <label
                            class="sm:w-36
                                   font-black
                                   text-sm
                                   md:text-base
                                   text-gray-800
                                   uppercase
                                   tracking-wider"
                        >
                            GERBONG
                        </label>

                        <select
                            name="kode_gerbong"
                            x-model="filter.kode_gerbong"
                            required

                            class="flex-1
                                   bg-white
                                   border
                                   border-gray-200
                                   rounded-2xl
                                   px-5 py-3.5
                                   font-bold
                                   text-base
                                   text-gray-800
                                   shadow-sm
                                   focus:outline-none
                                   focus:ring-2
                                   focus:ring-[#1E50A0]
                                   transition
                                   uppercase"
                        >

                            <option
                                value=""
                                disabled
                            >
                                PILIH GERBONG
                            </option>

                            <option value="ALL">
                                SEMUA GERBONG
                            </option>

                            @foreach($gerbongList as $gb)

                                <option value="{{ $gb }}">
                                    {{ $gb }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <!-- BUTTON -->

                    <div
                        class="flex
                               justify-end
                               space-x-3
                               pt-4"
                    >

                        <!-- BATAL -->

                        <button
                            type="button"
                            @click="showSearchModal = false"

                            class="bg-gray-300
                                   hover:bg-gray-400
                                   text-gray-800
                                   font-black
                                   text-base
                                   px-6 py-2.5
                                   rounded-2xl
                                   transition"
                        >
                            Batal
                        </button>


                        <!-- BUAT REKAP -->

                        <button
                            type="submit"

                            class="bg-[#228B22]
                                   hover:bg-[#1C731C]
                                   text-white
                                   font-black
                                   text-base
                                   px-8 py-2.5
                                   rounded-2xl
                                   shadow-md
                                   transition
                                   active:scale-95"
                        >
                            Buat Rekap
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </main>


    <!-- ========================================================= -->
    <!-- ALPINE JS -->
    <!-- ========================================================= -->

    <script>
        function databaseManager() {

            return {

                // Sidebar terbuka saat pertama kali halaman dibuka
                sidebarOpen: true,

                // Modal pencarian
                showSearchModal: false,

                // Filter pencarian
                filter: {
                    tanggal: '',
                    kode_gerbong: ''
                },

                // Data rekap dari Laravel
                listRekap: @json($rekapFiles ?? [])

            }

        }
    </script>

</body>
</html>