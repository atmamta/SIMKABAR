<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RekapPenimbangan;
use App\Models\Gerbong;
use App\Models\TransaksiPenimbangan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseController extends Controller
{
    /**
     * Tampilan Utama Halaman Rekap Database
     */
    public function index()
    {
        $rekapFiles = RekapPenimbangan::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id'              => $item->id,
                    'nama_file'       => $item->nama_file,
                    'waktu_formatted' => $item->created_at
                        ->locale('id')
                        ->translatedFormat('d F Y - H:i:s'),
                    'status'          => $item->status,
                ];
            })
            ->toArray();

        $gerbongList = Gerbong::where('status', 'Aktif')
            ->pluck('kode_gerbong')
            ->toArray();

        if (empty($gerbongList)) {
            $gerbongList = ['B1', 'B2', 'B3', 'B4', 'B5', 'B6', 'B7', 'B8', 'B9', 'B10', 'B11'];
        }

        return view('database.index', compact('rekapFiles', 'gerbongList'));
    }

    public function cari()
    {
        return redirect()->route('database.index');
    }

    /**
     * Generate Rekap Penimbangan (Ringkasan per Mitra & Gerbong)
     * Format Output Kolom: NO, TANGGAL, GERBONG, MITRA (PT), TUJUAN, TOTAL TONASE (TON)
     *
     * File CSV dibuat secara real-time dan langsung dikirim sebagai unduhan.
     * Tidak ada file fisik yang disimpan di server (aman untuk lingkungan
     * serverless / read-only filesystem seperti Vercel).
     */
    public function generateRekap(Request $request)
    {
        $validated = $request->validate([
            'tanggal'      => ['required', 'date'],
            'kode_gerbong' => ['required', 'string'],
        ]);

        $tanggal = Carbon::parse($validated['tanggal']);
        $kodeGerbong = $validated['kode_gerbong'];

        $namaFile = $this->buildNamaFile($tanggal, $kodeGerbong);
        $rows = $this->fetchRekapRows($tanggal, $kodeGerbong);

        // Catat riwayat generate (metadata saja, tanpa file fisik)
        RekapPenimbangan::create([
            'nama_file'     => $namaFile,
            'kode_gerbong'  => $kodeGerbong,
            'tanggal_rekap' => $tanggal->toDateString(),
            'status'        => 'Done',
        ]);

        return $this->streamCsv($namaFile, $rows, $tanggal);
    }

    /**
     * Unduh Rekap (dibangun ulang secara real-time dari data transaksi
     * terkini setiap kali diunduh, bukan dari file yang di-cache).
     */
    public function downloadFile($id)
    {
        $rekap = RekapPenimbangan::findOrFail($id);

        $tanggal = Carbon::parse($rekap->tanggal_rekap);
        $rows = $this->fetchRekapRows($tanggal, $rekap->kode_gerbong);

        return $this->streamCsv($rekap->nama_file, $rows, $tanggal);
    }

    /**
     * Hapus Riwayat Rekap (hanya menghapus catatan, tidak ada file fisik)
     */
    public function destroy($id)
    {
        $rekap = RekapPenimbangan::findOrFail($id);
        $namaFile = $rekap->nama_file;

        $rekap->delete();

        return redirect()
            ->route('database.index')
            ->with('success', 'Riwayat rekap "' . $namaFile . '" berhasil dihapus.');
    }

    /**
     * Susun nama file rekap berdasarkan gerbong & tanggal.
     */
    private function buildNamaFile(Carbon $tanggal, string $kodeGerbong): string
    {
        $prefix = ($kodeGerbong === 'ALL') ? '' : $kodeGerbong . '_';

        return $prefix . 'Ringkasan_Muat_' . $tanggal->format('j_n_Y');
    }

    /**
     * Query Aggregation: Kelompokkan data transaksi berdasarkan Mitra, Gerbong, dan Tujuan
     */
    private function fetchRekapRows(Carbon $tanggal, string $kodeGerbong)
    {
        $query = TransaksiPenimbangan::query()
            ->select(
                'kode_gerbong',
                'nama_mitra',
                'tujuan',
                DB::raw('SUM(berat_bersih) as total_berat_bersih_kg')
            )
            ->whereDate('tanggal_timbang', $tanggal->toDateString());

        if ($kodeGerbong !== 'ALL') {
            $query->where('kode_gerbong', $kodeGerbong);
        }

        return $query->groupBy('kode_gerbong', 'nama_mitra', 'tujuan')
            ->orderBy('kode_gerbong')
            ->orderBy('nama_mitra')
            ->get();
    }

    /**
     * Bangun CSV di memori/output stream dan kirim langsung sebagai
     * respons unduhan (streamDownload) — tanpa menulis apapun ke disk.
     */
    private function streamCsv(string $namaFile, $rows, Carbon $tanggal)
    {
        $fileNameWithExt = $namaFile . '.csv';

        return response()->streamDownload(function () use ($rows, $tanggal) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 agar Microsoft Excel membaca file secara rapi
            fputs($handle, "\xEF\xBB\xBF");

            // Header Ringkasan
            fputcsv($handle, [
                'No',
                'Tanggal',
                'Gerbong',
                'Nama Mitra',
                'Tujuan',
                'Total Kilogram (kg)'
            ]);

            // Isi Data Ringkasan
            foreach ($rows as $i => $row) {
                fputcsv($handle, [
                    $i + 1,
                    $tanggal->translatedFormat('j/n/y'),
                    $row->kode_gerbong,
                    $row->nama_mitra,
                    $row->tujuan,
                    round($row->total_berat_bersih_kg),
                ]);
            }

            fclose($handle);
        }, $fileNameWithExt, [
            'Content-Type' => 'text/csv',
        ]);
    }
}