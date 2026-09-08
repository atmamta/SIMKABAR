<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penimbangan Loket {{ $loket }} - PT KAI</title>
    
    <!-- Third-Party Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Framework CDNs -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #FFFFFF; 
        }
        .card-shadow { 
            box-shadow: 0px 6px 18px rgba(0, 0, 0, 0.08); 
        }
        [x-cloak] { 
            display: none !important; 
        }

        /* Menghilangkan panah spinner pada input type number */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* Scrollbar Halus untuk Navigasi Tab / Filter */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Format Struk / Resi Cetak Thermal (80mm) */
        @media print {
            body * { visibility: hidden; }
            #area-resi-cetak, #area-resi-cetak * { visibility: visible; }
            #area-resi-cetak {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
                font-family: 'Courier New', Courier, monospace;
                font-size: 11px;
                color: #000;
                padding: 5px;
            }
        }
    </style>
</head>
<body class="p-6 bg-white min-h-screen" x-data="penimbanganWorkspace()">

    <!-- FLASH NOTIFICATION: ERROR -->
    @if(session('error'))
        <div class="mb-4 bg-red-500 text-white p-4 rounded-2xl font-bold flex justify-between items-center shadow-lg">
            <span><i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="font-black text-xl">&times;</button>
        </div>
    @endif

    <!-- FLASH NOTIFICATION: SUCCESS -->
    @if(session('success'))
        <div class="mb-4 bg-green-600 text-white p-4 rounded-2xl font-bold flex justify-between items-center shadow-lg">
            <span><i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="font-black text-xl">&times;</button>
        </div>
    @endif

    <!-- HEADER & NAVIGASI KEMBALI -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('penimbangan.index') }}" class="text-3xl font-black text-black hover:opacity-75 transition" title="Kembali">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h1 class="text-3xl font-black text-[#1E50A0] tracking-wider uppercase">LOKET {{ $loket }}</h1>
        <div class="w-8"></div>
    </div>

    <!-- SECTION 1: MONITORING KAPASITAS GERBONG LOKET AKTIF -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        @foreach($monitoringGerbong as $gb)
            <div class="bg-[#FF6B1A] p-5 rounded-3xl text-white card-shadow">
                <h2 class="text-3xl font-black mb-3">{{ $gb['kode_gerbong'] }}</h2>
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/95 text-black p-2.5 rounded-2xl text-center shadow-sm flex flex-col justify-center">
                        <span class="block text-[10px] font-bold text-gray-500 uppercase">Total Volume (Kg)</span>
                        <span class="text-2xl font-black">{{ number_format($gb['total_volume'], 0, ',', '.') }}</span>
                    </div>

                    <div class="bg-white/95 text-black p-2.5 rounded-2xl text-center shadow-sm flex flex-col justify-center">
                        <span class="block text-[10px] font-bold text-gray-500 uppercase">Sisa Volume (Kg)</span>
                        <span class="text-2xl font-black">{{ number_format($gb['sisa_volume'], 0, ',', '.') }}</span>
                    </div>

                    <div class="bg-white/95 p-2.5 rounded-2xl text-center flex flex-col justify-center items-center shadow-sm">
                        <span class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Status</span>
                        @php
                            $status = strtolower($gb['status']);
                            $statusClass = match($status) {
                                'aman'           => 'bg-[#D4EDDA] text-[#155724] border-green-300',
                                'waspada'        => 'bg-[#FFF3CD] text-[#856404] border-yellow-300',
                                'bahaya', 'penuh' => 'bg-[#F8D7DA] text-[#721C24] border-red-300',
                                default          => 'bg-gray-200 text-gray-800 border-gray-300',
                            };
                        @endphp
                        <span class="px-3.5 py-1 rounded-xl text-xs font-black border {{ $statusClass }}">
                            {{ ucfirst($gb['status']) }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- SECTION 2 & 3: FORM WORKSPACE & RINGKASAN AKUMULASI MITRA -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- FORM INPUT PENIMBANGAN -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl card-shadow border border-gray-100">
            <h2 class="text-center font-black text-2xl text-[#1E50A0] tracking-wider mb-6">PENIMBANGAN</h2>

            <form action="{{ route('penimbangan.store') }}" method="POST" id="formPenimbangan" class="space-y-4">
                @csrf
                <input type="hidden" name="loket" value="{{ $loket }}">
                <input type="hidden" name="tujuan" x-model="selectedTujuan" required>
                <input type="hidden" name="kode_gerbong" x-model="selectedGerbong" required>

                <!-- 1. INPUT BERAT KOTOR (BRUTO) -->
                <div class="flex items-center">
                    <label class="w-36 shrink-0 font-black text-sm uppercase text-gray-800 text-left">BERAT</label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           name="berat_kotor" 
                           x-model="form.berat_kotor" 
                           @wheel.prevent
                           @keydown.enter.prevent="focusNext($event)" 
                           @keydown.arrow-down.prevent="focusNext($event)" 
                           required 
                           class="nav-input flex-1 border-2 border-gray-200 rounded-2xl px-4 py-2 font-bold text-lg focus:outline-none focus:border-blue-500 transition">
                </div>

                <!-- 2. INPUT HANDPALLET -->
                <div class="flex items-center">
                    <label class="w-36 shrink-0 font-black text-sm uppercase text-gray-800 text-left">HANDPALLET</label>
                    <input type="number" 
                           step="0.01" 
                           min="0"
                           name="handpallet" 
                           x-model="form.handpallet" 
                           @wheel.prevent
                           @keydown.enter.prevent="focusNext($event)" 
                           @keydown.arrow-down.prevent="focusNext($event)" 
                           @keydown.arrow-up.prevent="focusPrev($event)" 
                           class="nav-input flex-1 border-2 border-gray-200 rounded-2xl px-4 py-2 font-bold text-lg focus:outline-none focus:border-blue-500 transition">
                </div>

                <!-- 3. INPUT DYNAMIC KAYU -->
                <div class="flex items-center">
                    <label class="w-36 shrink-0 font-black text-sm uppercase text-gray-800 text-left">KAYU</label>
                    <div class="flex-1 flex items-center space-x-2 flex-wrap">
                        <template x-for="(val, idx) in kayuInputs" :key="idx">
                            <input type="number" 
                                   step="0.01" 
                                   min="0"
                                   :name="'kayu['+idx+']'" 
                                   x-model="kayuInputs[idx]" 
                                   @wheel.prevent
                                   @keydown.enter.prevent="focusNext($event)" 
                                   @keydown.arrow-down.prevent="focusNext($event)" 
                                   @keydown.arrow-up.prevent="focusPrev($event)" 
                                   class="nav-input w-24 border-2 border-gray-200 rounded-2xl px-3 py-2 font-bold text-center focus:outline-none focus:border-blue-500 transition mb-1">
                        </template>
                        <button type="button" @click="removeKayu()" class="bg-[#F8D7DA] text-[#721C24] font-black px-3.5 py-2 rounded-2xl hover:bg-red-300 transition mb-1" title="Hapus Input">-</button>
                        <button type="button" @click="addKayu()" class="bg-[#D4EDDA] text-[#155724] font-black px-3.5 py-2 rounded-2xl hover:bg-green-300 transition mb-1" title="Tambah Input">+</button>
                    </div>
                </div>

                <!-- 4. FITUR PREVIEW BERAT BERSIH (NETTO) REAL-TIME -->
                <div class="flex items-center">
                    <label class="w-36 shrink-0 font-black text-sm uppercase text-gray-800 text-left">
                        BERAT BERSIH
                    </label>
                    <div class="flex-1 bg-blue-50 border-2 border-blue-200 rounded-2xl px-4 py-2 flex items-center justify-between">
                        <span class="text-xl font-black text-[#1E50A0]" x-text="formatAngka(hitungBeratBersih) + ' Kg'"></span>
                    </div>
                </div>

                <!-- 5. AUTOCOMPLETE MITRA WITH STRICT VALIDATION -->
                <div class="flex items-center relative">
                    <label class="w-36 shrink-0 font-black text-sm uppercase text-gray-800 text-left">MITRA</label>
                    <div class="flex-1 relative">
                        <input type="text" 
                               name="nama_mitra" 
                               x-model="searchMitra" 
                               @focus="showDropdown = true" 
                               @click.away="validateMitraSelection()" 
                               @keydown.enter.prevent="selectFirstMitra($event)"
                               @keydown.arrow-up.prevent="focusPrev($event)"
                               @keydown.arrow-down.prevent="showDropdown = true"
                               required 
                               class="nav-input w-full border-2 border-gray-200 rounded-2xl px-4 py-2 font-bold text-lg focus:outline-none focus:border-blue-500 transition uppercase">
                        
                        <!-- DROPDOWN PENCARIAN -->
                        <div x-show="showDropdown && filteredMitras.length > 0" 
                             class="absolute z-50 w-full bg-white border border-gray-200 rounded-2xl shadow-xl mt-1 max-h-44 overflow-y-auto" 
                             x-cloak>
                            <template x-for="(m, index) in filteredMitras" :key="m">
                                <div @click="selectMitra(m)" 
                                     :class="{'bg-blue-100 text-blue-900': index === 0}" 
                                     class="px-4 py-2.5 hover:bg-blue-50 cursor-pointer font-bold text-gray-800 border-b border-gray-100 flex justify-between items-center">
                                    <span x-text="m"></span>
                                    <template x-if="index === 0">
                                        <span class="text-[10px] bg-blue-200 text-blue-800 px-2 py-0.5 rounded font-black">Tekan Enter ↵</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 6. SELEKSI TUJUAN & GERBONG -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    
                    <!-- SELEKSI TUJUAN -->
                    <div>
                        <label class="block font-black text-xs uppercase mb-2 text-gray-800">
                            TUJUAN <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="t in tujuanList" :key="t">
                                <button type="button" 
                                        @click="selectedTujuan = t" 
                                        :class="selectedTujuan === t ? 'bg-[#1E50A0] text-white shadow-md scale-105' : 'bg-[#FFDE59] text-black hover:bg-yellow-300'" 
                                        class="px-3.5 py-2 rounded-xl font-black text-xs transition active:scale-95 shadow-sm" 
                                        x-text="t">
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- SELEKSI GERBONG LOKET AKTIF -->
                    <div>
                        <label class="block font-black text-xs uppercase mb-2 text-gray-800">
                            GERBONG <span class="text-red-500">*</span>
                        </label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($monitoringGerbong as $gb)
                                <button type="button" 
                                        @click="selectedGerbong = '{{ $gb['kode_gerbong'] }}'" 
                                        :class="selectedGerbong === '{{ $gb['kode_gerbong'] }}' ? 'bg-[#1E50A0] text-white shadow-md scale-105' : 'bg-[#FFDE59] text-black hover:bg-yellow-300'" 
                                        class="px-4 py-2 rounded-xl font-black text-xs transition active:scale-95 shadow-sm">
                                    {{ $gb['kode_gerbong'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- SUBMIT BUTTON -->
                <div class="mt-6">
                    <button type="button" 
                            @click="validateAndSubmit()" 
                            :disabled="isSubmitting" 
                            :class="(selectedTujuan && selectedGerbong) ? 'bg-[#228B22] hover:bg-[#1C731C] text-white' : 'bg-gray-400 text-gray-100 cursor-pointer'" 
                            class="w-full font-black text-lg py-3 rounded-2xl shadow-md transition active:scale-95 flex items-center justify-center">
                        <span x-show="!isSubmitting">INPUT</span>
                        <span x-show="isSubmitting" x-cloak><i class="fa-solid fa-spinner fa-spin mr-2"></i> MEMPROSES...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- CARD RINGKASAN MITRA -->
        <div class="bg-[#1E50A0] p-6 rounded-3xl text-white card-shadow flex flex-col justify-between">
            <div>
                <h2 class="text-center font-black text-xl tracking-wider mb-6 uppercase">RINGKASAN MITRA</h2>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($ringkasanMitra as $r)
                        <div class="flex flex-col justify-between bg-white rounded-2xl p-3 text-black font-extrabold shadow-sm">
                            <span class="text-xs text-gray-600 uppercase tracking-wider truncate mb-1">{{ $r->nama_mitra }}</span>
                            <span class="bg-gray-100 text-black px-2 py-1 rounded-xl text-center text-sm border border-gray-200">
                                {{ number_format($r->total_tonase, 0, ',', '.') }} Kg
                            </span>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-10 text-white/70 font-bold text-sm">
                            Belum ada timbangan masuk hari ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- SECTION 4: DATA TABLE & DRILL-DOWN TRANSACTION -->
    <div class="bg-[#EAEAEA] p-6 rounded-3xl card-shadow">
        
        <!-- LEVEL 1: TAB SELEKSI MITRA -->
        <div class="flex items-center justify-start border-b border-gray-300 pb-3 mb-4">
            <div class="flex items-center space-x-3 overflow-x-auto no-scrollbar">
                <template x-for="m in uniqueMitras" :key="m">
                    <button @click="selectMitraTab(m)" 
                            :class="activeMitraTab === m ? 'bg-[#1E50A0] text-white shadow-md font-black' : 'bg-white text-black hover:bg-gray-100 font-bold'" 
                            class="px-6 py-2.5 rounded-2xl text-sm uppercase transition-all duration-150 shadow-sm shrink-0" 
                            x-text="m">
                    </button>
                </template>
            </div>
        </div>

        <!-- LEVEL 2: FILTER GERBONG PER MITRA (SMOOTH & NO CLIPPING) -->
        <div class="flex items-center space-x-2 overflow-x-auto mb-6 no-scrollbar">
            <span class="text-xs font-black text-gray-500 uppercase mr-2 shrink-0 flex items-center">
                <i class="fa-solid fa-filter text-gray-400 mr-1.5"></i> Pilih Gerbong:
            </span>
    
            <template x-for="g in uniqueGerbongByActiveMitra" :key="g">
                <button type="button"
                        @click="activeGerbongTab = g; activeDetailView = false" 
                        :class="activeGerbongTab === g 
                            ? 'bg-[#1E50A0] text-white shadow-md font-black border-2 border-[#1E50A0]' 
                            : 'bg-white text-gray-700 hover:bg-blue-50 font-extrabold border-2 border-transparent hover:border-gray-200'" 
                        class="px-4 py-2 rounded-2xl text-xs uppercase transition-all duration-200 active:scale-95 flex items-center shrink-0 space-x-2 cursor-pointer">
                    <i class="fa-solid fa-train text-[11px]" :class="activeGerbongTab === g ? 'text-yellow-400' : 'text-gray-400'"></i>
            
                    <!-- PERUBAHAN: Menampilkan format NAMA MITRA - KODE GERBONG (contoh: LNP - B11) -->
                    <span x-text="`${activeMitraTab} - ${g}`"></span>
                </button>
            </template>
        </div>

        <!-- GRID REKAP RELASI TUJUAN BERDASARKAN MITRA & GERBONG AKTIF -->
        <div x-show="!activeDetailView" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
            <template x-for="(items, tujuanKey) in groupedByTujuan" :key="tujuanKey">
                <div @click="openDetail(tujuanKey)" class="bg-[#1E50A0] text-white p-4 rounded-3xl cursor-pointer hover:scale-[1.02] transition shadow-md flex flex-col justify-between border-2 border-transparent hover:border-white">
                    <div>
                        <h3 class="font-black text-xl text-center mb-3 uppercase tracking-wide border-b border-white/20 pb-1" x-text="tujuanKey"></h3>
                        <div class="space-y-1 mb-4 max-h-36 overflow-y-auto px-1">
                            <template x-for="item in items" :key="item.id">
                                <div class="text-base font-bold text-blue-100 text-center" x-text="formatAngka(item.berat_bersih)"></div>
                            </template>
                        </div>
                    </div>

                    <div class="bg-white/10 rounded-2xl p-2.5 mt-2 border border-white/20 text-center">
                        <span class="block text-[10px] font-bold text-blue-200 uppercase tracking-wider">Total</span>
                        <span class="text-base font-black text-white" x-text="formatAngka(hitungTotalTujuan(items)) + ' Kg'"></span>
                    </div>
                </div>
            </template>
        </div>

        <!-- RINCIAN DETAIL TRANSAKSI -->
        <div x-show="activeDetailView" class="bg-[#1E50A0] rounded-3xl p-6 text-white card-shadow overflow-x-auto" x-cloak>
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-lg uppercase tracking-wide">
                    RELASI: <span x-text="selectedTujuanKey" class="text-yellow-300"></span>
                    (<span x-text="activeMitraTab"></span> - GERBONG <span x-text="activeGerbongTab"></span>)
                </h3>
                <button @click="activeDetailView = false" class="bg-white/20 text-white font-bold px-4 py-2 rounded-xl hover:bg-white/30 text-xs transition flex items-center">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Ringkasan
                </button>
            </div>

            <table class="w-full text-center border-separate border-spacing-y-2 table-fixed min-w-[700px]">
                <thead>
                    <tr class="text-[11px] font-black uppercase text-blue-100 tracking-wider">
                        <th class="w-[12%] px-2 py-1">Berat<br>Bersih</th>
                        <th class="w-[12%] px-2 py-1">Mitra</th>
                        <th class="w-[12%] px-2 py-1">Tujuan</th>
                        <th class="w-[10%] px-2 py-1">Gerbong</th>
                        <th class="w-[22%] px-2 py-1">Waktu</th>
                        <th class="w-[10%] px-2 py-1">Berat<br>Kotor</th>
                        <th class="w-[10%] px-2 py-1">Hand<br>Pallet</th>
                        <th class="w-[8%] px-2 py-1">Kayu</th>
                        <th class="w-[4%] px-2 py-1">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-xs font-extrabold text-black">
                    <template x-for="row in selectedTujuanData" :key="row.id">
                        <tr class="bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                            <td class="py-3 px-2 font-black rounded-l-2xl text-blue-950 truncate" x-text="formatAngka(row.berat_bersih)"></td>
                            <td class="py-3 px-2 truncate" x-text="row.nama_mitra"></td>
                            <td class="py-3 px-2 truncate" x-text="row.tujuan"></td>
                            <td class="py-3 px-2 truncate" x-text="row.kode_gerbong"></td>
                            <td class="py-3 px-2 text-gray-500 whitespace-nowrap text-[11px]" x-text="formatWaktu(row.created_at)"></td>
                            <td class="py-3 px-2 truncate" x-text="formatAngka(row.berat_kotor)"></td>
                            <td class="py-3 px-2 truncate" x-text="formatAngka(row.handpallet)"></td>
                            <td class="py-3 px-2 truncate" x-text="formatAngka(row.total_kayu)"></td>
                            <td class="py-3 px-2 rounded-r-2xl relative" x-data="{ openMenu: false }">
                                <button @click="openMenu = !openMenu" class="text-gray-700 font-black text-base hover:text-black focus:outline-none p-1">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                            
                                <div x-show="openMenu" 
                                     @click.away="openMenu = false" 
                                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-2xl shadow-2xl z-50 text-left p-2" 
                                     x-cloak>
                                    <form :action="'/penimbangan/delete/' + row.id" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi timbangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left text-red-600 font-bold px-3 py-2 hover:bg-red-50 rounded-xl text-xs flex items-center">
                                            <i class="fa-solid fa-trash mr-2"></i> Hapus
                                        </button>
                                    </form>

                                    <button @click="openModalPindah(row); openMenu = false" class="w-full text-left text-blue-700 font-bold px-3 py-2 hover:bg-blue-50 rounded-xl text-xs flex items-center">
                                        <i class="fa-solid fa-arrows-left-right mr-2"></i> Pindah (Gerbong/Mitra)
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </div>

    <!-- MODAL PINDAH GERBONG / MITRA -->
    <div x-show="showPindahModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-3xl p-6 w-full max-w-md shadow-2xl">
            <h3 class="text-xl font-black text-[#1E50A0] mb-2 text-center">PINDAH GERBONG / MITRA</h3>
            <p class="text-center text-xs text-gray-500 mb-4 font-bold">ID Transaksi: <span x-text="modalData.id"></span></p>
            
            <form :action="'/penimbangan/pindah/' + modalData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-black uppercase text-gray-700 mb-1">Pilih Gerbong Baru</label>
                    <select name="kode_gerbong" x-model="modalData.kode_gerbong" class="w-full border-2 border-gray-200 rounded-xl p-2.5 font-bold focus:outline-none focus:border-blue-500">
                        @foreach($semuaGerbong as $g)
                            <option value="{{ $g }}">{{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black uppercase text-gray-700 mb-1">Pilih Mitra Resmi</label>
                    <select name="nama_mitra" x-model="modalData.nama_mitra" class="w-full border-2 border-gray-200 rounded-xl p-2.5 font-bold uppercase focus:outline-none focus:border-blue-500" required>
                        @foreach($mitraList as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex space-x-3 pt-3">
                    <button type="button" @click="showPindahModal = false" class="flex-1 bg-gray-200 font-bold py-2.5 rounded-xl text-gray-700 hover:bg-gray-300 transition">Batal</button>
                    <button type="submit" class="flex-1 bg-[#1E50A0] text-white font-black py-2.5 rounded-xl hover:bg-blue-900 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- AREA CETAK RESI THERMAL TERSEMBUNYI -->
    <div id="area-resi-cetak" style="display: none;">
        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">
            PT KERETA API INDONESIA (PERSERO)<br>
            ANGKUTAN BARANG RETAIL<br>
            --------------------------------
        </div>
        <div>
            <strong>No Resi  :</strong> <span id="resi-no"></span><br>
            <strong>Loket    :</strong> Loket <span id="resi-loket"></span><br>
            <strong>Waktu    :</strong> <span id="resi-waktu"></span><br>
            <strong>Mitra    :</strong> <span id="resi-mitra"></span><br>
            <strong>Tujuan   :</strong> <span id="resi-tujuan"></span><br>
            <strong>Gerbong  :</strong> <span id="resi-gerbong"></span><br>
            --------------------------------<br>
            <strong>Bruto    :</strong> <span id="resi-bruto"></span> Kg<br>
            <strong>Handp.   :</strong> <span id="resi-handpallet"></span> Kg<br>
            <strong>Kayu     :</strong> <span id="resi-kayu"></span> Kg<br>
            --------------------------------<br>
            <strong>NETTO    : <span id="resi-netto"></span> Kg</strong><br>
            --------------------------------
        </div>
        <div style="text-align: center; margin-top: 10px;">
            *** Terima Kasih ***
        </div>
    </div>

    <!-- ALPINE.JS COMPONENT LOGIC -->
    <script>
        function penimbanganWorkspace() {
            return {
                // Initial State Variables
                kayuInputs: [null],
                tujuanList: @json($tujuanList),
                allMitras: @json($mitraList),
                allTransaksi: @json($transaksi),
                semuaGerbong: @json($semuaGerbong),
                
                selectedTujuan: '',
                selectedGerbong: '',
                searchMitra: '',
                showDropdown: false,
                isSubmitting: false,
                form: { berat_kotor: '', handpallet: '' },

                activeMitraTab: '',
                activeGerbongTab: '',
                activeDetailView: false,
                selectedTujuanKey: '',

                showPindahModal: false,
                modalData: { id: '', kode_gerbong: '', nama_mitra: '' },

                /**
                 * Inisialisasi komponen Alpine.js
                 */
                init() {
                    // Watchers untuk mencegah nilai input bernilai minus
                    this.$watch('form.berat_kotor', val => { if (val < 0) this.form.berat_kotor = 0; });
                    this.$watch('form.handpallet', val => { if (val < 0) this.form.handpallet = 0; });
                    this.$watch('kayuInputs', array => {
                        array.forEach((v, i) => { if (v < 0) this.kayuInputs[i] = 0; });
                    }, { deep: true });

                    // Auto-select Mitra pertama & Gerbong pertamanya saat load
                    if (this.uniqueMitras.length > 0) {
                        this.selectMitraTab(this.uniqueMitras[0]);
                    }

                    @if(session('last_id'))
                        this.cetakResi("{{ session('last_id') }}");
                    @endif
                },

                /**
                 * Memilih Tab Mitra & Auto Reset Gerbong Aktif ke Opsi Pertama
                 */
                selectMitraTab(m) {
                    this.activeMitraTab = m;
                    this.activeDetailView = false;
                    
                    const availableGerbongs = this.uniqueGerbongByActiveMitra;
                    if (availableGerbongs.length > 0) {
                        this.activeGerbongTab = availableGerbongs[0];
                    } else {
                        this.activeGerbongTab = '';
                    }
                },

                /**
                 * GETTER: Kalkulasi Berat Bersih (Netto) secara Real-Time
                 * Rumus: Bruto - Handpallet - Total Kayu
                 */
                get hitungBeratBersih() {
                    const bruto = parseFloat(this.form.berat_kotor) || 0;
                    const handpallet = parseFloat(this.form.handpallet) || 0;
                    
                    const totalKayu = this.kayuInputs.reduce((sum, val) => {
                        return sum + (parseFloat(val) || 0);
                    }, 0);

                    const netto = bruto - handpallet - totalKayu;
                    return netto < 0 ? 0 : netto;
                },

                /**
                 * Validasi Mitra Resmi di sisi Client
                 */
                validateMitraSelection() {
                    this.showDropdown = false;
                    if (!this.searchMitra) return;

                    const match = this.allMitras.find(m => m.toLowerCase() === this.searchMitra.trim().toLowerCase());
                    if (match) {
                        this.searchMitra = match;
                    } else {
                        alert("MITRA TIDAK VALID! Silakan pilih mitra resmi yang terdaftar dari opsi dropdown.");
                        this.searchMitra = '';
                    }
                },

                /**
                 * Validasi Form sebelum diforward ke Backend
                 */
                validateAndSubmit() {
                    if (!this.searchMitra || !this.allMitras.includes(this.searchMitra.toUpperCase())) {
                        alert("PERINGATAN: Nama mitra wajib dipilih dari daftar resmi KAI!");
                        return;
                    }

                    if (!this.selectedTujuan) {
                        alert("PERINGATAN: Anda belum memilih TUJUAN angkutan!");
                        return;
                    }

                    if (!this.selectedGerbong) {
                        alert("PERINGATAN: Anda belum memilih GERBONG penimbangan!");
                        return;
                    }

                    const formElement = document.getElementById('formPenimbangan');
                    if (!formElement.checkValidity()) {
                        formElement.reportValidity();
                        return;
                    }

                    this.isSubmitting = true;
                    formElement.submit();
                },

                /**
                 * Navigasi Input menggunakan Tombol Enter / Panah
                 */
                focusNext(e) {
                    let inputs = Array.from(document.querySelectorAll('.nav-input'));
                    let index = inputs.indexOf(e.target);
                    if (index > -1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                },
                focusPrev(e) {
                    let inputs = Array.from(document.querySelectorAll('.nav-input'));
                    let index = inputs.indexOf(e.target);
                    if (index > 0) {
                        inputs[index - 1].focus();
                    }
                },

                /**
                 * Manajemen Dynamic Input Kayu
                 */
                addKayu() { this.kayuInputs.push(null); },
                removeKayu() { if (this.kayuInputs.length > 1) this.kayuInputs.pop(); },

                get filteredMitras() {
                    if (!this.searchMitra) return this.allMitras;
                    return this.allMitras.filter(m => m.toLowerCase().includes(this.searchMitra.toLowerCase()));
                },

                selectMitra(m) {
                    this.searchMitra = m;
                    this.showDropdown = false;
                },

                selectFirstMitra(e) {
                    if (this.showDropdown && this.filteredMitras.length > 0) {
                        this.searchMitra = this.filteredMitras[0];
                        this.showDropdown = false;
                        this.focusNext(e);
                    } else {
                        this.validateMitraSelection();
                    }
                },

                get uniqueMitras() {
                    return [...new Set(this.allTransaksi.map(t => t.nama_mitra))];
                },

                /**
                 * GETTER: Daftar Gerbong khusus untuk Mitra Aktif
                 */
                get uniqueGerbongByActiveMitra() {
                    let filtered = this.allTransaksi.filter(t => t.nama_mitra === this.activeMitraTab);
                    return [...new Set(filtered.map(t => t.kode_gerbong))];
                },

                /**
                 * GETTER: Grouping data berdasarkan TUJUAN (Mitra Aktif + Gerbong Aktif)
                 */
                get groupedByTujuan() {
                    let filtered = this.allTransaksi.filter(t => 
                        t.nama_mitra === this.activeMitraTab && 
                        t.kode_gerbong === this.activeGerbongTab
                    );

                    return filtered.reduce((acc, obj) => {
                        let key = obj.tujuan;
                        if (!acc[key]) acc[key] = [];
                        acc[key].push(obj);
                        return acc;
                    }, {});
                },

                hitungTotalTujuan(items) {
                    if (!items || items.length === 0) return 0;
                    return items.reduce((sum, item) => sum + (parseFloat(item.berat_bersih) || 0), 0);
                },

                openDetail(tujuanKey) {
                    this.selectedTujuanKey = tujuanKey;
                    this.activeDetailView = true;
                },

                /**
                 * GETTER: Data detail transaksi (Mitra + Gerbong + Tujuan)
                 */
                get selectedTujuanData() {
                    return this.allTransaksi.filter(t => 
                        t.nama_mitra === this.activeMitraTab && 
                        t.kode_gerbong === this.activeGerbongTab && 
                        t.tujuan === this.selectedTujuanKey
                    );
                },

                /**
                 * Format Angka ke Ribuan Indonesia
                 */
                formatAngka(val) {
                    if (val === null || val === undefined || val === '') return '0';
                    let num = Math.round(parseFloat(val));
                    return new Intl.NumberFormat('id-ID').format(num);
                },

                /**
                 * Format Datetime ke dd/mm/yyyy hh.mm
                 */
                formatWaktu(datetimeStr) {
                    if (!datetimeStr) return '-';
                    let dateable = typeof datetimeStr === 'string' ? datetimeStr.replace(' ', 'T') : datetimeStr;
                    let d = new Date(dateable);

                    if (isNaN(d.getTime())) return datetimeStr;

                    let day = String(d.getDate()).padStart(2, '0');
                    let month = String(d.getMonth() + 1).padStart(2, '0');
                    let year = d.getFullYear();
                    let hours = String(d.getHours()).padStart(2, '0');
                    let minutes = String(d.getMinutes()).padStart(2, '0');

                    return `${day}/${month}/${year} ${hours}.${minutes}`;
                },

                openModalPindah(row) {
                    this.modalData = { id: row.id, kode_gerbong: row.kode_gerbong, nama_mitra: row.nama_mitra };
                    this.showPindahModal = true;
                },

                /**
                 * Otomasi Cetak Resi Thermal via API
                 */
                cetakResi(transaksiId) {
                    fetch(`/penimbangan/resi/${transaksiId}`)
                        .then(response => response.json())
                        .then(res => {
                            if (res.status === 'success') {
                                const data = res.data;
                                document.getElementById('resi-no').innerText = data.no_resi;
                                document.getElementById('resi-loket').innerText = data.loket;
                                document.getElementById('resi-waktu').innerText = data.waktu_penimbangan;
                                document.getElementById('resi-mitra').innerText = data.mitra;
                                document.getElementById('resi-tujuan').innerText = data.tujuan;
                                document.getElementById('resi-gerbong').innerText = data.gerbong;
                                document.getElementById('resi-bruto').innerText = data.berat_kotor;
                                document.getElementById('resi-handpallet').innerText = data.handpallet;
                                document.getElementById('resi-kayu').innerText = data.total_kayu;
                                document.getElementById('resi-netto').innerText = data.berat_bersih;

                                const resiElem = document.getElementById('area-resi-cetak');
                                resiElem.style.display = 'block';
                                window.print();
                                resiElem.style.display = 'none';
                            }
                        })
                        .catch(err => console.error('Gagal mengambil data resi:', err));
                }
            }
        }
    </script>
</body>
</html>