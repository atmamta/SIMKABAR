<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PenimbanganController extends Controller
{
    /**
     * Helper privat untuk mengambil daftar nama mitra aktif dari database
     */
    private function getDaftarMitraResmi(): array
    {
        $mitraList = DB::table('master_mitras')
            ->where('is_active', true)
            ->orderBy('nama_mitra', 'asc')
            ->pluck('nama_mitra')
            ->toArray();

        return !empty($mitraList) ? $mitraList : ['KALOG', 'KI8', 'BBX', 'LNP', 'MA', 'JS', 'KIB'];
    }

    /**
     * Halaman Pemilihan Loket
     */
    public function indexLoket()
    {
        return view('penimbangan.loket');
    }

    /**
     * Halaman Utama Workspace Penimbangan
     */
    public function workspace(Request $request)
    {
        $loket = (int) $request->query('loket', 1);
        $today = Carbon::now()->toDateString();

        // Mapping Gerbong Utama per Loket
        $mappingGerbong = [
            1 => ['B9', 'B10', 'B11'],
            2 => ['B6', 'B7', 'B8'],
            3 => ['B4', 'B5'],
            4 => ['B1', 'B2', 'B3'],
        ];

        $gerbongAktif = $mappingGerbong[$loket] ?? ['B9', 'B10', 'B11'];
        $semuaGerbong = ['B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10', 'B11'];

        // 1. Akumulasi Monitoring Kapasitas Gerbong Hari Ini
        $monitoringGerbong = collect($gerbongAktif)->map(function ($kodeGerbong) use ($loket, $today) {
            $totalVolume = DB::table('transaksi_penimbangans')
                ->where('loket', $loket)
                ->where('kode_gerbong', $kodeGerbong)
                ->where('tanggal_timbang', $today)
                ->sum('berat_bersih');

            $kapasitasMax = 20000; // Limit Safe Margin (20 Ton)
            $sisaVolume = $kapasitasMax - $totalVolume;
            $persentase = ($kapasitasMax > 0) ? ($totalVolume / $kapasitasMax) * 100 : 0;

            if ($persentase >= 95 || $sisaVolume <= 0) {
                $status = 'Bahaya';
            } elseif ($persentase >= 80) {
                $status = 'Waspada';
            } else {
                $status = 'Aman';
            }

            return [
                'kode_gerbong' => $kodeGerbong,
                'total_volume' => (float) $totalVolume,
                'sisa_volume'  => (float) max($sisaVolume, 0),
                'status'       => $status
            ];
        });

        // 2. Master Data Mitra Resmi
        $mitraList = $this->getDaftarMitraResmi();

        // 3. Master Data Stasiun Tujuan
        $rutePemberhentian = [
            'JAKG', 'CKP', 'PGB', 'JTB', 'CNP', 'TG', 
            'PK', 'SMT', 'NBO', 'CU', 'BJ', 'BBT'
        ];

        $tujuanList = DB::table('master_stasiuns')
            ->where('is_active', true)
            ->get()
            ->sortBy(function ($item) use ($rutePemberhentian) {
                $index = array_search($item->kode_stasiun, $rutePemberhentian);
                return $index !== false ? $index : 999;
            })
            ->pluck('kode_stasiun')
            ->values()
            ->toArray();

        if (empty($tujuanList)) {
            $tujuanList = $rutePemberhentian;
        }

        // 4. Data Transaksi Hari Ini
        $transaksi = DB::table('transaksi_penimbangans')
            ->where('loket', $loket)
            ->where('tanggal_timbang', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Ringkasan Tonase Per Mitra Hari Ini
        $ringkasanMitra = DB::table('transaksi_penimbangans')
            ->select('nama_mitra', DB::raw('SUM(berat_bersih) as total_tonase'))
            ->where('loket', $loket)
            ->where('tanggal_timbang', $today)
            ->groupBy('nama_mitra')
            ->get();

        return view('penimbangan.workspace', compact(
            'loket', 'today', 'monitoringGerbong', 'mitraList', 'tujuanList', 
            'transaksi', 'ringkasanMitra', 'gerbongAktif', 'semuaGerbong'
        ));
    }

    /**
     * Memproses Penyimpanan & Validasi Batas Safe Margin 20 Ton
     */
    public function store(Request $request)
    {
        $mitraResmi = $this->getDaftarMitraResmi();

        $validated = $request->validate([
            'loket'        => 'required|integer',
            'berat_kotor'  => 'required|numeric|min:0.01',
            'handpallet'   => 'nullable|numeric|min:0',
            'kayu'         => 'nullable|array',
            'nama_mitra'   => ['required', 'string', Rule::in($mitraResmi)], // RESTRICTION WHITELIST MITRA
            'tujuan'       => 'required|string',
            'kode_gerbong' => 'required|string',
        ], [
            'nama_mitra.in' => 'Nama mitra tidak terdaftar dalam sistem resmi PT KAI!'
        ]);

        $handpallet = $validated['handpallet'] ?? 0;
        
        $kayuList = array_values(array_filter($validated['kayu'] ?? [], function ($val) {
            return !is_null($val) && $val !== '';
        }));
        $totalKayu = array_sum($kayuList);

        $beratBersih = $validated['berat_kotor'] - $handpallet - $totalKayu;
        if ($beratBersih <= 0) {
            return redirect()->back()->with('error', 'Berat bersih tidak valid! (Harus lebih dari 0)');
        }

        $today = Carbon::now()->toDateString();
        
        $currentVolume = DB::table('transaksi_penimbangans')
            ->where('loket', $validated['loket'])
            ->where('kode_gerbong', strtoupper($validated['kode_gerbong']))
            ->where('tanggal_timbang', $today)
            ->sum('berat_bersih');

        if (($currentVolume + $beratBersih) > 20000) {
            $sisa = 20000 - $currentVolume;
            return redirect()->back()->with('error', "Penimbangan ditolak! Muatan gerbong overload. Sisa kapasitas gerbong {$validated['kode_gerbong']} hanya " . number_format($sisa, 0, ',', '.') . " Kg.");
        }

        DB::table('transaksi_penimbangans')->insert([
            'loket'           => $validated['loket'],
            'tanggal_timbang' => $today,
            'berat_kotor'     => $validated['berat_kotor'],
            'handpallet'      => $handpallet,
            'kayu_list'       => json_encode($kayuList),
            'total_kayu'      => $totalKayu,
            'berat_bersih'    => $beratBersih,
            'nama_mitra'      => strtoupper($validated['nama_mitra']),
            'tujuan'          => strtoupper($validated['tujuan']),
            'kode_gerbong'    => strtoupper($validated['kode_gerbong']),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return redirect()->route('penimbangan.workspace', ['loket' => $validated['loket']])
            ->with('success', 'Penimbangan berhasil dicatat!');
    }

    /**
     * Hapus Transaksi Timbangan
     */
    public function destroy($id)
    {
        DB::table('transaksi_penimbangans')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Transaksi penimbangan berhasil dihapus.');
    }

    /**
     * Pindah Gerbong atau Mitra (Mendukung Pindah Lintas Loket)
     */
    public function updatePindah(Request $request, $id)
    {
        $mitraResmi = $this->getDaftarMitraResmi();

        $validated = $request->validate([
            'kode_gerbong' => 'required|string',
            'nama_mitra'   => ['required', 'string', Rule::in($mitraResmi)],
        ], [
            'nama_mitra.in' => 'Pilihan nama mitra tidak terdaftar!'
        ]);

        $targetGerbong = strtoupper($validated['kode_gerbong']);

        $mappingLoket = [
            'B9' => 1, 'B10' => 1, 'B11' => 1,
            'B6' => 2, 'B7'  => 2, 'B8'  => 2,
            'B4' => 3, 'B5'  => 3,
            'B1' => 4, 'B2'  => 4, 'B3'  => 4,
        ];

        $loketBaru = $mappingLoket[$targetGerbong] ?? 1;

        DB::table('transaksi_penimbangans')->where('id', $id)->update([
            'kode_gerbong' => $targetGerbong,
            'loket'        => $loketBaru,
            'nama_mitra'   => strtoupper($validated['nama_mitra']),
            'updated_at'   => now(),
        ]);

        return redirect()->back()->with('success', "Data berhasil dipindahkan ke Gerbong {$targetGerbong} (Loket {$loketBaru}).");
    }
}